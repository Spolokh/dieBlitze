@extends('layouts.app')
@section('styles') 
		<link rel="stylesheet" href="/css/redactor/redactor.css"/>
@endsection 
@section('title', $post->title) 
@section('content') 
			<div class="breadcrumbs text-muted my-4">
				<h5><a href="{{ route('blog.index') }}" class="text-decoration-none">Блог</a> 
				<i class="fa fa-angle-right mx-1" aria-hidden="true"></i> @yield('title')</h5>
			</div>
			<div class="my-3 p-3 bg-body rounded shadow-sm"><!--h6 class="border-bottom pb-3 mb-3">Редактировать</h6-->
				<ul class="nav nav-tabs mb-4">
					<li><a data-bs-toggle="tab" class="nav-link active" href="#main">{{ __('Основное') }}</a></li>
					<li><a data-bs-toggle="tab" class="nav-link" href="#content">{{ __('Контент') }}</a></li>
					<li><a data-bs-toggle="tab" class="nav-link" href="#picture">{{ __('Изображение') }}</a></li>
				</ul>
				<form id="editPost" method="POST" action="{{ route('blog.update', $post) }}" class="position-relative" enctype="multipart/form-data">
					@include ('templates.result')
					@method('PUT') 
					@csrf 
					<div class="row">
						<div class="col-md-9">
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane fade show active" id="main">
								<div class="mb-3 row text-muted">
									<label for="" class="col-12 col-lg-3 col-form-label">Заголовок:</label>
									<div class="col-12 col-lg-9">
										<input class="form-control" type="text" name="title" value="{{ $post->title }}" required />
									</div>
								</div>
								<div class="mb-3 row text-muted">
									<label for="" class="col-12 col-lg-3 col-form-label">URL:</label>
									<div class="col-12 col-lg-9">
										<input class="form-control" type="text" name="url" value="{{ $post->url }}" placeholder="URL поста"/>
									</div>
								</div>
								<div class="mb-3 row text-muted">
									<label for="" class="col-12 col-lg-3 col-form-label">{{ __('Дата:') }}</label>
									<div class="col-12 col-lg-9">
										<input class="form-control" type="datetime-local" name="date" step="1" value="{{ date('Y-m-d\TH:i:s', $post->date) }}" />
									</div>
								</div>
								<div class="mb-3 row text-muted">
									<label for="" class="col-12 col-lg-3 col-form-label">{{ __('Автор:') }}</label>
									<div class="col-12 col-lg-9">
										<input class="form-control" type="text" value="{{ $post->author }}" placeholder="Автор" />
									</div>
								</div>
								<div class="mb-3 row text-muted">
									<label for="" class="col-12 col-lg-3 col-form-label">{{ __('Изобажение:') }}</label>
									<div class="col-12 col-lg-9">
										<input class="fileInput form-control" type="file" name="image" accept="image/*" />
										<div class="upImageResult"></div>
									</div>
								</div>
								<div class="mb-3 row text-muted">
									<label for="" class="col-12 col-lg-3 col-form-label">Мета описание:</label>
									<div class="col-12 col-lg-9">
										<textarea class="form-control" name="description" maxlength="250" placeholder="Мета описание:">{{ $post->story->description }}</textarea>
									</div>
								</div>
								<div class="mb-3 row text-muted">
									<label for="" class="col-12 col-lg-3 col-form-label">{{ __('Тип поста:') }}</label>
									<div class="col-12 col-lg-9">
										<select name="type" id="type" class="form-select">
											@foreach($types as $k => $row)
												<option value="{{ $k }}" @selected($post->type == $k)>
													{{ $row }}
												</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="mb-3 row text-muted">
									<label for="" class="col-12 col-lg-3 col-form-label">{{ __('Статус:') }}</label>
									<div class="col-12 col-lg-9">
										<select name="hidden" id="hidden" class="form-select" required>
											@foreach(['Активен', 'Не активен'] as $k => $row)
												<option value="{{ $k }}" @selected($post->hidden == $k)>
													{{ $row }}
												</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane fade" id="content">
								<div class="mb-3 row text-muted">
									<label for="" class="col-12 col-lg-3 col-form-label">{{ __('Короткая версия:') }}</label>
									<div class="col-12 col-lg-9">
										<textarea class="form-control" name="short" placeholder="Короткая версия" required>{{ $post->story->short }}</textarea>
									</div>
								</div>
								<div class="mb-3 row text-muted">
									<label for="" class="col-12 col-lg-3 col-form-label">{{ __('Полная версия:') }}</label>
									<div class="col-12 col-lg-9">
										<textarea class="form-control" name="full" placeholder="Полная версия">{{ $post->story->full }}</textarea>
									</div>
								</div>			
							</div>
							<div role="tabpanel" class="tab-pane fade" id="picture">
								<img class="w-100 mb-3" alt="{{ $post->image }}"
									src="{{ $post->imageUrl }}" 
								/>
							</div>
							<div class="mb-3 row">
								<label for="" class="col-12 col-lg-3 col-form-label">{{ __('Действие:') }}</label>
								<div class="col-12 col-lg-9">
									<button id="submitInput" type="submit" accesskey="s" class="btn btn-primary w-25">
										<i class="fa fa-plus me-1" style="font-size: 14px;"></i>  {{__('Сохранить')}}
									</button>
									<button id="deleteInput" type="submit" accesskey="d" class="btn btn-primary w-25" form="deletePost">
										<i class="fa fa-trash me-1" style="font-size: 14px;"></i> {{__('Удалить')}}
									</button>
								</div>
							</div>
							</div>
						</div>
						<div class="col-md-3 text-center">
							<div class="circleAvatar m-auto mw-100 position-relative mb-3" title="{{ $post->image }}">
								<img id="srcImage" class="w-100 object-fit-cover" alt="{{ $post->image }}"
									src="{{ $post->imageUrl }}" style="height: 160px;"
								/>
								<div class="overlay">
									<a href="{{ $post->imageUrl }}" id="showImage" class="position-absolute text-center" title="{{ $post->title }}">
										<i class="fa fa-search-plus" aria-hidden="true"></i>
									</a>
								</div>
							</div>
							<!-- <input class="form-control" type="file" name="image" /> -->
						</div>
					</div>
					<div class="loader w-100 h-100 top-0 end-0 d-flex position-absolute align-items-center justify-content-center d-none">
						<span class="d-inline-block rounded-circle"></span>
					</div>
				</form>
				<form id="deletePost" method="POST" action="{{ route('blog.destroy', $post) }}">		
					<input type="hidden" name="post" value="{{ $post->id }}">
					@csrf
					@method('DELETE')
				</form>
			</div>
