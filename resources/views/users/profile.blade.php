@extends('layouts.app')
@section('title', 'Личные настройки') 
@section('styles') 
	<link rel="stylesheet" href="{{ asset('css/cropper/cropper.min.css') }}">
	<style>
	.flag-icon {
		width: 20px;
		height: auto;
		margin: 2px;
		vertical-align: middle;
	}
	</style>  
@endsection 
@section('content') 
			<div class="breadcrumbs text-muted my-4">
				<h5>Личные настройки</h5>
			</div>
			<div class="mb-3 p-3 bg-body rounded shadow-sm">
				<ul class="nav nav-tabs mb-4">
					<li><a class="nav-link active" href="#main" class="active" data-bs-toggle="tab">Основное</a></li>
					<li><a class="nav-link" href="#upload" data-bs-toggle="tab">Аватар</a></li>
					<li><a class="nav-link" href="#setting" data-bs-toggle="tab">Настройки</a></li>
				</ul>
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane fade show active" id="main">
						<div class="row">
							<div class="col-12 col-lg-9">  
								<form id="editProfile" action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="position-relative">
									@include ('templates.result') 
									<div class="mb-3 row text-muted">
										<label for="" class="col-12 col-lg-3 col-form-label">Логин:</label>
										<div class="col-12 col-lg-9">
											<input type="text" class="form-control" value="{{ $user->username }}" @readonly($user->isNotAdmin()) />
										</div>
									</div>
									<div class="mb-3 row text-muted">
										<label for="" class="col-12 col-lg-3 col-form-label">Дата регистации:</label>
										<div class="col-12 col-lg-9">
											<input type="text" class="form-control" value="{{ $user->dateFormatted }}" @readonly($user->isNotAdmin()) />
										</div>
									</div>
									<div class="mb-3 row text-muted">
										<label for="" class="col-12 col-lg-3 col-form-label">Группа:</label>
										<div class="col-12 col-lg-9">
											<input type="text" class="form-control" value="{{ $group }}" readonly />
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
											<input type="email" class="form-control" name="mail" value="{{ $user->mail }}" placeholder="Email" required />
										</div>
									</div>
									<div class="mb-3 row text-muted">
										<label for="" class="col-12 col-lg-3 col-form-label">Телефон:</label>
										<div class="col-12 col-lg-9">
											<input type="tel" class="form-control" name="phone" value="{{ $user->phone }}" placeholder="Ваш телефон" />
										</div>
									</div>
									<div class="mb-3 row text-muted">
									      
										<label for="" class="col-12 col-lg-3 col-form-label">Дата рождения:</label>
										<div class="col-12 col-lg-9">
											<input type="date" class="form-control" name="birthdate" value="{{ $user->birthdate }}" placeholder="Дата рождения" />
										</div>
									</div>
									<div class="mb-3 row text-muted">
										<label for="" class="col-lg-3 col-form-label">Откуда Вы:</label>
										<div class="col-lg-9">
											<select id="Countries" title="Выбрать страну" class="form-control selectpicker" data-live-search="true" placeholder="Выбрать">
												<option value="at" data-content="<img src='/img/flags/at.png' class='flag-icon'> Австрия">Австрия</option>
												<option value="au" data-content="<img src='/img/flags/au.png' class='flag-icon'> Australia"></option>
												<option value="bg" data-content="<img src='/img/flags/bg.png' class='flag-icon'> Bulgaria"></option>
												<option value="de" data-content="<img src='/img/flags/de.png' class='flag-icon'> Germany" selected>Germany</option>
												<option value="ee" data-image="/img/flags/ee.png">Estonia</option>
												<option value="fr" data-image="/img/flags/fr.png">France</option>
												<option value="gb" data-image="/img/flags/gb.png">Великобритания</option>
												<option value="ge" data-image="/img/flags/ge.png">Georgia</option>
												<option value="gr" data-image="/img/flags/gr.png">Greece</option>
												<option value="cr" data-image="/img/flags/cr.png">Croatia</option>
												<option value="hu" data-image="/img/flags/hu.png">Hungary</option>
												<option value="ie" data-image="/img/flags/ie.png">Ireland</option>
												<option value="is" data-image="/img/flags/is.png">Iceland</option>
												<option value="it" data-image="/img/flags/it.png">Italy</option>
											</select>
										</div>
									</div>
									<!--div class="mb-3 row text-muted">
										<label for="" class="col-lg-3 col-form-label">Загрузить аватар</label>
										<div class="col-lg-9">
											<input type="file" class="form-control upImage" name="avatar" accept="image/jpeg, image/png, image/gif"/>
											<div class="upImageResult"></div>
										</div>
									</div-->
									<div class="mb-3 row text-muted">
										<label for="" class="col-12 col-lg-3 col-form-label">Пароль:</label>
										<div class="col-12 col-lg-9">
											<input type="text" class="form-control" name="password" placeholder="Изменить пароль" />
										</div>
									</div>
									<div class="mb-3 row text-muted">
										<label for="" class="col-12 col-lg-3 col-form-label">Повторить:</label>
										<div class="col-12 col-lg-9">
											<input type="text" class="form-control" name="password_confirmation" placeholder="Повторить пароль" />
										</div>
									</div>
									<div class="mb-3 row text-muted">
										<label for="" class="col-lg-3 col-form-label">О себе:</label>
										<div class="col-lg-9">
											<textarea class="form-control" name="about" placeholder="О себе">{{$user->about}}</textarea>
										</div>
									</div>
									<div class="mb-3 row">
										<label for="" class="col-12 col-lg-3 col-form-label"> </label>
										<div class="col-12 col-lg-9">
											@method('PUT') 
											@csrf 
											<button type="submit" accesskey="s" class="btn btn-primary w-25">
												Сохранить
											</button>
										</div>
									</div>
									<div class="loader w-100 h-100 top-0 end-0 d-flex position-absolute align-items-center justify-content-center d-none">
										<div class="d-inline-block rounded-circle"></div>
									</div>
								</form>
							</div>
							<div class="col-12 col-lg-3 text-center">
								<div class="circleAvatar w-100 m-auto position-relative">
									<img alt="{{ $user->name ?: $user->username }}" class="rounded-circle" 
										src="{{ $user->avatar ? asset('uploads/userpics/thumbs/'.$user->username.'.jpg?'.time()) : '/img/camera.png' }}" 
									/>
									<div class="overlay">
										<a class="srcImage text position-absolute text-center" id="" title="{{ $user->name ?: $user->username }}" href="{{asset('uploads/userpics/'.$user->username.'.jpg?'.time())}}">
											<i class="fa fa-search-plus" aria-hidden="true"></i>
										</a>
									</div>
								</div>
								<a href="/image" role="button" title="Загрузить аватар" class="btn btn-primary my-4 w-75">
									<i class="fa fa-upload" aria-hidden="true"></i>
									Загрузить аватар
								</a>
								<button type="button" role="switch" data-off="VDI" data-on="RDSH" 
									class="border switch-btn switch-off">VDI
								</button>
							</div>
						</div>
					</div>

					<div role="tabpanel" class="tab-pane fade" id="upload">
						<form id="avatarForm" action="{{ route('image.update') }}" method="post" enctype="multipart/form-data">
                    		@include ('templates.result')
							<div class="row">
								<div class="col-md-9 img-container">
									<img id="imgPreview"
										src="{{  $user->avatar ? asset('uploads/userpics/'.$user->username.'.jpg?'.time()) : '/img/camera.png' }}"  alt=""
									/>
								</div>
								<div class="col-md-3">
									<div class="imgPreview img-preview-custom rounded-circle">
										<img src="/img/avatar.jpg" />
									</div>
									<input id="inputImage" type="file" class="form-control mb-3" name="avatar" accept="image/jpg, image/jpeg, image/png, image/gif"/>
									<div class="input-group mb-3">
										<label class="input-group-text" for="dataWidth">Ширина:</label>
										<input type="number" class="form-control" id="dataWidth" name="width" placeholder="width"/>
										<span class="input-group-text">.px</span>
									</div>
									<div class="input-group mb-3">
										<label class="input-group-text" for="dataHeight">Высота:</label>
										<input type="number" class="form-control" id="dataHeight" name="height" placeholder="height"/>
										<span class="input-group-text">.px</span>
									</div>
									<div class="input-group mb-3">
										<label class="input-group-text" for="dataX">X:</label>
										<input type="number" class="form-control" id="dataX" name="x" placeholder="x"/>
										<span class="input-group-text">.px</span>
									</div>
									<div class="input-group mb-3">
										<label class="input-group-text" for="dataY">Y:</label>
										<input type="number" class="form-control" id="dataY" name="y" placeholder="y"/>
										<span class="input-group-text">.px</span>
									</div>
									<div class="text-start mb-3">
										<input type="checkbox" id="cropping" name="cropping" value="1" checked />
										<label for="cropping"></label>
										Кадрировать аватар ?
									</div>
									@csrf 
                            		<button type="submit" class="btn btn-primary mb-3 w-100">Загрузить аватар</button>
								</div>
							</div>
							<div class="loader w-100 h-100 position-absolute d-none">
								<div class="d-table-cell align-middle text-center">
									<span></span>
								</div>
							</div>
						</form>
					</div>
				</div><!--//tabsContent-->
			</div>
