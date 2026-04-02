<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>@yield( 'title', str_replace('_', ' ', env('APP_NAME')) )</title>
        <meta charset="{{env('APP_CHARSET')}}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="{{ $description ?? str_replace('_', ' ', env('APP_DESC')) }}" />
        <meta name="keywords" content="" />
        <base href="{{ env('APP_URL') }}">
        <link rel="icon" type="image/svg+xml" href="/img/ico.svg" />
        <link rel="canonical" hreflang="{{ app()->getLocale() }}" href="{{ url()->current() }}" />
        <link rel="alternate" type="application/rss+xml" title="{{str_replace('_', ' ', env('APP_NAME'))}}" href="{{ url('rss.xml') }}" />
        <link rel="stylesheet" href="/css/style.css" />
        <link rel="stylesheet" href="/fonts/roboto/style.css" />
        @yield('styles') 
    </head>
    <body class="d-flex flex-column">
        <nav class="navbar navbar-expand-xl navbar-dark bg-dark shadow" aria-label="Sixth navbar">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    {{str_replace('_', ' ', env('APP_NAME'))}}
                </a>
                <a class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
                    <span class="navbar-toggler-icon"></span>
                </a>
                <div class="collapse navbar-collapse" id="menu">
                    <ul class="navbar-nav me-auto mb-2 mb-xl-0">
                        <!--li><a class="nav-link active" href="{{ url('/') }}">Главная</a></li-->
                        <li><a class="nav-link{{ request()->routeIs('blog*') ? ' active' : '' }}" href="{{ route('blog.index') }}">Блог</a></li>
                        <li><a class="nav-link{{ request()->routeIs('users*') ? ' active' : '' }}" href="{{ route('users.index') }}">Люди</a></li>
                        <li><a class="nav-link{{ request()->routeIs('countries*') ? ' active' : '' }}" href="{{ route('countries.index') }}">Страны</a></li>
                        <li><a class="nav-link{{ request()->routeIs('notices*') ? ' active' : '' }}" href="{{ route('notices.index') }}">Почта</a></li>
                        <li><a class="nav-link{{ request()->routeIs('contact*') ? ' active' : '' }}" href="{{ route('contact') }}">Контакты</a></li>
                        <li class="dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">Ещё...</a>
                            <ul class="dropdown-menu" aria-labelledby="dropdown01">
                                <li><a class="dropdown-item" href="{{ route('page.index') }}">Страница</a></li>
                                <li><a class="dropdown-item" href="{{ route('page.gallery') }}">Галерея</a></li>
                                <!-- <li><a class="dropdown-item" href="/redactor">Редактор</a></li> -->
                                <li><a class="dropdown-item" href="{{ route('page.shop') }}">Магазин</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="dropdown navbar-nav auth">
                        @auth 
                        <li>
                            <a href="#" style="padding-right:1px;" class="nav-link active dropdown-toggle" id="authUser" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ auth()->user()->username }} <img style="margin:-15px 0;" src="/userpic/thumbs/{{ auth()->user()->avatar ? auth()->user()->username . '.' . auth()->user()->avatar : 'default.png' }}?{{time()}}" alt="" width="38" class="img-fluid mx-1 rounded-circle"/>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="authUser">
                                <li><a class="dropdown-item" href="{{ route('profile.index') }}">
                                    <i class="fa fa-user-circle-o fa-fw"></i> Профиль</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}">
                                    <i class="fa fa-sign-out fa-fw" aria-hidden="true"></i> Выйти</a>
                                </li>
                            </ul>
                        </li>
                        @else 
                        <li><a class="nav-link" href="{{ route('login') }}" data-bs-toggle="modal" data-bs-target="#myModal" title="Авторизация"><i class="fa fa-sign-in" aria-hidden="true"></i> Вход</a></li> 
                        <li><a class="nav-link" href="{{ route('registration') }}">Регистрация</a></li>
                        @endauth 
                    </ul>
                </div>
            </div>
        </nav>
        <main class="container flex-shrink-0">
            @yield('content')
        </main>
        <footer class="footer mt-auto pt-3 text-muted">
			<div class="container py-2">
                <div class="row">
                    <div class="footer-row col-12 col-lg-4 mb-4">
                        <ul class="list-unstyled">
                            <li><a class="text-muted" href="{{ route('home') }}">Главная</a></li>
                            <li><a class="text-muted" href="{{ route('blog.index') }}">Блог</a></li>
                            <li><a class="text-muted" href="{{ route('users.index') }}">Люди</a></li>
                            <li><a class="text-muted" href="{{ route('notices.index') }}">Почта</a></li>
                            <li><a class="text-muted" href="{{ route('countries.index') }}">Страны</a></li>
                            <li><a class="text-muted" href="{{ route('contact') }}">Контакты</a></li>
                        </ul>
                    </div>
                    <div class="footer-row col-12 col-lg-4 mb-4 text-center">
                        <ul class="list-unstyled">
                            <li>PHP version: <a class="text-muted" href="/i.php">{{ PHP_VERSION }}</a></li>
                            <li>Laravel version: {{ app()->version() }}</li>
                            <li>
                                <i class="fa fa-rss"></i> <a class="text-muted" href="{{ url('rss.xml') }}">RSS</a>
                            </li>
                        </ul>
                    </div>
                    <div class="footer-row col-12 col-lg-4 mb-4 text-center">
                        <ul class="social-icons list-unstyled">
                            <li class="d-inline-block">
                                <a class="bg-white d-block text-center overflow-hidden position-relative" href="#">
                                    <i class="fa fa-facebook"></i>
                                </a>
                            </li>
                            <li class="d-inline-block">
                                <a class="bg-white d-block text-center overflow-hidden position-relative" href="#">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </li>
                            <li class="d-inline-block">
                                <a class="bg-white d-block text-center overflow-hidden position-relative" href="#">
                                    <i class="fa fa-telegram"></i>
                                </a>
                            </li>
                            <li class="d-inline-block">
                                <a class="bg-white d-block text-center overflow-hidden position-relative" href="#">
                                    <i class="fa fa-google-plus"></i>
                                </a>
                            </li>
                            <li class="d-inline-block">
                                <a class="bg-white d-block text-center overflow-hidden position-relative" href="#">
                                    <i class="fa fa-whatsapp"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="text-center bg-black py-3">
                <small>
                    © <a href="{{ route('home') }}" class="text-muted text-decoration-none">{{ str_replace('_', ' ', env('APP_NAME')) }}</a> 2020 - {{ date('Y') }}
                </small>
            </div>
		</footer>
        <!--Modal-->
		<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="modalHeader">Авторизация</h5>
						<a class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary" form="">Отправить</button>
						<button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
					</div>
				</div>
			</div>
		</div>
		<div id="showOverlay"></div>
        <a id="nachKopf" class="border text-center rounded-circle position-fixed">
            <i class="fa fa-lg fa-arrow-up"></i>
        </a>
        <!--Шаблоны (невидимые, не выполняются как JS)-->
		<script type="text/template" id="tplDone">
			<div class="alert alert-success alert-dismissible fade show">
				[[message]]
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		</script>
		<script type="text/template" id="tplFail">
			<div class="alert alert-danger alert-dismissible fade show">
				<ul>
				[[eachErrors]]
					<li>[[this]]</li>
				[[/eachErrors]]
				</ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		</script>
        <script src="/js/app.js"></script>
        <script src="/js/functions.js"></script>
        @stack('scripts')
    </body>
</html>
