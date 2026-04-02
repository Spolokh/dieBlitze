
@extends('layouts.app') 
@section('title', 'Пользователи')
@section('styles')
<link rel="stylesheet" href="/css/dataTables.bootstrap5.min.css" />
@endsection
@section('content') 
			<div class="breadcrumbs text-muted my-4">
				<h5>Пользователи</h5>
			</div>
			<div class="my-3 p-3 bg-body rounded shadow-sm">
				<form id="editUsers" method="POST">
					@include ('templates.result') 
					<table id="dataTable" class="table table-striped table-hover table-sm w-100 my-2 list">
						<thead>
							<tr>
								<th class="text-muted" width="40">#ID</th>
								<th class="text-muted" width="auto">Имя</th>
								<th class="text-muted" width="25%">E-mail</th>
								<th class="text-muted" width="150">Группа</th>
								<th class="text-muted" width="150">Дата регистрации</th>
								<th class="text-muted" width="35">Фото</th>
								<th class="text-muted" width="35">
									<input type="checkbox" id="checkAll" @guest disabled @endguest />
									<label for="checkAll"></label>
								</th>
							</tr>
						</thead>
					</table>
					@isAdmin 
					<div class="row mt-3">
						<div class="col-6 text-start">
							<a role="button" class="btn btn-primary btn-sm" id="addUser" href="{{ route('users.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Добавить</a>
							<a role="button" class="btn btn-primary btn-sm" id="Download"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Скачать</a>
						</div>
						<div class="col-6 text-end">
							<a role="button" class="btn btn-primary btn-sm" id="Reload"><i class="fa fa-refresh" aria-hidden="true"></i> Обновить</a>
							<button type="submit" class="btn btn-primary btn-sm" id="Delete" disabled><i class="fa fa-trash" aria-hidden="true"></i> Удалить</button>
						</div>
					</div>
					@csrf 
					@method('DELETE') 
					@endisAdmin
				</form>
			</div>
