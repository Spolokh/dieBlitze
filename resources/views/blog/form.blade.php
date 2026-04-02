
                 
                <form id="updatePost" method="POST" action="{{ route('blog.update', $post) }}" class="position-relative">
					@include ('templates.result')
					@method('PUT') 
					@csrf 
                    <input class="form-control mb-3" type="text" name="title" value="{{ $post->title }}" />
                    <input class="form-control mb-3" type="text" name="date" value="{{ $post->url }}" disabled />
                    <textarea class="form-control mb-3" style="height:100px" name="description">{{ $post->story->description }}</textarea>
                    <textarea class="form-control mb-3" style="height:100px" name="short">{{ $post->story->short }}</textarea>
                    <div class="row "> 
                        <div class="col-12 col-lg-6">
                            <select name="type" id="type" class="form-select" required>
                            @foreach($types as $k => $row)
                                <option value="{{ $k }}" @selected(old('type') == $k)>
                                    {{ $row }}
                                </option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-lg-6">
                            <select name="hidden" id="hidden" class="form-select" required>
                            @foreach([0 => 'Активен', 1 => 'Не активен'] as $k => $row)
                                <option value="{{ $k }}" @selected(old('type') == $k)>
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
