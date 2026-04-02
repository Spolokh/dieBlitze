<?php

namespace App\Http\Controllers;

use Log;
use Storage;
use App\Models\{
    User,
    Comment
};

use Illuminate\Http\{
    Request,
    Response,
    JsonResponse,
    RedirectResponse
};

class CommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isUser = request()->user();
        $search = collect(request()->validate([ // Валидация и очистка входных данных
            'ip'     => 'nullable|string|ipv4',
            'type'   => 'nullable|string|max:10',
            'hidden' => 'nullable|boolean',
            'search' => 'nullable|string|max:255',
            'number' => 'nullable|integer|min:5|max:100',
            'author' => 'nullable|integer|exists:users,id',
        ]));

        $comments = Comment::select(Comment::FIELDS)
            ->with('user:id,name,avatar')
            ->when($search->get('ip'), fn($query, $ip) =>
                $query->where('id', $ip)
            )
            ->when($search->get('type'), fn($query, $type) =>
                $query->where('type', $type)
            )
            ->when($search->get('search'), fn($query, $search) =>
                $query->where('comment', 'like', '%'.$search.'%')
            )
            ->when($search->get('author'), fn($query, $author) =>
                $query->where('user_id', $author)
            )
            ->when($isUser?->isNotAdmin(), fn($query) =>
                $query->byAuthor($isUser->id)
            )
            ->orderBy('date', 'DESC');

        $users = cache()->remember('comments:authors:list', 3600, fn() => 
            User::isActive()
                ->whereIn('id', Comment::select('user_id')->distinct())
                ->pluck('username', 'id')
                ->toArray()
        );

        $search['number'] ??= 10;
        $types = ['' => __('Выбрать Тип')] + Comment::TYPES;
        $users = ['' => __('Выбрать автора')] + $users;
        $query = $comments->paginate($search['number']);
        $count = $query->total();
        return view('comments.index', compact('query', 'count', 'users', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return view('comments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        // Ограничение: гости могут комментировать только посты старше 5 минут
        // if (!auth()->check() && $request->post_id) {
        //     $post = Post::select('date')->whereKey($request->post_id);
        //     if ($post->date->diffInMinutes() < 5) {
        //         return response()->json([
        //             'message' => 'Только авторизованные пользователи могут комментировать новые посты.'
        //         ], 403);
        //     }
        // }

        // if ($level === null) { // Если родителя нет — прерываем выполнение с ошибкой валидации
        //     throw ValidationException::withMessages([
        //         'parent' => 'Родительский комментарий не найден.'
        //     ]);
        // }

        $rules = [
            'type'    => 'required|string|in:' . implode(',', array_keys(Comment::TYPES)),
            'parent'  => 'required|integer|min:0', 
            'comment' => 'required|string|max:2000',
            'post_id' => 'required|exists:posts,id',
        ];
    
        if (!auth()->check()) {
            $rules['mail']   = 'required|email|max:255';
            $rules['author'] = 'required|string|max:255|regex:/^[a-zA-Zа-яА-ЯёЁ0-9\s._-]+$/u';
        }

        $data = $request->validate($rules, [
            'comment.max'      => 'Вы превысили количество символов',
            'comment.required' => 'Вы не заполнили поле "комментарий"',
        ]);

        // $data['level'] = 0;

        if ($data['parent'] > 0) {
            $level = Comment::where('id', $data['parent'])->isHidden(0)->value('level');
            $data['level'] = ($level ?? -1) + 1;
        }

        try {
            Comment::create($data);
            $status = 200;
            $result = 'Ваш комментарий успешно добавлен.';
        } catch (\Throwable $e) {
            $status = 403;
            $result = 'Ошибка запроса при добавлении комментария.'; 
            logger()->error($result . ': ', ['exception' => $e->getMessage()]);
        } finally {
            return response()->json([
                'success' => $status === 200,
                'message' => $result,
            ], $status);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        // return view('comments.show', compact('comment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        $types = ['' => 'Выбрать тип'] + Comment::TYPES;
        return request()->ajax()
            ? view('comments.form', compact('comment', 'types'))
            : view('comments.edit', compact('comment', 'types'));   
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Comment $comment): JsonResponse
    {
        $data = $request->validate([
            'mail'    => 'nullable|email',
            'type'    => 'required|string|in:' . implode(',', array_keys(Comment::TYPES)),
            'reply'   => 'nullable|string|between:3,1500',
            'hidden'  => 'required|boolean',
            'comment' => 'required|string',
        ], [
            'mail.email'       => '"Email" введён не корректно',
            'type.required'    => 'Поле "Тип поста" обязательно для заполнения.',
            'reply.between'    => 'Ваш ответ должен содержать от 3-х до 1500-ти символов',
            'hidden.required'  => 'Поле "Статус" обязательно для заполнения.',
            'comment.required' => 'Поле "Комментарий" обязательно для заполнения.',
        ]);

        try {
            $comment->update($data);
            $status = 200;
            $result = 'Комментарий ID: '.$comment->id.' успешно сохранён.';
        } catch (\Throwable $e) {
            $status = 403;
            $result = 'Ошибка запроса, попробуйте позже.'; 
            logger()->error('Ошибка запроса: ', [
                'exception' => $e->getMessage()
            ]);
        } finally {
            return response()->json([
                'success' => $status === 200,
                'message' => $result,
            ], $status);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        try {
            $comment->delete();
            $result = 'Комментарий #ID '.$comment->id.' успешно удалён';
            return redirect()->route('comments.index')
                ->with('success', $result);
        } catch (\Throwable $e) {
            $result = 'Ошибка запроса при удалении комментария';
            // report($e); 
            logger()->error($result . ': ', [
                'exception' => $e->getMessage()
            ]);
            return redirect()->route('comments.index')
                ->with('error', $result);
        }
    }
}
