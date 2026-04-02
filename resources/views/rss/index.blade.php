@extends('layouts.app')
@section('title', 'Новости')
@section('content')

    <div class="breadcrumbs text-muted my-4">
        <h5>Последние новости</h5>
    </div>
     <!-- Кнопка обновления кэша (для админов) -->
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <form action="{{ route('rss.refresh') }}" method="POST" class="d-block mb-4">
            @csrf 
            <button type="submit" class="btn btn-sm btn-outline-primary">
                🔄 Обновить новости
            </button>
        </form>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @forelse($items as $row) 
            <div class="col">
                <div class="card h-100">
                    @if($row['picture']) 
                    <img src="/img/404.jpg" data-src="{{e($row['picture'])}}" loading="lazy" class="lazy card-img-top" alt="{{ e($row['title']) }}" />
                     @endif 
                    <div class="card-body">
                        <p class="card-text text-muted small">
                            {{ date('d.m.Y H:i', strtotime($row['date'])) }} | {{ e($row['author']) }}   
                        </p>
                        <h6 class="card-title">
                            <a href="{{ $row['link'] }}" target="_blank" rel="noopener" class="text-decoration-none">
                                {{ e($row['title']) }}
                            </a>
                        </h6>
                    </div>
                </div>
            </div>
        @empty 
            <div class="alert alert-info">
                Новости временно недоступны. Попробуйте позже.
            </div>
        @endforelse 
        </div>
    </div>
@endsection 
@push('scripts') 
    <script>
        window.addEventListener('load', () => {
            let lazyPictures = document.querySelectorAll('img.lazy'),
                lazyObserver = new IntersectionObserver((e, o) => {
                e.forEach(entry => {
                    if (entry.isIntersecting) {
                        let img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        o.unobserve(img);
                    }
                });
            });
            lazyPictures.forEach(
                img => lazyObserver.observe(img)
            );
        });
    </script>
@endpush 
