<?php

/**
 *      /\_/\   _
 *     ( *W* )<((
 *      )   (  ))
 *     ( _ _ )//
 */
namespace App\Http\Controllers;

use Log;
use Illuminate\Http\{
    Request,
    Response,
    JsonResponse,
    RedirectResponse
};

use App\Http\Requests\UpdateUserRequest;
use App\Models\{
    User,
    Groups
};

use App\Http\Traits\GetUserGroups;

// use Intervention\Image\ImageManager as Image;

class UserController extends Controller
{
    use GetUserGroups;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $groups = [0 => 'Все группы'] + $this->getGroups();
        return view('users.index', compact('groups'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     */
    public function edit(User $user)
    {
        $groups = $this->getGroups();
        return view('users.edit', compact('user', 'groups'));
    }

    public function json(Request $request): JsonResponse
    {
        $data = [];
        $draw = $request->get('draw');
        $skip = (int) $request->get('start', 0);
        $take = (int) $request->get('length', 10);

        $groups = $request->get('groups', 0);
        $active = $request->boolean('delete', 0);
        $search = $request->get('search')['value'];

        $orders = $request->get('order');
        $column = $request->get('columns');

        $indexes = $orders[0]['column'];
        $sorting = $orders[0]['dir'] ?? 'ASC'; 
        $columns = $column[$indexes]['data'] ?? 'id';

        $users = User::select(User::FIELDS)
            ->with('group:id,name')
            ->when($search, fn($query) => 
                $query->whereAny([
                   'mail', 
                   'name',
                ], 'like', $search.'%')
            )
            ->when($groups, fn($query) => 
                $query->inGroups($groups)
            )
            ->when($active, fn($query) => 
                $query->isActive($active)
            );

        $count = $users->count();
        $query = $users->orderBy($columns, $sorting)
            ->skip($skip)
            ->take($take)
            ->get();

        $data = $query->map(fn($row) => [
            'id'        => $row->id,
            'date'      => $row->date,
            'mail'      => $row->mail,
            'name'      => $row?->name ?? $row->username,
            'usergroup' => $row->group?->name,
            'avatar'    => $row->avatarUrl,
        ]);

        return response()->json([
            'draw' => $draw,
            'data' => $data, 
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $users
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        // dd($user);
        try {
            $name = $user->username;
            $user->delete();
            $status = 200;
            $result = 'Пользователь '.$name.' успешно удалён';
        } catch (\Throwable $e) {
            $status = 403;
            $result = 'Ошибка запроса при удалении пользователя';
            logger()->error($result,  [
                'exception' => $e->getMessage()
            ]);
        } finally {
            return redirect()->route('users.index')
                ->with('success', $result);
        }
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $auth = $request->user(); 
        
        abort_unless(( $auth?->isAdmin() || $auth->id === $user->id ) , 403, 'Доступ запрещён');

        try {
            $user->update($request->updateUserData());
            $status = 200;
            $result = "Данные пользователя $user->username успешно сохранены.";
        } catch (\Throwable $e) {
            $status = 403;
            $result = 'Ошибка запроса, попробуйте позже.';
            logger()->error('Ошибка запроса при редактировании юзера', [
                'exception' => $e->getMessage()
            ]);
        } finally {
            return response()->json([
                'success' => true,
                'message' => $result,
            ], $status);
        }
    }

    public function create()
    {
        $groups = $this->getGroups();
        return view('users.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // abort_unless($request->user()->isAdmin(), 403, 'Доступ запрещён');
        $data = $request->validate([
            'name'      => 'nullable|between:3,20',
            'mail'      => 'required|email|unique:users,mail',
            'deleted'   => 'required|boolean',
            'username'  => 'required|string|between:6,20',
            'password'  => 'required|confirmed|between:6,20',
            'usergroup' => 'required',
        ], [
            'name.between'       => 'Поле "Имя" должно содержать от 3-х до 50-ти символов',
            'mail.unique'        => 'Такой "Email" уже зарегистрирован',
            'mail.required'      => 'Поле "Email" обязательно для заполнения.',
            'username.between'   => 'Поле "Логин" должно содержать от 6-и до 20-ти символов.',
            'username.required'  => 'Поле "Логин" обязательно для заполнения.',
            'password.between'   => 'Пароль должен содержать от 8-и до 20-ти символов',
            'password.required'  => 'Поле "Пароль" обязательно для заполнения.',
            'password.confirmed' => 'Ведённые вами пароли не совпадают',
        ]);

        $data['password'] = bcrypt($data['password']);

        User::create($data);

        return redirect()->route('users.create')
            ->with('success', 'Пользователь успешно создан!');
    }

    private function handleImageUpload(Request $request, Users $user): void
    {
        if (!$request->hasFile('avatar')) {
            return;
        }

        $request->validate([
            'avatar' => 'image|mimes:jpg,png,jpeg,gif|max:2048',
        ], [
            'avatar.image' => 'Ваш файл не является картинкой',
            'avatar.mimes' => 'Ваш файл не является картинкой',
            'avatar.max'   => 'Ваш файл не должен превышать 2МБ',
        ]);

        // $image = Image::gd()->read($request->file('avatar')); // Инициализация Intervention (v3.x)

        try {

            $username = $user->username;
            $filename = 'userpics/' .$username. '.jpg';
            Storage::disk('public')->put($filename, $image->toJpeg(95));

            // if ($request->has('cropping')) {
            //     $image->crop(
            //         (int) $request->width,
            //         (int) $request->height,
            //         (int) $request->x,
            //         (int) $request->y,
            //     )->scale(width: 350); 
            //     // ->cover(350, 350);
            //     $filename = 'userpics/thumbs/' .$username. '.jpg';
            //     Storage::disk('public')->put($filename, $image->toJpeg(95));
            // }
            // Users::find($request->user)->fill(['avatar' => 'jpg'])->save(); 

            $result = back()->with('success', 'Ваш аватар обновлён!');

        } catch (\Throwable $e) {
            logger()->error('Ошибка отправки файла: ', [
                'exception' => $e->getMessage()
            ]);
            $result = back()->with('error', 'Что-то пошло не так. Попробуйте снова.');
        } 
        //finally {
            // return $result;
        //}
    }
}
