@extends('layouts.app')
@section('styles') 
        <link rel="stylesheet" href="/css/carousel/owl.carousel.min.css"/>
        <link rel="stylesheet" href="/css/fancybox.css"/>
@endsection 
@section('content') 
            <div class="breadcrumbs text-muted my-4">
                <h5>Привет, {{ auth()->check() ? auth()->user()->username : 'Гость' }} !</h5>
            </div>
            <div class="my-1 p-3 bg-body rounded shadow-sm">
                <div class="wrapper news">
                    <h5 class="category-title pb-1 mb-3"><i class="fa fa-rss fa-lg me-2" aria-hidden="true"></i> Последние новости</h5>
                    <x-rss-feed url="https://forum.print-forum.ru/external.php" 
                        :limit="9"
                    />
                </div>
                <div class="wrapper news mb-4">
                    <h5 class="category-title pb-1 mb-3"><i class="fa fa-rss fa-lg me-2" aria-hidden="true"></i> Последние новости</h5>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="card shadow-sm bg-body">
                                <a class="w-100 overflow-hidden" role="card">
                                    <img src="/uploads/posts/post_avril-ramona-lavin.jpg" alt="" class="card-img-top" />
                                </a>
                                <div class="card-body">
                                    <h5>Lorem ipsum dolor sit amet</h5>
                                    <small class="text-muted">Donec sed odio dui. Etiam porta sem malesuada magna mollis euismod. Nullam id dolor id nibh ultricies vehicula ut id elit.</small>
                                </div>
                                <div class="card-footer">
                                    <small><a data-caption="Donec sed odio dui. Etiam porta sem malesuada magna mollis euismod. Nullam id dolor id nibh ultricies vehicula ut id elit." data-fancybox="gallery" class="text-muted text-decoration-none" href="/img/post_1.jpg" role="link">Подробнее &raquo;</a></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm bg-body">
                                <a class="w-100 overflow-hidden" role="card">
                                    <img src="/uploads/posts/rakul-preet-singh.jpg" alt="" class="card-img-top" />
                                </a>
                                <div class="card-body">
                                    <h5>Lorem ipsum dolor sit amet</h5>
                                    <small class="text-muted">Donec sed odio dui. Etiam porta sem malesuada magna mollis euismod. Nullam id dolor id nibh ultricies vehicula ut id elit.</small>
                                </div>
                                <div class="card-footer">
                                    <small><a data-caption="Donec sed odio dui. Etiam porta sem malesuada magna mollis euismod. Nullam id dolor id nibh ultricies vehicula ut id elit." data-fancybox="gallery" class="text-muted text-decoration-none" href="/img/post_4.jpg" role="link">Подробнее &raquo;</a></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm bg-body">
                                <a class="w-100 overflow-hidden" role="card">
                                    <img src="/uploads/posts/14-sekretov-populyarnyx-blogov-ot-forbes.jpg" alt="" class="card-img-top" />
                                </a>
                                <div class="card-body">
                                    <h5>Lorem ipsum dolor sit amet</h5>
                                    <small class="text-muted">Donec sed odio dui. Etiam porta sem malesuada magna mollis euismod. Nullam id dolor id nibh ultricies vehicula ut id elit.</small>
                                </div>
                                <div class="card-footer">
                                    <small><a data-caption="Donec sed odio dui. Etiam porta sem malesuada magna mollis euismod. Nullam id dolor id nibh ultricies vehicula ut id elit." data-fancybox="gallery" class="text-muted text-decoration-none" href="/img/post_5.jpg" role="link">Подробнее &raquo;</a></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wrapper cinema mb-4">
                    <h5 class="category-title pb-1 mb-3"><i class="fa fa-file-image-o fa-lg me-2" aria-hidden="true"></i> Cinema</h5>
                    <div id="carousel" class="carousel owl-carousel owl-theme">
                        <div class="card border text-center shadow-sm">
                            <a title="" href="/img/sl1.jpg" data-fancybox="galleryS1">
                                <img src="/img/sl1.jpg" loading="lazy" alt="" />
                            </a>
                        </div>
                        <div class="card border text-center shadow-sm">
                            <a title="Раскол короны (2018)" href="/img/sl2.jpg" data-fancybox="galleryS1">
                                <img src="/img/sl2.jpg" loading="lazy" alt="" />
                            </a>
                        </div>
                        <div class="card border text-center shadow-sm">
                            <a title="У холмов есть глаза (2007)" href="/img/sl3.jpg" data-fancybox="galleryS1">
                                <img src="/img/sl3.jpg" loading="lazy" alt="" />
                            </a>
                        </div>
                        <div class="card border text-center shadow-sm">
                            <a title="" href="/img/sl4.jpg" data-fancybox="galleryS1">
                                <img src="/img/sl4.jpg" loading="lazy" alt="" />
                            </a>
                        </div>
                        <div class="card border text-center shadow-sm">
                            <a title="" href="/img/sl5.jpg" data-fancybox="galleryS1">
                                <img src="/img/sl5.jpg" loading="lazy" alt="" />
                            </a>
                        </div>
                        <div class="card border text-center shadow-sm">
                            <a title="" href="/img/sl6.jpg" data-fancybox="galleryS1">
                                <img src="/img/sl6.jpg" loading="lazy" alt="" />
                            </a>
                        </div>
                        <div class="card border text-center shadow-sm">
                            <a title="" href="/img/sl7.jpg" data-fancybox="galleryS1">
                                <img src="/img/sl7.jpg" loading="lazy" alt="" />
                            </a>
                        </div>
                        <div class="card border text-center shadow-sm">
                            <a title="" href="/img/sl9.jpg" data-fancybox="galleryS1">
                                <img src="/img/sl9.jpg" loading="lazy" alt="" />
                            </a>
                        </div>
                        <div class="card border text-center shadow-sm">
                            <a title="" href="/img/sl10.jpg" data-fancybox="galleryS1">
                                <img src="/img/sl10.jpg" loading="lazy" alt="" />
                            </a>
                        </div>
                        <div class="card border text-center shadow-sm">
                            <a title="" href="/img/sl11.jpg" data-fancybox="galleryS1">
                                <img src="/img/sl11.jpg" loading="lazy" alt="" />
                            </a>
                        </div>
                    </div>
                </div>
    
                <div class="wrapper video my-4">
                    <h5 class="category-title pb-1 mb-3"><i class="fa fa-file-video-o fa-lg me-2" aria-hidden="true"></i> Последние видео</h5>
                    <div class="row g-4">
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="card shadow-sm bg-body">
                                <a class="w-100 overflow-hidden" role="card">
                                    <img src="https://img.youtube.com/vi/ey4pFtT0HIM/maxresdefault.jpg" alt="" class="card-img-top" />
                                </a>
                                <div class="card-body">
                                    <h6>Ein Herz aus Stahl – Epische Deutsche Hymne</h6>
                                    <small class="text-muted">
                                        Willkommen bei GERMANIA EPICA – der Heimat epischer deutscher Hymnen und Balladen.
                                    </small>
                                </div>
                                <div class="card-footer">
                                    <small>
                                        <a href="/uploads/video/Herz_aus_Stahl.mp4"
                                            data-caption="Ein Herz aus Stahl – Epische Deutsche Hymne"
                                            data-fancybox="video"
                                            class="text-muted text-decoration-none stretched-link">Смотреть видео &raquo;
                                        </a>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="card shadow-sm bg-body">
                                <a class="w-100 overflow-hidden" role="card">
                                    <img src="https://img.youtube.com/vi/VjxObubomug/maxresdefault.jpg" alt="" class="card-img-top" />
                                </a>
                                <div class="card-body">
                                    <h6>Schwarze Sonne – Deutsche dramatische epische Ballade.</h6>
                                    <small class="text-muted">
                                        Eine episch-dramatische Hymne über Stärke, Einheit und den Willen, im Chaos standzuhalten.
                                    </small>
                                </div>
                                <div class="card-footer">
                                    <small>
                                        <a href="https://youtu.be/VjxObubomug/" 
                                            data-fancybox="youtube" 
                                            class="text-muted text-decoration-none stretched-link">Ссылка на YouTube &raquo;
                                        </a>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card shadow-sm bg-body">
                                <a class="w-100 overflow-hidden" role="card">
                                    <img src="https://img.youtube.com/vi/f6lZ9jY_dTE/maxresdefault.jpg" alt="" class="card-img-top" />
                                </a>
                                <div class="card-body">
                                    <h6>Ehre im Sturm – Die Letzte Fahne Deutschlands.</h6>
                                    <small class="text-muted">
                                        Willkommen bei Ewiger Klang – der ewige Herzschlag deutscher Seele.
                                    </small>
                                </div>
                                <div class="card-footer">
                                    <small>
                                        <a class="text-muted text-decoration-none stretched-link" 
                                            data-fancybox="video" 
                                            data-caption="Ehre im Sturm – Die Letzte Fahne Deutschlands." 
                                            href="https://youtu.be/f6lZ9jY_dTE?si=tTWJBrFlPu-u-Vn4">Смотреть видео &raquo;
                                        </a>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card shadow-sm bg-body">
                                <a class="w-100 overflow-hidden" role="card">
                                    <img src="https://img.youtube.com/vi/8d06pYfzD48/maxresdefault.jpg" alt="" class="card-img-top" />
                                </a>
                                <div class="card-body">
                                    <h6>Ehre im Sturm – Die Letzte Fahne Deutschlands.</h6>
                                    <small class="text-muted">
                                        Willkommen bei Ewiger Klang – der ewige Herzschlag deutscher Seele.
                                    </small>
                                </div>
                                <div class="card-footer">
                                    <small>
                                        <a class="text-muted text-decoration-none stretched-link" 
                                            data-fancybox="video" 
                                            data-caption="Ehre im Sturm – Die Letzte Fahne Deutschlands." 
                                            href="https://youtube.com/shorts/Ld-_pJ5iFfw?si=dBJmdQZHeajbMZA7">Смотреть видео &raquo;
                                        </a>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wrapper">
                    <h5 class="category-title pb-1 my-3"><i class="fa fa-rss fa-lg me-2" aria-hidden="true"></i> Последние новости</h5>
                    <div class="row g-4">
                       <div class="col-12 col-md-6 col-lg-3">
                            <figure class="card shadow info-bottom overflow-hidden position-relative">
                                <img class="w-100 object-fit-cover" src="/img/pages.gallery-1.jpg" alt="" />
                                <figcaption class="position-absolute cursor-pointer">
                                    <div class="info text-white">
                                        <h3>Lorem ipsum dolor sit amet</h3>
                                        <div class="line overflow-hidden"></div>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                    </div>
                                    <a data-src="/img/pages.gallery-1.jpg" data-fancybox="galleryT2"
                                        class="w-100 py-2 text-white start-0 bottom-0 position-absolute stretched-link"
                                        data-caption="Ein Herz aus Stahl – Epische Deutsche Hymne"
                                    >
                                        Подробнее »
                                    </a>
                                </figcaption>
                            </figure>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <figure class="card border shadow info-bottom overflow-hidden position-relative">
                                <img class="w-100 object-fit-cover" src="/img/pages.gallery-2.jpg" alt="" />
                                <figcaption class="position-absolute cursor-pointer">
                                    <div class="info text-white">
                                        <h3>Lorem ipsum dolor sit amet</h3>
                                        <div class="line overflow-hidden"></div>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                    </div>
                                    <a data-src="/img/pages.gallery-2.jpg" data-fancybox="galleryT2"
                                        class="w-100 py-2 text-white start-0 bottom-0 position-absolute stretched-link"
                                        data-caption="Schwarze Sonne - Deutsche dramatische epische Ballade."
                                    >
                                        Подробнее »
                                    </a>
                                </figcaption>
                            </figure>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <figure class="card border shadow info-bottom overflow-hidden position-relative">
                                <img class="w-100 object-fit-cover" src="/img/pages.gallery-3.jpg" alt="" />
                                <figcaption class="position-absolute cursor-pointer">
                                    <div class="info text-white">
                                        <h3>Lorem ipsum dolor sit amet</h3>
                                        <div class="line overflow-hidden"></div>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                    </div>
                                    <a data-src="/img/pages.gallery-3.jpg" data-fancybox="galleryT2"
                                        class="w-100 py-2 text-white start-0 bottom-0 position-absolute stretched-link"
                                        data-caption="Ehre im Sturm - Die Letzte Fahne Deutschlands."
                                    >
                                        Подробнее »
                                    </a>
                                </figcaption>
                            </figure>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <figure class="info-bottom card border shadow overflow-hidden position-relative">
                                <img class="w-100 object-fit-cover" src="/img/pages.gallery-4.jpg" alt="" />
                                <figcaption class="position-absolute cursor-pointer">
                                    <div class="info text-white">
                                        <h3>Lorem ipsum dolor sit amet</h3>
                                        <div class="line overflow-hidden"></div>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                    </div>
                                    <a data-src="/img/pages.gallery-4.jpg" data-fancybox="galleryT2"
                                        class="w-100 py-2 d-block text-white start-0 bottom-0 position-absolute stretched-link"
                                        data-caption="Ehre im Sturm - Die Letzte Fahne Deutschlands."
                                    >
                                        Подробнее »
                                    </a>
                                </figcaption>
                            </figure>
                        </div>
                    </div>
                </div>
                <div class="wrapper servises mb-2">
                    <div class="row">
                        <div class="col-md-6">
                            <a data-aos="fade-right" data-aos-delay="100" role="button" href="javascript:;" data-fancybox-text="Ваш текст или HTML" class="btn shadow rounded text-start text-muted bg-light mb-4 p-3">
                                <i class="fa fa-check fa-lg text-success me-2"></i> Модальное окно
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a data-aos="fade-left" data-aos-delay="100" role="button" href="javascript:;" class="btn shadow rounded text-start text-muted bg-light mb-4 p-3" data-fancybox="" data-type="ajax" data-src="/notice/location/57.129.25.66">
                                <i class="fa fa-check fa-lg text-success me-2"></i> Открыть Ajax
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a data-aos="fade-right" data-aos-delay="100" role="button" class="btn shadow rounded text-start text-muted bg-light mb-4 p-3" data-fancybox="video" data-caption="Ссылка на файл MP4" href="/uploads/video/Herz_aus_Stahl.mp4">
                                <i class="fa fa-check fa-lg text-success me-2"></i> Ссылка на файл MP4
                            </a>
                        </div>
                        <div class="col-md-6">    
                            <a data-aos="fade-left" data-aos-delay="100" role="button" class="btn shadow rounded text-start text-muted bg-light mb-4 p-3" data-fancybox="youtube" href="https://youtu.be/UMTDZs2Qxt4">
                                <i class="fa fa-check fa-lg text-success me-2"></i> Ссылка на YouTube
                            </a>
                        </div>
                    </div>
                </div>
            </div>
