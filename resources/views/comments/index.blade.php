@extends('layouts.app')
@section('title', 'Комментарии') 
@section('content') 
			<div class="breadcrumbs text-muted my-4">
				<h5>Комментарии (<span>{{$count}}</span>)</h5>
			</div>
			<div id="container" class="my-3 p-3 bg-body rounded shadow-sm position-relative">
				<form id="searchComment" action="{{ route('comments.index') }}" class="row g-3 mb-3" role="search">
					{{-- Автор --}}
					<div class="col-md-6 col-lg-2">
						<label for="author" class="visually-hidden">Автор</label>
						<select name="author" id="author" class="form-select form-select-sm">
							@foreach($users as $id => $username)
								<option value="{{ $id }}" @selected(old('author') == $id)>
									{{ $username }}
								</option>
							@endforeach
						</select>
					</div>
					{{-- Тип поста --}}
					<div class="col-md-6 col-lg-2">
						<label for="type" class="visually-hidden">Тип</label>
						<select name="type" id="type" class="form-select form-select-sm">
							@foreach($types as $id => $label)
								<option value="{{ $id }}" @selected(old('type') == $id)>
									{{ $label }}
								</option>
							@endforeach
						</select>
					</div>

					{{-- Поиск по IP --}}
					<div class="col-md-6 col-lg-2">
						<label for="ip" class="visually-hidden">IP</label>
						<input 
							type="search" 
							name="ip" 
							id="ip" 
							class="form-control form-control-sm" 
							placeholder="IP"
							value="{{ old('ip') }}"
							pattern="^(\d{1,3}\.){3}\d{1,3}$"
							title="Введите корректный IPv4 адрес"
						>
					</div>
					{{-- Поиск по тексту --}}
					<div class="col-md-6 col-lg-2">
						<label for="search" class="visually-hidden">Поиск</label>
						<input 
							type="search" 
							name="search" 
							id="search" 
							class="form-control form-control-sm" 
							placeholder="Текст"
							value="{{ old('search') }}"
							maxlength="255"
						>
					</div>
					{{-- Кнопка сброса --}}
					<div class="col-sm-6 col-lg-2">
						<button type="reset" accesskey="r" class="w-100 btn btn-sm btn-outline-secondary">
							<i class="fa fa-undo"></i> Сбросить
						</button>
					</div>
					{{-- Кнопка поиска --}}
					<div class="col-sm-6 col-lg-2">
						<button type="submit" accesskey="s" class="w-100 btn btn-sm btn-primary">
							<i class="fa fa-search"></i> Найти
						</button>
						<input type="hidden" name="count" id="count" value="{{ $count }}">
					</div>
				</form>
				 
				<form id="listComment" method="POST" action="{{ route('comment.edit') }}" class="mb-3">
					@csrf
					@include ('templates.result') 	 
					<table id="dataTable" class="table table-striped table-hover table-sm list">
						<thead>
							<tr>
								<th class="text-muted" width="60">#ID</th>
								<th class="text-muted" width="140">Дата создания</th>
								<th class="text-muted" width="">Автор</th>
								<th class="text-muted" width="45%">Комментарий</th>
								<th class="text-muted" width="100">IP</th>
								<th class="text-muted text-start" width="110">Тип поста</th>
								<th class="text-muted" width="40">
									<input type="checkbox" id="checkAll"/>
									<label for="checkAll"></label>
								</th>
							</tr>
						</thead>
						<tbody>
					@forelse ($query as $row) 
						<tr>
							<td>{{ $row->id }}</td>
							<td>{{ date('d.m.Y H:i', $row->date) }}</td>
							<td>
								<figure class="d-inline-block me-2">
									<img src="{{ $row->AvatarUrl }}" class="lazy rounded-circle object-fit-cover" width="24" alt="{{ $row->author }}"/>
								</figure>
								{!! $row->user?->name ?? stripslashes($row->author) !!}
							</td>
								<td>
									@can('update', $row)
									<a href="{{ route('comments.edit', $row->id) }}"  data-form="editComment" data-bs-toggle="modal" data-bs-target="#myModal" 	
										title="Комментарий ID: {{ $row->id }} {{ $row->dateFormatted }}" class="me-1 text-decoration-none">
										<i class="fa fa-pencil fa-fw"></i>
									</a>
									@endcan
									<a title="{{ stripslashes($row->postTitle) }}" data-bs-toggle="tooltip" class="text-decoration-none" href="{{ route('comments.edit', $row->id) }}">
										{!! $row->hidden ? '<s>' : '' !!}{!! $row->excerpt !!}{!! $row->hidden ? '</s>' : '' !!}
									</a>
								</td>
								<td>
									<a title="Комментарий ID: {{ $row->id }} ({{ $row->ip }})" href="{{ route('api.geoip', ['ip' => $row->ip]) }}" data-bs-toggle="modal" data-bs-target="#myModal" class="text-decoration-none">
										{{ $row->ip }}
									</a>
								</td>
								<td title="{{ $row->type }}"><i class="me-1 fa fa-fw {{ $row->hidden ? 'fa-remove text-danger' : 'fa-check text-success' }}"></i>
									{{ $types[$row->type] ?? '--' }}
								</td>
								<td>
									<input class="checkItem" id="row{{ $row->id }}" name="items[]" type="checkbox" value="{{ $row->id }}"/>
									<label for="row{{ $row->id }}"></label>
								</td>
							</tr>
					@empty 
							<tr>
								<td class="py-3 border-0 bg-white" colspan="8" style="box-shadow: none;">
									<div class=" alert alert-danger">	
										<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
										Комментарии не найдены.
									</div>
								</td>
							</tr>
					@endforelse 
						</tbody>
					</table>
					<div class="row my-3">
					    <div class="col-8">
						@if ($count > 0) 
							{{ $query->withQueryString()->links() }} 
						@endif
						</div>
					    <div class="col-4">
							<div class="input-group">
								{{-- Выбор действия --}}
								<select name="action" id="actions" class="form-select" disabled>
									<option value="">Выбрать действие</option>
									<option value="hidden">Скрыть комментарии</option>
									<option value="delete">Удалить комментарии</option>
								</select>
								{{-- Кнопка применения --}}
								<button id="EditBtn" type="submit" class="btn btn-primary" disabled>
									{{__('Редактировать')}}
								</button>
							</div>
						</div>
					</div>
					<div class="loader w-100 h-100 top-0 end-0 d-flex position-absolute align-items-center justify-content-center d-none">
						<div class="form-loading"></div>
					</div>
				</form>
			</div>
