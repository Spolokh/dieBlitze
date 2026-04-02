                <form id="editComment" action="{{ route('comments.update', $comment) }}" method="POST" class="position-relative">
                    @method('PUT') 
                    @csrf 
                    @include ('templates.result')
                    <div class="row text-muted"> 
                        <div class="col-md-7 text-muted">
                            <input class="form-control mb-3" name="author" value="{{ $comment->user?->name ?? stripslashes($comment->author) }}" @readonly(auth()->user()?->isNotAdmin()) />
                            <input class="form-control mb-3" value="{{ $comment->dateFormatted }}" disabled />
                            <input class="form-control mb-3" value="{{ $comment->ip }}" disabled />
                        </div>
                        <div class="col-md-5 text-center mb-3">
                            <img src="{{ $comment->AvatarUrl }}" height="148" class="mw-100 rounded-circle object-fit-cover" alt=""/>
                        </div>
                    </div>
                    <div class="text-muted">
                        <input class="form-control mb-3" type="email"  name="mail" value="{{ $comment->mail }}" @readonly(auth()->user()?->isNotAdmin()) />
                    </div>
                    <div class="text-muted">
                        <textarea class="form-control mb-3" name="comment" required>{!! str($comment->comment)->replace('<br />', "\n") !!}</textarea>
                    </div>
                    <div class="text-muted">
                        <textarea class="form-control mb-3" name="reply" placeholder="Ответ">{{ $comment->reply }}</textarea>
                    </div>
                    <div class="row text-muted">
                        <div class="col-md-6">
                            <select id="posType" name="type" class="form-select" required>
                                @foreach($types as $k => $row)
                                    <option value="{{ $k }}" @selected($comment->type == $k)>
                                        {{ $row }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- \App\makeDropDown($types, 'type', $comment->type, 'class="form-select"') --}}
                        </div>
                        <div class="col-md-6">
                            <select name="hidden" id="hidden" class="form-select" required>
                                @foreach(['Активен', 'Не активен'] as $k => $row)
                                    <option value="{{ $k }}" @selected($comment->hidden == $row)>
                                        {{ $row }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="loader w-100 h-100 top-0 end-0 d-flex position-absolute align-items-center justify-content-center d-none">
                        <div class="form-loading"></div>
                    </div>
                </form>
            