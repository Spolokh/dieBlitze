@extends('layouts.app')
@section('styles') 
		<link rel="stylesheet" href="/css/redactor/redactor.css" />
@endsection 
@section('title', 'Добавить пост') 
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
				<form id="createPost" method="POST" action="{{route('blog.store')}}" class="position-relative" enctype="multipart/form-data">
					@include ('templates.result')
					@csrf 
					<div class="row">
						<div class="col-md-9">
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane fade show active" id="main">
								<div class="mb-3 row text-muted">
									<label for="" class="col-12 col-lg-3 col-form-label">Заголовок:</label>
									<div class="col-12 col-lg-9">
										<input type="text" name="title" class="form-control" placeholder="Заголовок поста" required/>
									</div>
								</div>
								<div class="mb-3 row text-muted">
									<label for="" class="col-12 col-lg-3 col-form-label">URL:</label>
									<div class="col-12 col-lg-9">
										<input type="text" name="url" class="form-control" placeholder="URL поста"/>
									</div>
								</div>
								<div class="mb-3 row text-muted">
									<label for="" class="col-12 col-lg-3 col-form-label">{{ __('Изобажение') }}:</label>
									<div class="col-12 col-lg-9">
										<input type="file" name="image" class="form-control fileInput" accept="image/*" />
										<div class="upImageResult"></div>
									</div>
								</div>
								<div class="mb-3 row text-muted">
									<label for="" class="col-12 col-lg-3 col-form-label">{{ __('Мета описание:') }}</label>
									<div class="col-12 col-lg-9">
										<textarea class="form-control" name="description" maxlength="250" placeholder="Мета описание (макс. 500 символов)"></textarea>
									</div>
								</div>
								<div class="mb-3 row text-muted">
									<label for="" class="col-12 col-lg-3 col-form-label">{{ __('Тип поста:') }}</label>
									<div class="col-12 col-lg-9">
										<select name="type" id="type" class="form-select">
										@foreach($types as $k => $row)
											<option value="{{ $k }}" @selected(old('type') == $k)>
												{{ $row }}
											</option>
										@endforeach
										</select>
									</div>
								</div>
								<div class="mb-3 row text-muted">
									<label for="" class="col-12 col-lg-3 col-form-label">{{ __('Статус:') }}</label>
									<div class="col-12 col-lg-9">
										<select name="hidden" id="hidden" class="form-select">
											@foreach(['Активен', 'Не активен'] as $k => $row)
												<option value="{{ $k }}" @selected(old('type') == $k)>
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
										<textarea class="form-control" name="short" placeholder="Короткая версия" required></textarea>
									</div>
								</div>
								<div class="mb-3 row text-muted">
									<label for="" class="col-12 col-lg-3 col-form-label">{{ __('Полная версия:') }}</label>
									<div class="col-12 col-lg-9">
										<textarea class="form-control" name="full" placeholder="Полная версия"></textarea>
									</div>
								</div>			
							</div>
							<div role="tabpanel" class="tab-pane fade" id="picture">
								<img class="w-100 mb-3" alt="/img/404.jpg" src="/img/404.jpg" />
							</div>
							<div class="mb-3 row">
								<label for="" class="col-12 col-lg-3 col-form-label">{{ __('Действие:') }}</label>
								<div class="col-12 col-lg-9">
									<button id="submitInput" type="submit" accesskey="s" class="btn btn-primary w-25">
										{{__('Сохранить')}}
									</button>
									<button id="resetInput" type="reset" accesskey="d" class="btn btn-primary w-25">
										{{__('Очистить')}}
									</button>
								</div>
							</div>
							</div>
						</div>
						<div class="col-md-3 text-center">
							<div class="circleAvatar m-auto border mw-100 position-relative mb-3" title="/img/404.jpg">
								<img id="srcImage" src="/img/404.jpg" class="object-fit-cover" alt="/img/404.jpg"/>
							</div>
						</div>
					</div>
					<div class="loader w-100 h-100 top-0 end-0 d-flex position-absolute align-items-center justify-content-center d-none">
						<span class="d-inline-block rounded-circle"></span>
					</div>
				</form>
			</div>
@endsection 
@push('scripts') 
		<script src="/js/jquery.validity.js"></script>
		<script src="/js/redactor/redactor.js"></script>
		<script>
			jQuery(function($) {
				$('[name="full"]').redactor({
					fileUpload : '/files.upload.php',
					imageUpload: '/image.upload.php',
					imageGetJson: '/images.json'
				});

				$('#createPost').submit(function(e) {
					let $form = $(this)
					if (!$form.valid()) {
						e.preventDefault();
						return false;
					}
				}).validity();

				$('#createPost').find('[name="image"]').change(function() {
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
		</script>
@endpush