@endsection
@push('scripts')
<script src="/js/jquery.validity.js"></script>
		<script>
			jQuery(function($) {
				const route = '{{ route("comments.index") }}';
				$('#listComment').on('submit', function(e) {
					e.preventDefault();
					let form   = $(this),
						params = form.serialize(), 
						action = form.attr('action'),
						loader = form.find('.loader'),
						result = form.find('.result'),
						errors = [],
						notice = '';
					loader.toggleClass('d-none');
					$.post(action, params).done(function(xhr) {
						notice = Template.render('#tplDone', {message: xhr.message});
						reloadContainer(window.location.href, '#dataTable');
					}).fail(function(xhr) {
						errors = (xhr.responseJSON.errors)
							? Object.values(xhr.responseJSON.errors).flat()
							: [xhr.responseJSON.message];
						notice = Template.render('#tplFail', {errors: errors});
					}).always(function(xhr) {
						result.html(notice);
						loader.toggleClass('d-none');
						if (xhr.success == true) {
							form[0].reset();
						}
        			});
				});

				$(document).on('submit', '#editComment', function(e) {
					e.preventDefault();
					let form   = $(this),
						params = form.serialize(), 
						action = form.attr('action'),
						loader = form.find('.loader'),
						result = form.find('.result'),
						errors = [],
						closed = 1000;

					loader.toggleClass('d-none');
					$.post(action, params).done(function(data) {
						reloadContainer(window.location.href, '#listComment');
						html = Template.render('#tplDone', {message: data.message});
						setTimeout(() => {
							$('#myModal').modal('hide');
						}, closed);
					}).fail(function(data) {
						errors = (data.responseJSON.errors)
							? Object.values(data.responseJSON.errors).flat()
							: [data.responseJSON.message];
						const html = Template.render('#tplFail', {errors: errors});	 
					}).always(function(data) {
						result.html(html);
						loader.toggleClass('d-none'); 
        			});
				});

				$(document).on('submit', '#searchComment', function(e) {
					e.preventDefault();
					let form  = $(this),
						params = form.serialize(),
						action = form.attr('action'),
						loader = $('#listComment').find('.loader'),
						content = '';
					loader.toggleClass('d-none');
				
					$.get(action, params)
						.done(function(data) {
							content = $(data).find('#listComment').html();
							$('#listComment').html(content);
					})
						.fail(function() {
							alert('Ошибка загрузки страницы');	 
					})
						.always(function() {
							loader.toggleClass('d-none');
							const Url = action + '?' + params;
							const Count = $('#count').val() || '0';
							$('.breadcrumbs').find('span').text(Count);
							history.pushState({path: Url}, '', Url);
						});
				}).on('reset', '#searchComment', function(e) {
					reloadContainer(window.location.pathname);
				});

				$(document).on('click', 'ul.pagination a.page-link', function(e) {
					e.preventDefault();
					let link    = $(this).attr('href'),
						loader  = $('#listComment').find('.loader'),
						content = '';
					$.get(link, function(data) {
						reloadContainer(link, '#listComment');
					}).fail(function() {
						alert('Ошибка загрузки страницы');	 
					}).always(function() {
						$(window).scrollTop(60);
						history.pushState({ path: link }, '', link);
        			});
				});

				function reloadContainer(url, container, callback = null) {
					if (!container) {
						container = '#container';
					}
					let loader = $(container).find('.loader');
						loader.toggleClass('d-none');

					$.get(url).done(function(data) {
						let $template = $('<div>').append($.parseHTML(data));
                        	$template = $template.find(container).html();
						if ($template) {
							$(container).html($template);
						}
						if (typeof callback === 'function') {
							setTimeout(() => {
								callback();
							}, 50);
						}
					}).fail(function() {
						alert('Не удалось обновить список комментариев');
					}).always(function() {
						loader.toggleClass('d-none');
					});
				}
				
				$(document).on('change', '#checkAll', function() {
					let Checked = $(this).prop('checked');
					$('#dataTable')
						.find('.checkItem')
						.prop('checked', Checked);
				});

				$(document).on('change', '.checkItem, #checkAll', function() {
					$('#EditBtn, #actions').prop('disabled', $('.checkItem:checked').length === 0);
				});
			});
		</script>		
@endpush 