@endsection
@push('scripts') 
		<script src="/js/jquery.bootstrap-select.js"></script>
		<script src="/js/jquery.maskedinput.min.js"></script>
		<script src="/js/cropper/cropper.js"></script>
		<script src="/js/jquery.validity.js"></script>
		<script>
			jQuery(function($) {

				// $('#Countries option').each(function() {
				// 	var $option = $(this);
				// 	var image = $option.data('image');
				// 	var text  = $option.text();
					
				// 	if (image) {
				// 		$option.attr('data-content', '<img src="' + image + '">');
				// 	}
				// });
				// data-content="<img src='/img/flags/at.png' class='flag-icon'>
    
				// Инициализируем selectpicker
				$('#Countries').selectpicker();
				$('#editProfile').validity().submit(function(e) {
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
					})
					.fail(function(xhr) {
						errors = (xhr.responseJSON.errors)
							? Object.values(xhr.responseJSON.errors).map(error => error[0])
							: [xhr.responseJSON.message];
						html = Template.render('#tplFail', {errors: errors});
					})
					.always(function(xhr) {
						$(this).html(html); // console.log(html);
						loader.toggleClass('d-none'); 
					});
				});

				var $image = $('#imgPreview'),
                    $input = $('#inputImage'),
                    $dataX = $('#dataX'),
                    $dataY = $('#dataY'),
                    $dataWidth  = $('#dataWidth'),
                    $dataHeight = $('#dataHeight'),
                    options = {
                        viewMode: 1,
                        dragMode: 'move',
                        aspectRatio: 1,
                        preview: '.imgPreview',
						minContainerWidth : 200,
  						minContainerHeight: 200,
                        crop: function(e) {
                            $dataX.val(Math.round(data.x));
                            $dataY.val(Math.round(data.y));
                            $dataWidth.val(Math.round(data.width));
                            $dataHeight.val(Math.round(data.height));
                        }
                    };

				// Инициализировать Cropper, когда вкладка "Аватар" становится активной
				document.querySelectorAll('[href="#upload"]').forEach(tab => {
					tab.addEventListener('shown.bs.tab', function() { // Небольшая задержка, чтобы DOM точно обновился (иногда нужно)
						setTimeout(() => {
							$image.cropper(options);
						}, 50);
					});
				});
				
				$input.change(function() {
                    var Reader = new FileReader(),
                        Accept = this.accept,
                        files  = this.files,
                        files  = files[0];
                    if(!files) {
                        return;
                    }
                    if(!Accept.includes(files.type)) {
                        alert ('Ваш файл не является изображением!');
                        return;
                    }

					Reader.onload = function(e) { // уничтожаем старый экземпляр
						$image.cropper('destroy').attr('src', e.target.result).cropper(options);
					};
                    Reader.readAsDataURL(files);
                });

				$('button[role="switch"]').click(function(e) {
					e.preventDefault();
					let $this = $(this);
						$this.toggleClass('switch-on').toggleClass('switch-off');
					($this.hasClass('switch-on'))
						? $this.text($this.data('on'))
						: $this.text($this.data('off'));
				});

				$('.upImage').change(function () {
					uploadImage(this.files[0], this, 5);
				});

				$('.srcImage').click(showImage);
			});
		</script>
@endpush