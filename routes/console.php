<?php

use App\Models\Post;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('generate:feed', function() {
    $this->info('Generating RSS Feed');
    $post = Post::select(['posts.id', 'title', 'url', 'date', 'image', 'author', 'short', 'description'])
        ->join ('story', 'posts.id', '=', 'post_id')
        ->isType('blog')
        ->latest()
        ->take(10)
        ->get();

    $feed = [
      'name'          => str_replace('_', ' ', env('APP_NAME')),
      'url'           => url('rss.xml'), // Link to your rss.xml. eg. https://simplestweb.in/rss.xml
      'description'   => env('APP_DESC', 'Description'),
      'language'      => app()->getLocale(),
      'lastBuildDate' => date(DATE_RSS, $post[0]->date),
    ];
    
    $view = view('rss', compact('post', 'feed')); //Storage::disk('local')->put('rss.xml', $view);
    file_put_contents(public_path('rss.xml'), $view);
    $this->info('Generating RSS Completed');
});

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/
Artisan::command('inspire', function() {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
