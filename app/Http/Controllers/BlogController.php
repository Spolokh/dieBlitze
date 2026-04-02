<?php

namespace App\Http\Controllers;

use DB;
use Storage;
use Carbon\Carbon;
use Illuminate\Http\{
    Request,
    Response,
    JsonResponse,
    RedirectResponse,
};

use App\Models\{
    Post,
    Story,
    Comment
};

use Intervention\Image\ImageManager as Image;

class BlogController extends Controller
{
    public function __construct(private int $limit = 7)
    {
    }

    public function index()
    {
        $user   = request()->get('user');
        $type   = request()->get('type', 'blog');
        $search = request()->get('search');
        $author = request()->get('author');
        $number = request()->get('number', $this->limit);
        $category = request()->get('category');

        $post = Post::select(Post::FIELDS)
            ->with('story:post_id,short')
            ->with('users:id,name')
            ->when($author, fn($query) => 
                $query->byAuthor($author)
            )
            ->search($search);

        if (!$user || !$user->isAdmin()) {
            $post->isType('blog');
        }

        $query = $post->orderBy('date', 'DESC')
            ->paginate($this->limit);

        $count = $query->total();
        $types = Post::TYPES;

        return view('blog.index', compact('query', 'types', 'count', 'search', 'type'));
    }

    public function show($id)
    {   
        $hide = 0;
        $type = 'blog';
        $view = session()->get('views', []);
        $post = Post::select([
            'id', 'type', 'date', 'user_id', 'url', 'title', 'image', 'views', 'votes', 'author'
        ])  ->with('users:id,name')
            ->with('story:post_id,short,full,description')
            ->with(['comments' => fn($query) =>
                $query->select(Comment::FIELDS)
                    ->with('user:id,name,avatar', 'children.user:id,name,avatar')
                    ->where('parent', 0)
                    ->orderBy('date', 'DESC')
            ])->find($id);
        
        if ($post && !in_array($id, $view)) {
            $post->increment('views');
            session()->push ('views', $id);
        }

        $neighbors = cache()->remember("posts:neighbors:$id", 3600, fn() => [
            'prev' => Post::select('id', 'title')->where('id', '<', $id)->latest('id')->first(),
            'next' => Post::select('id', 'title')->where('id', '>', $id)->oldest('id')->first(),
        ]);

        $prev = $neighbors['prev'];
        $next = $neighbors['next']; 
        return view('blog.show', compact('post', 'prev', 'next'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        $types = Post::TYPES;
        return request()->ajax() ? view('blog.form', compact('post', 'types')) : view('blog.edit', compact('post', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'url'   => 'nullable|string|unique:posts,url',
            'type'  => 'required|string|max:5',
            'title' => 'required|string|max:255',
            'short' => 'required|string|max:1000',
            'hidden' => 'required|boolean',
            'full'   => 'nullable|string',
            'description' => 'nullable|string|max:255',
        ], [
            'title.required' => 'Поле "Заголовок" обязательно для заполнения.',
            'short.required' => 'Поле "Короткая версия" обязательно для заполнения.',
        ]);

        $data['description'] ??= str($data['short'])->limit(250);

        try {

            $post = DB::transaction(function() use ($data) {
                $post = Post::create([
                    'url'    => $data['url'],
                    'type'   => $data['type'],
                    'title'  => $data['title'],
                    'hidden' => $data['hidden']
                ]);
                $post->story()->create([
                    'full'        => $data['full'],
                    'short'       => $data['short'],
                    'description' => $data['description']
                ]);
                return $post;
            });
            
            $this->handleImageUpload($request, $post);
            $result = 'Пост "' . $post->title . '" успешно создан';
            return redirect()->route('blog.edit', $post)
                ->with('success', $result);
        } catch (\Throwable $e) {
            $result = 'Ошибка при создании поста';
            logger()->error($result, [
                'exception' => $e->getMessage(),
            ]);
            return redirect()->back()
                ->with('error', $result)->withInput();
        }
    }

    public function create(Request $request)
    {
        $types = Post::TYPES;
        return view('blog.create', compact('types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Post $post) : JsonResponse
    {
        $this->authorize('update', $post);
        $posts = $request->validate([
            'url'    => 'nullable|string|unique:posts,url,' . $post->id,
            'date'   => 'required|string',
            'type'   => 'required|string',
            'title'  => 'required|string',
            'hidden' => 'required|boolean',
        ], [
            'type.required'   => 'Поле "Тип поста" обязательно для заполнения.',
            'title.required'  => 'Поле "Заголовок" обязательно для заполнения.',
            'hidden.required' => 'Поле "Статус" обязательно для заполнения.',
        ]);

        $story = $request->validate([
            'description' => 'nullable|string|max:255',
            'short' => 'required|string',
            'full'  => 'nullable|string',
        ], [
            'short.required' => 'Поле "Короткая версия" обязательно для заполнения.',
        ]);

        $posts['date'] = Carbon::parse($posts['date'])->timestamp;
        $story['description'] ??= str($story['short'])->limit(250);

        try {
            DB::transaction(function() use ($post, $posts, $story) {
                $post->update($posts); // Обновляем пост
                $post->story->update($story);
            });

            $this->handleImageUpload($request, $post);
            $status = 200;
            $result = 'Пост #ID ' . $post->id . ' успешно обновлён';

        } catch (\Throwable $e) {
            $status = 500;
            $result = 'Ошибка запроса при редактировании поста';
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post): RedirectResponse
    {
        $this->authorize('delete', $post);
        try {
            $status = 200;
            $result = $post->delete() ? 'Пост #ID '.$post->id.' успешно удалён' : 'Ошибка запроса при удалении поста';
        } catch (\Throwable $e) {
            $status = 403;
            $result = 'Ошибка запроса при удалении поста';
            logger()->error($result, [
              'exception' => $e->getMessage()
            ]);
        } finally {
            return redirect()->route('blog.index')
                ->with('success', $result);
        }
    }

    private function handleImageUpload(Request $request, Post $post): void
    {
        if ($post->image && $request->has('remove_image')) {
            // Проверка существования
            if (Storage::disk('uploads')->exists('posts/' . $post->image)) {
                Storage::disk('uploads')->delete('posts/' . $post->image);
            }
            $post->update(['image' => null]);
            return;
        }

        if (!$request->hasFile('image')) {
            return;
        }

        $request->validate([
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5120', // макс. 5MB
        ], [
            'image.image' => 'Загружаемый файл не являетя изобажением.',
            'image.mimes' => 'Расширение файла не jpeg, png, jpg или webp.',
        ]);

        $file = $request->file('image');
        $name = ( $request->url ?? str($request->title)->slug() ).'.'.$file->getClientOriginalExtension();
        
        // Storage::disk('uploads')->directoryExists('posts')
        //     OR Storage::disk('uploads')->makeDirectory('posts');

        try {
            Storage::disk('uploads')->putFileAs('posts', $file, $name);
            $post->update(['image' => $name]);
        } catch (\Exception $e) {
            logger()->error('Ошибка обработки изображения' [
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
