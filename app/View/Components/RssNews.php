<?php

namespace App\View\Components;

use Closure;
use App\Services\RssFeedService;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class RssNews extends Component
{
    public $items;

    /**
     * Create a new component instance.
     */
    public function __construct(public string $url, public int $limit = 12, public ?string $view = null)
    {
        try {
            $this->items = collect( (new RssFeedService($url))->getItems($limit) );
        } catch (\Throwable $e) {
            logger()->error('RssNews component error: ' .$e->getMessage(), [
                'url' => $url
            ]);
            $this->items = collect();
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.rss-news');
    }
}
