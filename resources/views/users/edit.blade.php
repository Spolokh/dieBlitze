@extends('layouts.app') 
@section('title', $user->firstName) 
@section('content') 
			<div class="breadcrumbs text-muted my-4">
				<h5><a href="{{ route('users.index') }}" class="text-decoration-none">Пользователи</a> 
				<i class="fa fa-angle-right mx-1" aria-hidden="true"></i> @yield('title')</h5>
			</div>
			<div class="my-3 p-3 bg-body rounded shadow-sm">
				<h6 class="border-bottom pb-3 mb-3">Редактировать</h6>
				<div class="row">
					<div class="col-md-9">
						<form id="updateUser" action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data" class="position-relative">
							@include ('templates.result')
							<div class="mb-3 row text-muted">
								<label for="" class="col-12 col-lg-3 col-form-label">Логин:</label>
								<div class="col-12 col-lg-9">
									<input class="form-control" value="{{ $user->username }}" disabled />
								</div>
							</div>
							<div class="mb-3 row text-muted">
								<label for="" class="col-12 col-lg-3 col-form-label">Дата регистрации:</label>
								<div class="col-12 col-lg-9">
									<input class="form-control" value="{{ $user->dateFormatter }}" disabled />
								</div>
							</div>
							<div class="mb-3 row text-muted">
								<label for="" class="col-12 col-lg-3 col-form-label">Имя:</label>
								<div class="col-12 col-lg-9">
									<input type="text" class="form-control" name="name" value="{{ $user->name }}" placeholder="Имя" />
								</div>
							</div>
							<div class="mb-3 row text-muted">
								<label for="" class="col-12 col-lg-3 col-form-label">Email:</label>
								<div class="col-12 col-lg-9">
									<input type="email" class="form-control" name="mail" value="{{ $user->mail }}" placeholder="email" required />
								</div>
							</div>
							<div class="mb-3 row text-muted">
								<label for="" class="col-12 col-lg-3 col-form-label">Роль:</label>
								<div class="col-12 col-lg-9">
									<select name="usergroup" id="usergroup" class="form-select" required>
										@foreach($groups as $k => $row)
											<option value="{{ $k }}" @selected($user->usergroup == $k)>
												{{ $row }}
											</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="mb-3 row text-muted">
								<label for="" class="col-12 col-lg-3 col-form-label">Загрузить аватар:</label>
								<div class="col-12 col-lg-9">
									<input type="file" class="form-control fileInput" name="avatar" accept="image/jpeg, image/png, image/gif"/>
									<div class="upImageResult"></div>
								</div>
							</div>
							<div class="mb-3 row text-muted">
								<label for="" class="col-12 col-lg-3 col-form-label">Пароль:</label>
								<div class="col-12 col-lg-9">
									<input type="text" class="form-control" name="password" placeholder="Изменить пароль" />
								</div>
							</div>
							<div class="mb-3 row text-muted">
								<label for="" class="col-12 col-lg-3 col-form-label">Повторить пароль:</label>
								<div class="col-12 col-lg-9">
									<input type="text" class="form-control" name="password_confirmation" placeholder="Повторить пароль" />
								</div>
							</div>
							<div class="mb-3 row text-muted">
								<label for="" class="col-12 col-lg-3 col-form-label">Активность:</label>
								<div class="col-12 col-lg-9">
									<select name="deleted" id="deleted" class="form-select" required>
										@foreach(['Активен', 'Не активен'] as $k => $row)
											<option value="{{ $k }}" @selected($user->deleted == $k)>
												{{ $row }}
											</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="mb-3 row">
								<label for="" class="col-12 col-lg-3 col-form-label"></label>
								<div class="col-12 col-lg-9">
									@method('PUT') 
									@csrf 
									<button id="subInput" accesskey="s" type="submit" class="btn btn-primary w-25">Обновить</button>
									<button id="delInput" accesskey="d" type="submit" class="btn btn-primary w-25" form="deleteUser">Удалить</button>
								</div>
							</div>
							<div class="loader w-100 h-100 top-0 end-0 d-flex position-absolute align-items-center justify-content-center d-none">
								<div class="d-inline-block rounded-circle"></div>
							</div>
						</form>
						<form id="deleteUser" method="POST" action="{{ route('users.destroy', $user) }}">		
							<input type="hidden" name="user" value="{{ $user->id }}">
							@csrf
							@method('DELETE')
						</form>
					</div>
					<div class="col-md-3 text-center">
						<div class="circleAvatar m-auto w-100 position-relative" title="{{$user->name ?: $user->username}}">
							<img id="srcImage" alt="{{ $user->username }}" class="object-fit-cover rounded-circle border" 
								src="{{ $user->AvatarUrl.'?'.time() }}" 
							/>
							@if ($user->avatar) 
							<div class="overlay">
								<a class="position-absolute text-center" id="showImage" title="{{$user->name ?: $user->username}}" href="{{ asset('uploads/userpics/'.$user->username) }}.jpg">
									<i class="fa fa-search-plus" aria-hidden="true"></i>
								</a>
							</div>
							@endif 
						</div>
					</div>
				</div>
			</div>
@endsection 
@push('scripts') 
		<script src="/js/jquery.validity.js"></script>
		<script>
			jQuery(function($){

				$('#deleteUser').submit(function(e) { 
					if (!confirm('Вы уверены, что хотите удалить этот пост? Действие нельзя отменить.')) {
						e.preventDefault();
					}
				});
	
				$('#updateUser').validity().submit(function(e) {
					e.preventDefault();
					let form = $(this)
						, params = form.serialize()
						, action = form.attr('action')
						, method = form.attr('method')
						, loader = form.find('.loader')
						, result = form.find('.result')
						, submit = form.find('[type="submit"]')
						, errors = []
						, html = '';

					$.ajax({
						url    : action,
						data   : params,
						method : method,
						context: result,
						dataType: 'json',
						beforeSend: function () {
							loader.toggleClass('d-none');
						}
					})
					.done(function(xhr) {
						html = Template.render('#tplDone', {message: xhr.message});
            			$(this).html(html);
					})
					.fail(function(xhr) {
						errors = (xhr.responseJSON.errors)
							? Object.values(xhr.responseJSON.errors).map(error => error[0])
							: [xhr.responseJSON.message];
						html = Template.render('#tplFail', {errors: errors});
						$(this).html(html);
					})
					.always(function(xhr) {
						loader.toggleClass('d-none'); // console.log(xhr.responseJSON.message);
					});
				});
				
				$('.fileInput').change(function() {
					uploadImage(this.files[0], this, 5);
				});

				$('#showImage').click(showImage);
			});
		</script>
@endpush
