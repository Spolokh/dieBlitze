@extends('layouts.app') 
@section('title', 'Комментарий ID: ' . $comment->id) 
@section('content') 
			<div class="breadcrumbs text-muted my-4">
				<h5><a href="{{ route('comments.index') }}" class="text-decoration-none">Комментарии</a> 
				<i class="fa fa-angle-right mx-1" aria-hidden="true"></i> @yield('title')</h5>
			</div>
			<div class="my-3 p-3 bg-body rounded shadow-sm">
				<h6 class="border-bottom pb-3 mb-3">Редактировать</h6>
				<div class="row">
					<div class="col-md-9">
						<form id="updateComment" action="{{ route('comments.update', $comment) }}" method="POST" class="position-relative">
							@include ('templates.result')
							<div class="mb-3 row text-muted">
								<label for="" class="col-12 col-lg-3 col-form-label">Автор:</label>
								<div class="col-12 col-lg-9">
									<input class="form-control" value="{{ $comment->user?->name ?? $comment->author }}" disabled />
								</div>
							</div>
							<div class="mb-3 row text-muted">
								<label for="" class="col-12 col-lg-3 col-form-label">Дата:</label>
								<div class="col-12 col-lg-9">
									<input class="form-control" value="{{ $comment->dateFormatted }}" disabled />
								</div>
							</div>
							<div class="mb-3 row text-muted">
								<label for="" class="col-12 col-lg-3 col-form-label">IP:</label>
								<div class="col-12 col-lg-9">
									<input class="form-control" value="{{ $comment->ip }}" disabled />
								</div>
							</div>
							<div class="mb-3 row text-muted">
								<label for="" class="col-12 col-lg-3 col-form-label">Email:</label>
								<div class="col-12 col-lg-9">
									<input type="email" class="form-control" name="mail" value="{{ $comment->mail }}" placeholder="Email" />
								</div>
							</div>
							<div class="mb-3 row text-muted">
								<label for="" class="col-12 col-lg-3 col-form-label">Комментарий:</label>
								<div class="col-12 col-lg-9">
									<textarea class="form-control" name="comment" required>{!! Str::replace('<br />', "\n", $comment->comment) !!}</textarea>
								</div>
							</div>
							<div class="mb-3 row text-muted">
								<label for="" class="col-12 col-lg-3 col-form-label">Ответ:</label>
								<div class="col-12 col-lg-9">
									<textarea class="form-control" name="reply" placeholder="Ответ">{{ $comment->reply }}</textarea>
								</div>
							</div>
							<div class="mb-3 row text-muted">
								<label for="posType" class="col-12 col-lg-3 col-form-label">Тип поста:</label>
								<div class="col-12 col-lg-9">
									<select 
										name="type" 
										id="posType" 
										class="form-select" 
										required
									>
										@foreach($types as $value => $label)
											<option value="{{ $value }}" @selected(old('type', $comment->type) == $value)>
												{{ $label }}
											</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="mb-3 row text-muted">
								<label for="" class="col-12 col-lg-3 col-form-label">Видимость:</label>
								<div class="col-12 col-lg-9">
									<select name="hidden" id="hidden" class="form-select" required>
										<option value="">Выбрать</option>
										<option value="0" @selected(old('hidden', $comment->hidden) == 0)>
											Активен
										</option>
										<option value="1" @selected(old('hidden', $comment->hidden) == 1)>
											Не активен
										</option>
									</select>
								</div>
							</div>

							<div class="mb-3 row">
								<label for="" class="col-12 col-lg-3 col-form-label"> </label>
								<div class="col-12 col-lg-9">
									<button id="subInput" accesskey="s" type="submit" class="btn btn-primary w-25">Обновить</button>
									<button id="delInput" accesskey="d" type="submit" class="btn btn-primary w-25" form="deleteComment">Удалить</button>
								</div>
							</div>
							<div class="loader w-100 h-100 top-0 end-0 d-flex align-items-center justify-content-center position-absolute d-none">
								<span class="d-inline-block rounded-circle"></span>
							</div>
							@method('PUT') 
                        	@csrf 
						</form>
					</div>
					<div class="col-md-3 text-center">
						<div class="circleAvatar m-auto w-100 position-relative" title="{{ $comment->author }}">
							<img alt="{{ $comment->author }}" class="object-fit-cover rounded-circle" 
								 src="{{ $comment->AvatarUrl }}" 
							/>
						</div>
					</div>
				</div>
				<form id="deleteComment" method="POST" action="{{ route('comments.destroy', $comment) }}">
					<input type="hidden" name="comment" value="{{ $comment->id }}">
					{{-- Кнопка удаления (добавьте по необходимости) --}}
					@csrf
					@method('DELETE')
				</form>
			</div>
@endsection 
@push('scripts') 
		<script src="/js/jquery.validity.js"></script>
		<script>
			jQuery(function($) {
				$('#deleteComment').submit(function(e) { 
					if (!confirm('⚠️ Вы уверены? Это действие нельзя отменить.')) {
						e.preventDefault();
					}
				});
				 
				$('#updateComment').submit(function(e) {
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
					if (!form.valid()) {
						return false;
					}

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
				}).validity();
			});
		</script>
@endpush
