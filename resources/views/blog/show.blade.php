@extends('layouts.app')
@section('title', stripslashes($post->title))
@section('description', stripslashes($post->story->description))
@section('styles')
    <link rel="stylesheet" href="/css/blog.css"/>
@endsection
@section('content') 
            <div class="breadcrumbs text-muted my-4">
                <h5>
                    <a href="{{ route('blog.index') }}" class="text-decoration-none">Блог</a> 
                    <i class="fa fa-angle-right mx-1" aria-hidden="true"></i> {!! stripslashes($post->title) !!}
                </h5>
            </div>
            <!--container-->
            <div id="container" class="my-3 p-4 bg-body rounded shadow-sm">
                <article id="post-{{ $post->id }}" class="post border-bottom">
                    <div class="published mb-3">
                        <time class="d-inline-block me-1">
                            <i class="fa fa-clock-o"></i> {{ $post->dateFormatted }}
                        </time>
                        <a class="d-inline-block text-decoration-none">
                            <i class="fa fa-user-circle-o"></i> {{ $post->postAuthor }}
                        </a>
                    </div>
                    <h1 class="title my-4">
                        {!! stripslashes($post->title) !!}
                    </h1>
                    <div class="story my-4">
                        {!!
                            str(stripslashes($post->content))->replace('{nl}', "\n")
                        !!}
                    </div>
                    <footer class="d-flex mb-4"> 
                        <ul class="actions flex-grow-1 list-unstyled ya-share2" data-limit="7" 
                            data-services="vkontakte,twitter,lj,telegram,skype,whatsapp">
                        </ul>
                        <ul class="stats list-unstyled">
                            <li><a href="#" data-bs-toggle="tooltip" data-views title="Просмотров: {{ $post->views }}">
                                    <i class="fa fa-eye"></i> <span>{{ $post->views }}</span>
                                </a>
                            </li>
                            <li><a href="#" data-bs-toggle="tooltip" data-id="{{ $post->id }}" data-votes title="Голосов: {{ $post->votes }}">
                                    <i class="fa fa-heart"></i> <span>{{ $post->votes }}</span>
                                </a>
                            </li>
                            <li><a href="#" data-bs-toggle="tooltip" data-bookmark title="Добавить в закладки">
                                    <i class="fa fa-bookmark"></i>
                                </a>
                            </li>
                            @can('update', $post)
                            <li>
                                <a href="{{ route('blog.edit', $post) }}" data-bs-toggle="tooltip" title="Редактировать">
                                    <span><i class="fa fa-pencil"></i></span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </footer>
                </article>
                <div id="allComments" class="comments position-relative">
                    <h5 class="mb-3">Комментарии ({{ $post->comments->count() }})</h5>
                    @if ($post->comments->count()) 
                        @include('blog.comments', ['comments' => $post->comments])
                    @else 
                    <p class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Комментариев пока нет.
                    </p>
                    @endif 
                    <x-blog.comment-form
                        :post="$post"
                        id="commentForm"
                        title="Оставьте комментарий"
                    />
                </div>
                <x-blog.neighbors
                    :prev="$prev"
                    :next="$next"
                />
                <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="false">
                    Account
                </button>
                <div class="collapse" id="account-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="#" class="link-dark rounded">New...</a></li>
                        <li><a href="#" class="link-dark rounded">Profile</a></li>
                        <li><a href="#" class="link-dark rounded">Settings</a></li>
                        <li><a href="#" class="link-dark rounded">Sign out</a></li>
                    </ul>
                </div>

                <div class="wrapper news my-4">
                    <h5 class="category-title pb-1 my-3">
                        <i class="fa fa-rss fa-lg me-2" aria-hidden="true"></i> Последние новости
                    </h5>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
                        <div class="col">
                            <div class="card info-grid border overflow-hidden position-relative">
                                <img class="card-img object-fit-cover" src="/img/pages.gallery-1.jpg" alt="" />
                                <div class="card-img-overlay text-white position-absolute">
                                    <h3>Lorem ipsum dolor sit amet</h3>
                                    <div class="line overflow-hidden"></div>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
                                       sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                    </p>
                                    <a href="#" data-src="/img/pages.gallery-3.jpg" data-fancybox="galleryT2"
                                        class="w-100 py-2 text-white start-0 bottom-0 position-absolute stretched-link"
                                        data-caption="Ehre im Sturm - Die Letzte Fahne Deutschlands."
                                    >
                                        Подробнее »
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card info-grid border shadow overflow-hidden position-relative">
                                <img class="card-img object-fit-cover" src="/img/pages.gallery-2.jpg" alt="" />
                                <div class="card-img-overlay text-white position-absolute cursor-pointer">
                                    <h3>Lorem ipsum dolor sit amet</h3>
                                    <div class="line overflow-hidden"></div>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
                                       sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                    </p>
                                    <a data-src="/img/pages.gallery-3.jpg" data-fancybox="galleryT2"
                                        class="w-100 py-2 text-white start-0 bottom-0 position-absolute stretched-link"
                                        data-caption="Ehre im Sturm - Die Letzte Fahne Deutschlands."
                                    >
                                        Подробнее »
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card info-grid border shadow overflow-hidden position-relative">
                                <img class="card-img object-fit-cover" src="/img/pages.gallery-3.jpg" alt="" />
                                <div class="card-img-overlay text-white position-absolute cursor-pointer">
                                    <h3>Lorem ipsum dolor sit amet</h3>
                                    <div class="line overflow-hidden"></div>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
                                       sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                    </p>
                                    <a data-src="/img/pages.gallery-3.jpg" data-fancybox="galleryT2"
                                        class="w-100 py-2 text-white start-0 bottom-0 position-absolute stretched-link"
                                        data-caption="Ehre im Sturm - Die Letzte Fahne Deutschlands."
                                    >
                                        Подробнее »
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card info-grid border shadow overflow-hidden position-relative">
                                <img class="card-img object-fit-cover" src="/img/pages.gallery-4.jpg" alt="" />
                                <div class="card-img-overlay text-white position-absolute cursor-pointer">
                                    <h3>Lorem ipsum dolor sit amet</h3>
                                    <div class="line overflow-hidden"></div>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
                                       sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                    </p>
                                    <a data-src="/img/pages.gallery-3.jpg" data-fancybox="galleryT2"
                                        class="w-100 py-2 text-white start-0 bottom-0 position-absolute stretched-link"
                                        data-caption="Ehre im Sturm - Die Letzte Fahne Deutschlands."
                                    >
                                        Подробнее »
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 
                <div class="wrapper news my-4">
                    <h5 class="category-title pb-1 my-3">
                        <i class="fa fa-rss fa-lg me-2" aria-hidden="true"></i> Последние новости
                    </h5>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
                        <div class="col">
                            <div class="card shadow-sm bg-body h-100">
                                <a class="w-100 overflow-hidden" role="card">
                                    <img src="/img/post_1.jpg" alt="" class="card-img-top" />
                                </a>
                                <div class="card-body">
                                    <h6>Lorem ipsum dolor sit amet</h6>
                                </div>
                                <div class="card-footer">
                                    <a class="text-muted stretched-link text-decoration-none" href="#" role="link">Подробнее &raquo;</a>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card shadow-sm bg-body h-100">
                                <a class="w-100 overflow-hidden" role="card">
                                    <img src="/img/post_4.jpg" alt="" class="card-img-top" />
                                </a>
                                <div class="card-body">
                                    <h6>Schwarze Sonne – Deutsche dramatische epische Ballade</h6>
                                </div>
                                <div class="card-footer">
                                    <a class="text-muted stretched-link text-decoration-none" href="#" role="link">Подробнее &raquo;</a>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card shadow-sm bg-body h-100">
                                <a class="w-100 overflow-hidden" role="card">
                                    <img src="/img/post_5.jpg" alt="" class="card-img-top" />
                                </a>
                                <div class="card-body">
                                    <h6>Ein Herz aus Stahl – Epische Deutsche Hymne</h6>
                                </div>
                                <div class="card-footer">
                                    <a class="text-muted stretched-link text-decoration-none" href="#" role="link">
                                        Подробнее &raquo;
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card shadow-sm bg-body h-100">
                                <a class="w-100 overflow-hidden" role="card">
                                    <img src="/img/post_2.jpg" alt="" class="card-img-top" />
                                </a>
                                <div class="card-body">
                                    <h6>Ehre im Sturm – Die Letzte Fahne Deutschlands.</h6>
                                </div>
                                <div class="card-footer">
                                    <a class="text-muted stretched-linktext-decoration-none" href="#" role="link">
                                        Подробнее &raquo;
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--//container-->
@endsection
@push('scripts') 
        <script src="/js/jquery.validity.js"></script>
        <script src="//yastatic.net/share2/share.js" async></script>
        <script>
        jQuery(function($) {
            $('#commentForm').on('submit', function(e) { 
                e.preventDefault();
                let $form  = $(this),
                    params = $form.serialize(),
                    action = $form.attr('action'),
                    loader = $form.find('.loader'),
                    result = $form.find('.result'),
                    notice = null,
                    errors = [];

                if (!$form.valid()) {
                    return false;
                }

                $.post({
                    url: action,
                    data: params,     // ← КРИТИЧЕСКИ ВАЖНО: data: params
                    dataType: 'json', // ← Явно указываем ожидаемый тип ответа
                    beforeSend: function() {
                        loader.toggleClass('d-none');
                    }       
                }).done(xhr => {
                    reloadContainer(window.location.href, '#allComments');
                    notice = Template.render('#tplDone', {message: xhr.message});
                }).fail(xhr => {
                    errors = (xhr.responseJSON?.errors) ? Object.values(xhr.responseJSON.errors).flat() : [xhr.responseJSON?.message];
                    notice = Template.render('#tplFail', {errors: errors});
                }).always(xhr => {
                    loader.toggleClass('d-none'); 
                    result.html(notice)
                        .fadeOut(3000);
                    if (xhr.success == true) {
                        $form[0].find('input').not(':hidden').val('');
                        $form[0].find('textarea').val('');
                    }
                });
            }).validity();

            $(document).on('submit', '#editComment', function(e) {
                e.preventDefault();
                let form   = $(this),
                    params = form.serialize(), 
                    action = form.attr('action'),
                    loader = form.find('.loader'),
                    result = form.find('.result'),
                    notice = null,
                    errors = [];

                $.post({
                    url:  action,
                    data: params,     // ← КРИТИЧЕСКИ ВАЖНО: data: params
                    dataType: 'json', // ← Явно указываем ожидаемый тип ответа
                    beforeSend: function(xhr) {
                        // xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                        loader.toggleClass('d-none');
                    }       
                }).done(xhr => {
                    reloadContainer(window.location.href, '#allComments');
                    notice = Template.render('#tplDone', {message: xhr.message});
                    setTimeout(() => {
                        $('#myModal').modal('hide');
                    }, 1200);
                }).fail(xhr => {
                    errors = (xhr.responseJSON.errors)
                        ? Object.values(xhr.responseJSON.errors).flat() : [xhr.responseJSON.message];
                    notice = Template.render('#tplFail', {errors: errors});	 
                }).always(xhr => {
                    result.html(notice);
                    loader.toggleClass('d-none'); 
                });
            });

            $('a.quickreply').on('click', function(e) {
                e.preventDefault();
                let reply = $(this), 
                    form = $('#commentForm'),
                    commid = reply.data('id'),
                    postid = form.find('[name="post_id"]').val(),
                    parent = form.find('[name="parent"]').val(),
                    comment = reply.data('comment');
                if (commid == parent || commid == 0) {
                    form.find('[name="parent"]').val(0);
                    $('#allComments').append(form);
                } else {
                    form.find('[name="parent"]').val(commid);
                    $('#'+comment).append(form);
                }
                return false;
            });

            function reloadContainer(url, container, callback = null) {
                if (!container) {
                    container = '#container';
                }
                let loader = $(container).find('.loader');
                    loader.toggleClass('d-none');

                $.get(url).done(data => {
                    let $template = $('<div>').append($.parseHTML(data));
                        $template = $template.find(container).html();
                    if ($template) {
                        $(container).html($template);
                    }
                    if (typeof callback === 'function') {
                        setTimeout(() => {
                            callback();
                        }, 50);
                    }
                }).fail(() => {
                    alert('Не удалось обновить список комментариев');
                }).always(() => {
                    loader.toggleClass('d-none');
                });
            }

            $('[data-vote]').click(function(e) {
                e.preventDefault();
                let $this  = $(this),
                    $post  = $this.data('id'),
                    $vote  = $this.find('span');

                // Отправляем на сервер
                $.post('http://applaravelzw/api/api/post/addvote', {
                        post_id: $post
                    }).done(xhr => {
                        $vote.text(xhr.votes);
                    }).fail(xhr => {
                        alert('Ошибка: ' + (xhr.responseJSON?.message || 'Неизвестная ошибка'));
                    }).always(xhr => {
                    });
                });
        });

        var tooltipList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')),
            tooltipMaps = tooltipList.map(function(el) {
            return new bootstrap.Tooltip(el)
        });
        </script>
@endpush
