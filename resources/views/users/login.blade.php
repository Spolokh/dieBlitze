<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>Авторизация</title>
		<meta charset="{{ env('APP_CHARSET') }}">
		<meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/svg+xml" href="/img/ico.svg">
        <base href="{{ env('APP_URL') }}">
        <link rel="stylesheet" href="/css/style.css"/>
    </head>
    <body class="d-flex flex-column">
		<div class="vh-100 d-flex justify-content-center align-items-center">
			<div class="container">
				<div class="row d-flex justify-content-center">
					<div class="col-12 col-md-8 col-lg-5">
				  		<div class="card bg-white rounded shadow-sm p-4">
							<form id="login" method="post" action="{{ route('login') }}" class="login row mb-3">
								<div class="col mb-2">
									<h5 class="text-muted">Авторизация</h5>
								</div>
								@include ('templates.result') 
								<div class="input-group mb-3">
									<input class="form-control" name="username" placeholder="Логин" type="text" data-missing="Заполните поле логин"  required /> 
									<span class="input-group-text">
										<a id="checkname"><i class="fa fa-user fa-fw" aria-hidden="true"></i></a>
									</span>
								</div>
								<div class="input-group mb-3">
									<input class="form-control" name="password" placeholder="Пароль" type="password" data-missing="Заполните поле пароль" required />
									<span class="input-group-text">
										<a id="checkpass"><i class="fa fa-lock fa-fw" aria-hidden="true"></i></a>
									</span>
								</div>
								<div class="col-12 col-md-8 col-lg-6 mb-3 text-start">
									<a href="{{route('registration')}}">Регистрация</a>
								</div>
								<div class="col-12 col-md-8 col-lg-6 mb-3 text-end">
									<a href="#">Забыли пароль?</a>
								</div>
								<div class="col-12">
									@csrf 
									<button type="submit" class="btn btn-primary w-100 rounded">
										<i class="fa fa-sign-in" aria-hidden="true"></i> Войти
									</button>	 
								</div>
							</form>
				  		</div>
					</div>
			  	</div>
			</div>
		</div>
		<footer class="mt-auto py-3 text-center">
			<div class="container">
				<span class="text-muted">© 1998-{{date('Y')}}
					<a href="{{ env('APP_URL') }}">
						{{ str_replace('_', ' ', env('APP_NAME')) }}
					</a>
				</span> 
			</div>
		</footer>
		<script src="js/app.js"></script>
        <script src="js/functions.js"></script>
		<script src="js/jquery.validity.js"></script>
		<script>
			jQuery(function($) {
				const inputs = $('.form-control[required]');
				inputs.focus(function() {
					$(this).next().find('i.fa').css({
						transform: 'scale(1.5, 1.5)',
						transition: '.4s',
						color: '#0d6efd'
					});   
				}).blur(function() {
					$(this).next().find('i.fa').css({
						transform: 'scale(1, 1)',
						transition: '.4s',
						color: '#212529'
					}); 
				});

				$('#checkpass').click(function(e) {
					e.preventDefault();
					$('input[name="password"]').attr('type', function(i, type) {
						return type != 'password'
							? 'password'
							: 'text';
					});
					$(this).find('i').toggleClass('fa-unlock-alt');
				});

				$('#login').submit(function(e) {
					let form = $(this);
						form.valid() 
							? form.submit()
							: e.preventDefault();
				}).validity();

				var formValidate = function (inputs, submit) {
					return {
						submit: submit || $('button[type="submit"]'),
						inputs: inputs || $('.form-control[required]'),
						updateButton: function () {
							let Valid = this.inputs.toArray().every(
								input => $(input).hasClass('valid')
							);
							this.submit.prop('disabled', !Valid);
						},
						inputsValide: function () {
							let self = this;
							this.updateButton();
							this.inputs.on('input blur', function () {
                				$(this).toggleClass('valid', $(this).val().trim() !== '');
								self.updateButton();
							});
						}
					};
				};

				var formValidate = formValidate().inputsValide();
			});
		</script>
	</body>
</html>
