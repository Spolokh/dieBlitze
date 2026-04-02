@props(['feed' => null, 'limit'])

@php
    $feed = $attributes->get('feed-url') ?: $feed;
    $items = (new \App\Services\RssFeedService($feed))->getItems($limit);
@endphp

<div {{ $attributes->class(['rss-feed row g-4']) }}>
    @foreach($items as $row)
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card">
                @if($row['picture']) 
                <a class="w-100 overflow-hidden" role="card">
                    <img src="{{e($row['picture'])}}" 
                        alt="{{ e($row['title']) }}"
                        class="card-img-top"
                        loading="lazy"
                        onerror="this.style.display='none'"
                    />
                </a>
                @endif
                <div class="card-body">
                    <p class="card-text text-muted small">
                        {{ $row['author'] ? e($row['author']) : e($row['creator']) }}
                    </p>
                    <p class="card-text text-muted small">
                        <i class="fa fa-clock-o"></i> {{ \Carbon\Carbon::parse($row['date'])->format('d.m.Y H:i') }}
                    </p>
                    <h6 class="card-title text-decoration-none">
                        <a class="text-decoration-none" href="{{ $row['link'] }}">{{ e($row['title']) }}</a>
                    </h6>
                    <div class="card-text">
                        {!! e($row['description']) !!}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
