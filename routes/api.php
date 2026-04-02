<?php

use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Illuminate\Session\Middleware\StartSession;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function(Request $request) {
    return $request->user();
});

Route::post('/api/post/addvote', function (Request $request) {
    $data = $request->validate([
        'post_id' => 'required|integer|exists:posts,id',
    ]);

    Vote::create($data);
    $id = $data['post_id'];
    $votes = Post::whereKey($id)->increment('votes');
    $total = Post::whereKey($id)->value('votes');
    return response()->json(['votes' => $total]);
})->name('api.post.votes');