@endsection
@push('scripts') 
		<script src="/js/dataTables.min.js"></script>
		<!-- <script src="/js/moment/moment.js"></script> -->
		<script src="/js/xlsx.min.js"></script>
		<script>
			jQuery(function($) {

				const optionsGroups = @json($groups);
				const optionsActive = [
					'Активные',
					'Удалёные'
				];

				$("#checkAll").change(function () {
					let Deleted = $('#Delete'),
						Checked = $(this).prop('checked');
					$('#editUsers')
						.find('.checkItem')
						.prop('checked', Checked);
					(Checked) 
						? Deleted.attr('disabled', null) 
						: Deleted.attr('disabled', true);
				});
				$(document).on('change', '.checkItem', function() {
					$('#Delete').prop('disabled', $('.checkItem:checked').length === 0);
				});
				var route = "{{ route('users.json') }}",
					table = $('#dataTable').DataTable({
						ordering: true
						, autoWidth: false
						, searching : true
						, processing: true
						, serverSide: true
						, pageLength: 10
						, lengthMenu: [10, 25, 50, 100]
						, ajax: {
							url : route,
							type: 'GET',
							error: function(){

							}
						}
						, language: {
							sUrl: "/js/i18n/ru.json"
						}
						, columns: [{
								data: 'id', orderable: true
							},  {
								data: 'name', orderable: true, render: function(data, type, row) {
									return '<a href="/users/'+row.id+'/edit" class="text-decoration-none">'+row.name+'</a>';
							}}, {	
								data: 'mail',  orderable: true
							},  { 
								data: 'usergroup', orderable: true 
							}, {
								data: 'date',  orderable: true,  render: function(data, type, row) {
									return formatUnixTimestamp(data);
							}},  {
								data: 'avatar', orderable: false, render: function(data, type, row) {
									return '<figure><img loading="lazy" src="'+row.avatar+'?'+Date.now()+'" class="user-avatar rounded-circle" alt="'+row.name+'"/></figure>';
							}}, {
								data: 'checked', orderable: false, render: function (data, type, row) {
									return '<input id="item' +row.id+ '" type="checkbox" class="checkItem" name="item[]" value="'+row.id+'" @guest disabled @endauth/>'
										+ '<label for="item'+row.id+'"></label>';
							}
						}]
						, initComplete: function() {
							let selectActive = $('<select/>', {id: 'Active', class: 'form-select form-select-sm d-inline w-auto'})
								.css('margin-left', '.4em')
								.prependTo($('#dataTable_filter'));
							$.each(optionsActive, function (k, v) {
								selectActive.append($('<option></option>').attr('value', k).text(v));
							});

							let selectGroups = $('<select/>', {id: 'Groups', class: 'form-select form-select-sm d-inline w-auto'})
								.prependTo($('#dataTable_filter'));
							$.each(optionsGroups, function (k, v) {
								selectGroups.append($('<option></option>').attr('value', k).text(v));
							});

							this.api().cells(null, 1).every(function() {
								//const data = this.data();
								const node = this.node();
								$(node).attr('aria-label', 'Имя:');
							});
							this.api().cells(null, 2).every(function() {
								//const data = this.data();
								const node = this.node();
								$(node).attr('aria-label', 'Почта:');
							});
							this.api().cells(null, 3).every(function() {
								//const data = this.data();
								const node = this.node();
								$(node).attr('aria-label', 'Группа:');
							});
							this.api().cells(null, 4).every(function() {
								//const data = this.data();
								const node = this.node();
								$(node).attr('aria-label', 'Последний визит:');
							});
						}
					});

				$(document).on('change', '#Active', function(e) {
					let activeValue = $(this).val(),
						currentAjax = table.ajax.url() || route,
						url = new URL(currentAjax, window.location.origin);
					(activeValue === '0' || activeValue === null || activeValue === '')
						? url.searchParams.delete('delete')
						: url.searchParams.set('delete', activeValue);
					table.ajax.url(url.toString()).load();
				});

				$(document).on('change', '#Groups', function(e) {
					let groupsValue = $(this).val(),
						currentAjax = table.ajax.url() || route,
						url = new URL(currentAjax, window.location.origin);
					(groupsValue === '0' || groupsValue === null || groupsValue === '')
						? url.searchParams.delete('groups')
						: url.searchParams.set('groups', groupsValue);
					table.ajax.url(url.toString()).load();
				});

				$('#editUsers').submit(function(e) {
					e.preventDefault();
                    var form = $(this),
        				params = form.serialize(),
        				method = form.attr('method'),
        				action = form.attr('action'),
        				submit = form.find('[type="submit"]'),
						result = form.find('.result'),
						checked = form.find('input.checkItem:checked')
        			;
					if ( !confirm('Вы действительно хотите удалить это ?') ) {
						return;
					}
					if ( !checked || typeof checked === "undefined" ) {
						alert('Not checked');
					}
					$.ajax({
        				url: action,
        				data: params,
        				method: method,
        				dataType: 'html',
						context : result,
        				beforeSend: function () {
        					submit.attr('disabled', true);
        				}
        			}).done(function(xhr, status) {
						$(this).removeClass('alert-danger').addClass('alert-success').html(xhr);
        			}).fail(function(xhr, status) { 
        				$(this).removeClass('alert-success').addClass('alert-danger').html(xhr.responseText);
        			}).always(function(xhr) {
						form[0].reset();
        				submit.attr('disabled', null);
						table.ajax.reload(null, false);
        			});
				});

				$('#Reload').click( (e) => {
					e.preventDefault() ;
					table.ajax.reload(null, false);
				});

				$('#Download').click( () => {
					ExportToExcel('dataTable', 'xls', 'usersTable.xls');
				});

				$(document).keydown(function(e) {
					var kCode = e.keyCode || e.which,
						arrow = {
							up:    38,
							down:  40,
							left:  37, 
							right: 39
						};

					switch (kCode) {
						case arrow.up:
							//..
						break;
						case arrow.down:
							//..
						break;
						case arrow.left:
							$('#dataTable_previous').find('a').click();
						break;
						case arrow.right:
							$('#dataTable_next').find('a').click();
						break;
					}
				});

				// setInterval(() => {
				//  	//table.ajax.reload(null, false);
				// }, 4000);
			});
		</script>
@endpush 