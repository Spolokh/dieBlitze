@props(['items']) 

    <div {{ $attributes->class(['rss-news']) }}>
    @forelse($items as $row)
                    <article class="mini-post rounded shadow bg-white">
                        @if($row['picture']) 
                        <a href="{{ e($row['link']) }}" target="_blank" class="image w-100 d-block overflow-hidden">
                            <img src="/img/404.jpg" loading="lazy" class="lazy w-100 h-100"
                                data-src="{{e($row['picture'])}}" alt="{{ e($row['title']) }}"
                            />
                        </a>
                        @endif 
                        <header>
                            <h3>
                                <a href="{{ $row['link'] }}" target="_blank" class="text-decoration-none">
                                    {{ e($row['title']) }}
                                </a>
                            </h3>
                            <time class="published d-block">
                                <i class="fa fa-lg fa-clock-o me-1"></i>
                                {{ \App\Carbon($row['date'])->format('d.m.Y H:i') }}
                            </time>
                            <a href="#" class="author position-absolute">
                                <img src="/uploads/userpics/thumbs/default.png" class="rounded-circle" width="45" alt="" />
                            </a>
                        </header>
                    </article>
                @empty 
                    <p class="alert alert-info">
                        {{ __('Новости временно недоступны') }}
                    </p>
                @endforelse 
            </div>
