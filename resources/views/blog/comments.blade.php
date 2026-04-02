			@foreach($comments as $comment)
			<div class="d-flex comment py-3" style="margin-left: {{ $comment->level * 34 }}px;">
						<figure class="flex-shrink-0 mt-1"  data-bs-toggle="tooltip" title="{{ e($comment->author) }}">
							<img class="rounded-circle w-100" src="{{ $comment->avatarUrl }}" alt="{{ e($comment->author) }}"/>
						</figure>
						<div id="comment{{$comment->id}}" class="body flex-shrink-1 ms-3">
							<div class="lead title text-muted">
								{{ e($comment->user?->name ?? $comment->author) }} &bull; <time>{{ date('d.m.Y H:i', $comment->date) }}</time>
							</div>
							<div class="story my-2 flex-shrink-1">
								<p class="mb-1">
									{!! Str::replace("\r\n", '<br/>', $comment->comment) !!}
								</p>
							@if($comment->reply) 
								<div class="reply border-start ps-3">
									<i>Ответ Администратора:</i><br/>
									<div class="m-0 p-0">{!! e($comment->reply) !!}</div>
								</div>
							@endif
							</div> 
							<div class="attr flex-shrink-2">
								<a href="#" data-id="{{$comment->id}}" data-comment="comment{{$comment->id}}" title="Ответить {{$comment->id}}" class="quickreply text-decoration-none">
									<i class="fa fa-reply"></i> Ответить
								</a>
								@isAdmin 
								<a href="{{ route('comments.edit', $comment) }}"
									data-form="editComment"
									data-bs-toggle="modal"
									data-bs-target="#myModal"
									title="Комментарий ID: {{$comment->id}} {{ date('d.m.Y H:i', $comment->date) }}" class="ms-2 text-decoration-none">
									<i class="fa fa-pencil"></i> Редактировать
								</a>
								@endisAdmin 
							</div> 
						</div>
					</div>
				@if($comment->children->isNotEmpty()) 
					@include('blog.comments', ['comments' => $comment->children]) 
        		@endif 
			@endforeach
