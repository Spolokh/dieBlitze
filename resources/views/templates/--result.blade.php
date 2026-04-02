@foreach (['success', 'warning', 'error', 'info'] as $type)
	@if (session($type))
	<div class="alert alert-{{ $type === 'error' ? 'danger' : $type }}"> 
		<i class="fa {{ $type === 'error' ? 'fa-times' : 'fa-check' }}"></i> {{ session($type) }} 
	</div>
	@endif
@endforeach
