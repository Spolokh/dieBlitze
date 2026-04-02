<?php

namespace App\Services;

use SimpleXMLElement;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class RssFeedService
{
    protected int $cacheTime = 1200;
    protected string $feedUrl;

    public function __construct(?string $feedUrl = null)
    {
        $this->feedUrl = $feedUrl ?? config('services.rss.url', 'https://lenta.ru/rss');
    }

    public function getItems(int $limit = 12): array
    {
        return Cache::remember("rss.{$this->feedUrl}", $this->cacheTime, function () use ($limit) {
            try {
                $api = Http::timeout(10)->get($this->feedUrl);
                if ($api->failed()) {
                    logger()->error("RSS HTTP error", [
                        'url'    => $this->feedUrl, 
                        'status' => $api->status()
                    ]);
                    return [];
                }

                if ( !$xml = simplexml_load_string($api->body()) ) {
                    logger()->error("RSS parse failed", ['url' => $this->feedUrl]);
                    return [];
                }

                $arrItems = [];
                $rawItems = $xml->channel->item ?? $xml->entry ?? null;

                if ($rawItems instanceof SimpleXMLElement) { // Если это объект, пробуем итерировать

                    foreach ($rawItems as $item) {
                        $arrItems[] = $item;
                    }
                } elseif (is_array($rawItems)) {
                    $arrItems = $rawItems;
                }

                if (empty($arrItems)) {
                    logger()->error("Unknown RSS structure", ['url' => $this->feedUrl]);
                    return [];
                }

                $items = [];
                $query = array_slice($arrItems, 0, $limit);
                
                foreach ($query as $row) {
                    $items[] = [
                        'link'   => (string) $row->link,
                        'date'   => (string) $row->pubDate,
                        'title'  => (string) $row->title,
                        'author' => (string) $row->author,
                        'picture' => $this->getPicture($row),
                        'creator' => $this->getCreator($row),
                        'content' => $this->getContent($row),
                        'description' => (string) $row->description,
                    ];
                }
                
                return $items;
                
            } catch (\Throwable $e) {
                logger()->error("RSS parse error: {$e->getMessage()}");
                return [];
            }
        });
    }

    protected function getPicture(SimpleXMLElement $item): ?string
    {
        return (isset($item->enclosure['url']))
            ? trim($item->enclosure['url'])
            : null;
    }

    protected function getContent(SimpleXMLElement $item): ?string
    {
        $namespaces = $item->getNamespaces(true);
        $content = (isset($namespaces['content']))
            ? $item->children($namespaces['content'])
            : null;
        return (string) ($content?->encoded ?? '');
    }

    protected function getCreator(SimpleXMLElement $item): ?string
    {
        $namespaces = $item->getNamespaces(true);
        $creator = (isset($namespaces['dc']))
            ? $item->children($namespaces['dc'])
            : null;
        return (string) ($creator?->creator ?? '');
    }

    protected function loadXml(string $url): ?SimpleXMLElement
    {
        libxml_use_internal_errors(true); // Подавляем стандартные ошибки PHP

        $xml = simplexml_load_file($url, 'SimpleXMLElement', LIBXML_NOCDATA, stream_context_create( // Загружаем с контекстом
            ['http' => [
                    'timeout' => 10,
                    'header'  => "User-Agent: Laravel RSS Reader"
                ]
            ]
        ));

        if (!$xml) { // Проверяем ошибки
            $errors = libxml_get_errors();
            logger()->error("RSS XML parse error", [
                'url' => $url,
                'errors' => array_map(fn($e) => $e->message, $errors)
            ]);
            libxml_clear_errors();
        }
        return $xml ?: null;
    }
}
