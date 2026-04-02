			
				<div class="result">
				@if ($errors->any()) 
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<ul>
					@foreach($errors->all() as $error) 
							<li>{{ $error }}</li>
					@endforeach 
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</ul>
					</div>
				@endif 
				@if (session('success')) 
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						<i class="fa fa-check" aria-hidden="true"></i> {{ session('success') }}
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
					@endif
				</div>