@endsection 
@push('scripts') 
		<!-- <script src="/js/bootstrap-datepicker.min.js"></script> -->
		<!-- <script src="/js/jquery.maskedinput.min.js"></script> -->
		<script src="/js/jquery.validity.js"></script>
		<script src="/js/redactor/redactor.js"></script>
		<script>
			jQuery(function($) {
				// Глобальный обработчик ошибок
				$(document).ajaxError(function(event, xhr, settings, thrownError) {
					if (xhr.status === 419) {
						alert('Сессия истекла. Страница будет перезагружена.');
						location.reload();
					}
				});

				$('#deletePost').submit(function(e) { 
					if (!confirm('Вы уверены, что хотите удалить этот пост? Действие нельзя отменить.')) {
						e.preventDefault();
					}
				});

				$('#editPost').submit(function(e) {
					e.preventDefault();
					let form = $(this)
						, params = new FormData(this)
						, action = form.attr('action')
						, loader = form.find('.loader')
						, result = form.find('.result')
						, submit = form.find('[type="submit"]')
						, errors = []
						, notice = '';

					if (!form.valid()) {
						return false;
					}
					$.post({
						url : action,
						data: params,
						dataType: 'json',
						processData: false, 
        				contentType: false,
						beforeSend: function() {
							loader.toggleClass('d-none');
						}
					}).done(xhr => {
						notice = Template.render('#tplDone', {message: xhr.message});
					}).fail(xhr => {
						errors = xhr.responseJSON.errors
							? Object.values(xhr.responseJSON.errors).flat()
							: [xhr.responseJSON.message];
						notice = Template.render('#tplFail', {errors: errors});
					}).always(xhr => {
						result.html(notice);
						loader.toggleClass('d-none');
					});
				}).validity();

				$('[name="full"]').redactor ({
					fileUpload:  'files.upload.php',
					imageUpload: 'image.upload.php',
					imageGetJson:'/demo/json/data.json'
				});

				$('#showImage').click(showImage);

				$('.fileInput').change(function() {
					uploadImage(this.files[0], this, 5);
				});
			});

			document.addEventListener('DOMContentLoaded', () => {
				document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
					tab.addEventListener('shown.bs.tab', function() {
						autosize.update($('textarea'));
					});
				});	
			});

			// .fail( (xhr)  => {
			// Безопасная обработка ошибок
			// const responseJSON = xhr.responseJSON;
			// if (responseJSON && responseJSON.errors) {
			//     errors = Object.values(responseJSON.errors).flat(); // Валидация (422)
			// } else if (responseJSON && responseJSON.message) {
			//     // Другая ошибка с сообщением
			//     errors = [responseJSON.message];
			// } else if (xhr.status === 419) {
			//     // CSRF token истёк
			//     errors = ['Страница устарела. Обновите страницу и попробуйте снова.'];
			// } else if (xhr.status === 404) {
			//     errors = ['Страница не найдена.'];
			// } else if (xhr.status === 500) {
			//     errors = ['Внутренняя ошибка сервера. Попробуйте позже.'];
			// } else {
			//     // fallback: пытаемся прочитать текст ошибки
			//     errors = [xhr.responseText?.substring(0, 200) || `Ошибка ${xhr.status}: ${xhr.statusText}`];
			// }
			// html = Template.render('#tplFail', { errors: errors });
			// result.html(html);
			// Для отладки: выводим в консоль
			// console.warn('AJAX Error:', {
			//     status: xhr.status,
			//     statusText: xhr.statusText,
			//     responseJSON: responseJSON,
			//     responseText: xhr.responseText?.substring(0, 500)
			// });
		</script>
@endpush
