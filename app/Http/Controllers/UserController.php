<?php

/**
 *      /\_/\   _
 *     ( *W* )<((
 *      )   (  ))
 *     ( _ _ )//
 */
namespace App\Http\Controllers;

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
        $draw = $request->input('draw');
        $skip = $request->integer('start', 0);
        $take = $request->integer('length', 10);

        $groups = $request->integer('groups', 0);
        $active = $request->boolean('delete', false);
        $search = $request->input('search.value');

        $orders = $request->get('order');
        $column = $request->get('columns');

        $order = $request->input('order.0.dir', 'DESC');
        $index = $request->integer('order.0.column', 0);
        $field = $request->input("columns.{$index}.data", 'date');

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
        $data = (clone $users)->orderBy($field, $order)
            ->skip($skip)
            ->take($take)
            ->get()
            ->map(fn($row) => [  
                'id'        => $row->id,
                'date'      => $row->date,
                'mail'      => $row->mail,
                'name'      => $row->name ?: $row->username,
                'usergroup' => $row->group?->name,
                'avatar'    => $row->avatarUrl,
            ])->values();

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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
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
        $data = $data = $request->validated();

        if (empty($data['password'])) {
             unset($data['password']);
        }

        try {
            $user->update($data);
            $status = 200;
            $result = "Данные пользователя $user->username успешно сохранены.";
        } catch (\Throwable $e) {
            $status = 403;
            $result = 'Ошибка запроса при редактировании, попробуйте позже.';
            logger()->error($result, [
                'exception' => $e->getMessage()
            ]);
        } finally {
            return response()->json([
                'success' => $status === 200,
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
    public function store(StoreRequest $request): RedirectResponse
    {
        abort_unless($request->user()->isAdmin(), 403, 'Доступ запрещён');
        $data = $request->validated();
        User::create($data);
        return redirect()->route('users.create')
            ->with('success', 'Пользователь успешно создан!');
    }
}
