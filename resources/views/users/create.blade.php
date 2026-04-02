@extends('layouts.app') 
@section('title', 'Добавить пользователя') 
@section('content') 
			<div class="breadcrumbs text-muted my-4">
				<h5><a href="{{ route('users.index') }}" class="text-decoration-none">Пользователи</a> 
				<i class="fa fa-angle-right mx-1"></i> @yield( 'title' )</h5>
			</div>
            <div class="my-3 p-3 bg-body rounded shadow-sm">
				<h6 class="border-bottom pb-3 mb-3">@yield('title')</h6>
                <div class="row">
					<div class="col-md-9"> 
						<form id="createUser" action="{{ route('users.create') }}" method="POST" class="validity" enctype="multipart/form-data">
							@include ('templates.result') 
							<div class="mb-3 row text-muted">
								<label for="" class="col-12 col-lg-3 col-form-label">Логин: <sup>*</sup></label>
								<div class="col-12 col-lg-9">
									<input type="text" class="form-control" name="username" data-missing="Заполните поле Логин" placeholder="Логин" required />
								</div>
							</div>
							<div class="mb-3 row text-muted">
								<label for="" class="col-12 col-lg-3 col-form-label">Имя:</label>
								<div class="col-12 col-lg-9">
									<input type="text" class="form-control" name="name" placeholder="Имя" />
								</div>
							</div>
							<div class="mb-3 row text-muted">
								<label for="" class="col-12 col-lg-3 col-form-label">Email: <sup>*</sup></label>
								<div class="col-12 col-lg-9">
									<input type="email" class="form-control" name="mail" data-missing="Заполните поле Email" placeholder="Email" required />
								</div>
							</div>
							<div class="mb-3 row text-muted">
								<label for="" class="col-12 col-lg-3 col-form-label">Роль: <sup>*</sup></label>
								<div class="col-12 col-lg-9">
									<select name="usergroup" id="usergroup" class="form-select" required>
									@foreach($groups as $k => $row)
										<option value="{{ $k }}" @selected(5 === $k)>
											{{ $row }}
										</option>
									@endforeach
									</select>
								</div>
							</div>
							<div class="mb-3 row text-muted">
								<label for="" class="col-lg-3 col-form-label">Загрузить аватар:</label>
								<div class="col-lg-9">
									<input type="file" name="avatar" class="form-control"
										accept="image/jpeg, image/png, image/gif"/>
									<div class="upImageResult"></div>
								</div>
							</div>
							<div class="mb-3 row text-muted">
								<label for="" class="col-12 col-lg-3 col-form-label">Пароль: <sup>*</sup></label>
								<div class="col-12 col-lg-9">
									<input type="text" name="password" class="form-control" placeholder="Пароль" required />
								</div>
							</div>
							<div class="mb-3 row text-muted">
								<label for="" class="col-12 col-lg-3 col-form-label">Повторить: <sup>*</sup></label>
								<div class="col-12 col-lg-9">
									<input type="text" name="password_confirmation" class="form-control" placeholder="Повторить пароль" required />
								</div>
								</div>
							<div class="mb-3 row text-muted">
								<label for="" class="col-12 col-lg-3 col-form-label">Активность: <sup>*</sup></label>
								<div class="col-12 col-lg-9">
									<select name="deleted" id="deleted" class="form-select" required>
									@foreach(['Активен', 'Не активен'] as $k => $row) 
										<option value="{{ $k }}">
											{{ $row }} 
										</option>
									@endforeach 
									</select>
								</div>
							</div>
							<div class="mb-3 row">
								<label for="" class="col-12 col-lg-3 col-form-label"></label>
								<div class="col-12 col-lg-9">
									@csrf 
									<button id="subInput" accesskey="s" type="submit" class="btn btn-primary w-25">Добавить</button>
									<button id="delInput" accesskey="r" type="reset" class="btn btn-secondary w-25">Очистить</button>
								</div>
							</div>
						</form>
					</div>
					<div class="col-md-3 text-center">
						<a class="circleAvatar">
							<img id="srcImage" class="object-fit-cover rounded-circle" src="/img/camera.png" />
						</a>
					</div>
				</div>
			</div>
@endsection
@push('scripts') 
		<script src="/js/jquery.validity.js"></script>
		<script>
			jQuery(function($) {
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

				$('#createUser').find('[name="avatar"]')
					.on('change', function() {
						uploadImage(this.files[0], this, 5);
					}).end()
					.on('submit', function(e) {
						$(this).valid()
							? $(this).submit()
							: e.preventDefault();
				}).validity();

				// $('#createUser').find('[name="avatar"]').change(function() {
				// 	uploadImage(this.files[0], this, 5);
				// });
			});
		</script>
@endpush