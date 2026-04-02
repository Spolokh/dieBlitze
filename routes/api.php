<?php

use App\Models\{Post, Vote};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Http,
    Route
};

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

Route::middleware([StartSession::class, 'auth:web'])->get('/users/json', function(Request $request) {
    dd($request->user());
})->name('api.users.json');

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

Route::get('/test', function(Request $request) {
    dd($request->user());
})->name('api.test');

// Route::get('/api/geoip', function(Request $request) {
//     // Валидация и очистка входных данных
//     $data = $request->validate([
//         'ip' => 'nullable|ip',
//     ]);

//     $ip  = $data['ip'] ?? $request->ip();
//     $url = config('services.geoip.url');
//     $api = Http::get($url . $ip, [ 
//         'lang' => app()->getLocale()
//     ]);

//     if ($api->failed() || $api->json('status') !== 'success') {
//         return response()->json([
//             'ip'    => $ip,
//             'error' => 'Failed to fetch GeoIp data',
//         ], 503);
//     }

//     $object = $api->object();
//     $status = $api->status();
//     return view('notices.location', compact('object'))->with('status', $status);
// })->name('api.geoip');
