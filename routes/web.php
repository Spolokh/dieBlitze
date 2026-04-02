<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\Vote;
use App\Models\Post;
use App\Models\Comment;

use App\Http\Controllers\{ 
    RssFeedController,
    CommentController,
    UserController,
    BlogController,
};

Route::get('/', [IndexController::class, 'index'])
    ->name('home');

Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class)
        ->only(['index', 'edit', 'json', 'create', 'update', 'store', 'destroy']);
    Route::resource('comments', CommentController::class);

    Route::get('/geoip', function(Request $request) {
        $data = $request->validate([
            'ip' => 'nullable|ip',
        ]);
    
        $ip  = $data['ip'] ?? $request->ip();
        $api = Http::get(config('services.geoip.url') . $ip, [ 
            'lang' => app()->getLocale(), //'fields' => 1110015
        ]);
    
        $object = $api->object();
        $status = $api->status();
        return ($object->status !== 'success')
            ? response()->json([$object->message], 503)
            : view('notices.location', compact('object'))->with('status', $status);
    })->name('api.geoip');
});

Route::get('/rss', [RssFeedController::class, 'index'])
    ->name('rss.index');

Route::resource('blog', BlogController::class)
    ->parameter('blog', 'post');

Route::controller(LoginController::class)->group(function() {
    Route::get('/logout', [LoginController::class, 'logout'])
        ->name('logout');
    
    Route::match(['get', 'post'], '/login', [LoginController::class, 'index'])
        ->name('login')
        ->middleware('throttle:5,1');
});

Route::post('/comments/edit', function(Request $request) {
    if (!$request->ajax()) {
        abort(403);
    }
    $user = $request->user();
    $data = $request->validate([
        'items'   => 'required|array|min:1|max:100',
        'items.*' => 'integer|exists:comments,id',
        'action'  => 'required|in:hidden,delete',
    ], [
        'action.required' => 'Вы не выбрали действие',
        'items.*.exists'  => 'Один из комментариев не найден',
        'items.required'  => 'Нет комментариев для обработки',
    ]);

    $counts = 0;
    $action = $data['action'];

    $query = Comment::whereIn('id', $data['items'])
        ->when($user?->isNotAdmin(), function($q) use ($user) {
            $q->where('user_id', $user->id);
        });

    $counts = match($action) {
        'delete' => $query->delete(),
        'hidden' => $query->update(['hidden' => true])
    };

    $result = [ // Формирование сообщения (упрощенная логика)
        'hidden' => ['Комментарии не найдены', 'Скрыт 1 комментарий',  "Скрыто комментариев:  {$counts}"],
        'delete' => ['Комментарии не найдены', 'Удалён 1 комментарий', "Удалено комментариев: {$counts}"],
    ];

    $message = match ($counts) {
        0 => $result[$action][0],
        1 => $result[$action][1],
        default => $result[$action][2]
    };

    return response()->json([
        'success' => $counts > 0,
        'message' => $message,
        'counts'  => $counts,
        'action'  => $action,
    ], 200, [], JSON_UNESCAPED_UNICODE);
})->name('comment.edit')->middleware('auth');

// Route::middleware(['auth', 'admin'])
//     ->prefix('admin')
//     ->name('admin.')
//     ->group(function () {
        
//         Route::get('/dashboard', [AdminController::class, 'dashboard'])
//             ->name('dashboard');  // admin.dashboard
        
//         Route::resource('posts', AdminPostController::class);
//         // → admin.posts.index   → GET  /admin/posts
//         // → admin.posts.create  → GET  /admin/posts/create
//         // → admin.posts.store   → POST /admin/posts
//         // → и т.д.
        
//         Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])
//             ->name('users.delete');  // admin.users.delete
//     });