@endsection
@push('scripts')
<script src="/js/owl.carousel.min.js"></script>
        <script src="/js/jquery.fancybox.min.js"></script>
        <script src="/js/aos/aos.js"></script>
        <script>
            jQuery(function($) {
                $('[data-fancybox-text]').click(function(e) {
                    e.preventDefault();
                    var html = $(this).data('fancybox-text');
                    $.fancybox.open(html, {
                        width : 420,
                        height: 'auto',
                        autoResize: true
                    });
                });

                // window.fancyboxOpen = function(html) {
                //     $.fancybox.open({
                //         src: html,
                //         type: 'html'
                //     });
                // };

                // $.fancybox.open({
                //     src: '/notice/location/57.129.25.66',
                //     type: 'ajax',
                //     ajax: {
                //         settings: {
                //             headers: { 'X-Requested-With': 'XMLHttpRequest' },
                //             { foo: 'bar' },
                //             method: 'POST'
                //         }
                //     }
                // });

                $('#carousel').owlCarousel({
                    nav: true,
                    loop: true,
                    dots: false,
                    items: 2,
                    margin: 24, 
                    autoplay: true,
                    animateIn: 'fadeIn',
                    animateOut: 'fadeOut',
                    smartSpeed: 500,
                    autoplayTimeout: 4000,
                    autoplayHoverPause: true,
                    navText: [
                        '<span class="fa fa-lg fa-chevron-prev"></span>', 
                        '<span class="fa fa-lg fa-chevron-next"></span>'
                    ],
                    responsive: {
                        0: {
                            items: 1
                        },
                        600: {
                            items: 2
                        },
                        900: {
                            items: 3
                        },
                        1200: {
                            items: 4
                        }
                    }
                });

                $('[data-fancybox^="gallery"]').fancybox({
                    loop: true,
                    transitionEffect: 'circular', // "fade", "slide", "circular", "tube", "zoom-in-out", "rotate"
                    thumbs: {
                        autoStart: false,
                        hideOnClose: true,
                        axis: "x"
                    },
                    buttons: [
                        'zoom',
                        'share',
                        'slideShow',
                        'fullScreen',
                        'download',
                        'thumbs',
                        'close'
                    ],
                    btnTpl: {
                        arrowLeft:
                        '<button data-fancybox-prev class="fancybox-button fancybox-button--arrow_left" title="">' +
                        '<i class="fa fa-chevron-prev" aria-hidden="true"></i>' +
                        '</button>',
                        arrowRight:
                        '<button data-fancybox-next class="fancybox-button fancybox-button--arrow_right" title="">' +
                        '<i class="fa fa-chevron-next" aria-hidden="true"></i>' +
                        '</button>'
                    } 
                });

                AOS.init({
                    once: true,
                    mirror: false,
                    duration: 800,
                    easing: 'ease-in-out'
                });
            });
        </script>
@endpush