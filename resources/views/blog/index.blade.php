@extends('layouts.app') 
@section('title', 'Блог') 
@section('styles')
        <!-- <link rel="preload" href="/css/blog.css" as="style" onload="this.rel='stylesheet'"> -->
        <link rel="stylesheet" href="/css/blog.css" />
@endsection
@section('content') 
            <div class="breadcrumbs text-muted my-4">
				<h5>@yield('title')</h5>
			</div>
            <div class="my-3 p-4 bg-body rounded shadow-sm">
                @include ('templates.result') 
                <form method="GET" action="{{ url()->current() }}" class="search-form mb-4">
                    <div class="input-group mb-4">
                        <input type="search" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="Поиск"/>   
                        <input type="hidden" name="category" id="category">
                        <button class="btn btn-secondary dropdown-toggle" style="width:160px;" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $type ? $types[$type] : __('Тип поста') }}
                        </button>
                        <ul class="dropdown-menu" style="width:160px;">
                            @foreach($types as $k => $type)
                            <li>
                                <a href="{{ request()->fullUrlWithQuery(['type' => $k]) }}"
                                    class="dropdown-item cursor-pointer {{ request('type') == $k ? 'text-primary' : '' }}">
                                    {{ $type }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        <button type="submit" class="btn btn-secondary input-group-text">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </form>
                <div class="row g-4">
                    <div class="col-12 col-lg-8">	
                    @forelse ($query as $row) 
                        <article id="post-{{ $row->id }}" class="post border-bottom {{ $row->hidden ? 'hidden' : '' }} {{ ($row->type !== 'post') ? $row->type : '' }}">
                            <div class="published mb-2">
                                <time class="me-1">
                                    <i class="fa fa-clock-o"></i> {{ $row->dateFormatted }}
                                </time>
                                <a data-bs-toggle="tooltip" title="{{ $row->postAuthor }}" class="text-decoration-none">
                                    <i class="fa fa-user-circle-o"></i> {{ $row->postAuthor }}
                                </a>
                            </div>
                            <h2 class="title mb-4">
                                <a href="{{ route('blog.show', $row) }}" title="{{ $row->id }}">
                                    {{ stripslashes($row->title) }}
                                </a>
                            </h2>
                            <figure class="image overflow-hidden shadow my-2">
                                <img 
                                    src="/img/404.jpg" 
                                    alt="{{ $row->imageUrl ?? 'Изображение' }}" 
                                    data-src="{{ $row->imageUrl }}" 
                                    data-aos="flip-left" 
                                    loading="lazy" 
                                    class="lazy w-100 object-fit-cover"
                                    onerror="this.src='/img/placeholder.jpg'"
                                >
                            </figure>
                            <div class="short my-3">
                                {!! Str::replace('{nl}', "\n", stripslashes($row->excerpt)) !!}
                            </div>
                            <footer class="d-flex mb-4"> 
                                <ul class="actions flex-grow-1 list-unstyled">
                                    <li>
                                        <a href="{{ route('blog.show', $row) }}" title="{{ $row->id }}" class="text-decoration-none more">
                                            Подробнее
                                        </a>
                                    </li>
                                </ul>
                                <ul class="stats list-unstyled">
                                    <li><a href="#" data-bs-toggle="tooltip" data-views title="Просмотров: {{ $row->views }}">
                                            <i class="fa fa-eye"></i> <span>{{ $row->views }}</span>
                                        </a>
                                    </li>
                                    <li><a href="#" data-bs-toggle="tooltip" data-id="{{ $row->id }}" data-vote title="Голосов: {{ $row->votes }}">
                                            <i class="fa fa-heart"></i> <span>{{ $row->votes }}</span>
                                        </a>
                                    </li>
                                    <li><a href="#" data-bs-toggle="tooltip" title="Комментариев: {{ $row->comments }}">
                                            <i class="fa fa-comment"></i> <span>{{ $row->comments }}</span>
                                        </a>
                                    </li>
                                    @can('update', $row)
                                    <li><a id="editorLink" href="#" data-bs-toggle="dropdown" class="dropdown-toggle" title="Редактировать">
                                            <span><i class="fa fa-pencil"></i></span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="editorLink">
                                            <li><a data-edit class="dropdown-item" href="{{ route('blog.edit', $row) }}">Редактировать</a></li>
                                            <li><a data-ajax class="dropdown-item" href="{{ route('blog.edit', $row) }}" title="Пост ID: {{ $row->id }} ({{ $row->dateFormatted }})"
                                                    data-form="editPost"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#myModal"
                                                >Изменить</a></li>
                                            <li><a data-drop class="dropdown-item" href="{{ route('blog.destroy', $row) }}">Удалить</a></li>
                                        </ul>
                                    </li>
                                    @endcan
                                </ul>
                            </footer>
                        </article>
                    @empty 
                        <div class="alert alert-danger">
                            Постов по вашеу запросу не найдено.
                        </div>
                    @endforelse 
                    @if ($count > 0) 
                        <div id="pagination" class="pagination justify-content-center mb-3">
                            {{ $query->withQueryString()->links() }}
                        </div>
                    @endif 
                    </div>
                    <div class="col-12 col-lg-4">
                        @auth 
                        <a href="{{ route('blog.create') }}" target="_blank" class="w-100 mb-4 btn btn-primary">
                            {{ __('Добавить пост') }}
                        </a>
                        @endauth 
                        <x-rss-news 
                            url="https://lenta.ru/rss" :limit="14"
                            class="col-12"
                        />
                    </div>
                </div>
            </div>
@endsection
@push('scripts')
<script src="/js/aos/aos.js"></script>
        <script>
            jQuery(function($) { 
                // $.ajaxSetup({
                //     headers: {
                //         'Accept': 'application/json',
                //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //     }
                // });

                // data-ajax
                $('[data-ajax]').click(function(e) {
                    // e.preventDefault();
                    // let Modal = new bootstrap.Modal($('#myModal')); 
                    //     Modal.show();
                });

                $('[data-drop]').click(function(e) {
                    e.preventDefault();
                });

                $('[data-vote]').click(function(e) {
                    e.preventDefault();
                    let $this  = $(this),
                        $post  = $this.data('id'),
                        $vote  = $this.find('span');

                    // Отправляем на сервер
                    $.post('{{ route("api.post.votes") }}', {
                            post_id: $post
                        }).done(xhr => {
                            $vote.text(xhr.votes);

                            const title = 'Голосов: ' + xhr.votes;
                            const tooltip = bootstrap.Tooltip.getInstance($this[0]);

                            if (tooltip) {
                                tooltip.dispose();
                                $this.attr('title', title); // Обновляем title
                                new bootstrap.Tooltip($this[0]); 
                            }

                        }).fail(xhr => {
                            alert('Ошибка: ' + (xhr.responseJSON?.message || 'Неизвестная ошибка'));
                        }).always(xhr => {
                        
                        });
                });
            });

            AOS.init({
                once: true,
                delay: 300,
                mirror: false,
                duration: 800,
                easing: 'ease-in-out'
            });

            window.addEventListener('load', () => { // document.addEventListener('DOMContentLoaded', () =>
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

            function filterByType(type) { // Обновляем параметр в URL
                let url = new URL(window.location);
                    url.searchParams.set('type', type);
                window.history.pushState({}, '', url);
            }
        </script>
@endpush
