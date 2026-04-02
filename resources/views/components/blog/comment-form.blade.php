
                {{-- resources/views/components/blog/comment-form.blade.php --}}
                @props(['post']) 
                    <form method="POST" action="{{ route('comments.store') }}" {{ $attributes->merge(['class' => 'comment-form']) }}>
                        <div class="result"></div>
                        @csrf 
                        @guest 
                        <input type="text" name="author"  
                            class="form-control my-3" 
                            data-missing="Заполните поле имя"
                            placeholder="Ваше имя" 
                            required>
                        <input type="email" name="mail"  
                            class="form-control my-3" 
                            data-missing="Заполните поле E-mail"
                            placeholder="Ваш E-mail"
                            required>
                        @endguest 
                        <textarea name="comment" 
                            class="form-control my-3" 
                            data-missing="Заполните поле Сообщение" 
                            placeholder="Ваш комментарий"
                            required></textarea>
                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                        <input type="hidden" name="parent" value="0">
                        <input type="hidden" name="type" value="{{ $post->type }}">
                        <input type="submit" accesskey="s" class="btn btn-primary w-50" value="{{ __('Отправить') }}">
                        <div class="loader w-100 h-100 top-0 end-0 d-flex position-absolute align-items-center justify-content-center d-none">
                            <div class="form-loading"></div>
                        </div>
                    </form>
