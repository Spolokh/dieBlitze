<?php

namespace App\Http\Controllers;

use App\Services\RssFeedService;

class RssFeedController extends Controller
{
    public function __construct( 
        private int $take = 12, 
        private string $feed = 'https://lenta.ru/rss'
    ) {
        // $this->middleware('auth');
    }

    public function index()
    {
        $items = (new RssFeedService($this->feed))->getItems($this->take);
        return view('rss.index', compact('items'));
    }

    public function refresh()
    {
        //abort_unless(auth()->user()?->isAdmin(), 403);
        $feed = config('services.rss.url', 'https://lenta.ru/rss');
        cache()->forget("rss.{$feed}");
        return back()->with('success', 'Кэш новостей обновлён');
    }
}
