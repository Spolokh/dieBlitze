
		<div class="container">
			<form id="login" method="post" action="{{ route('login') }}" class="login row my-2">
				@include ('templates.result') 
				<div class="input-group my-2">
					<input class="form-control" name="username" placeholder="Логин" type="text" data-missing="Заполните поле логин"  required /> 
					<span class="input-group-text">
						<a id="checkname"><i class="fa fa-user fa-fw" aria-hidden="true"></i></a>
					</span>
				</div>
				<div class="input-group my-2">
					<input class="form-control" name="password" placeholder="Пароль" type="password" data-missing="Заполните поле пароль" required />
					<span class="input-group-text">
						<a id="checkpass"><i class="fa fa-lock fa-fw" aria-hidden="true"></i></a>
					</span>
				</div>
				<div class="col-12 col-md-8 col-lg-6 my-2 text-start">
					<a href="{{route('registration')}}">Регистрация</a>
				</div>
				@csrf 
			</form>	
		</div>
		
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
