                {{-- resources/views/components/blog/neighbors.blade.php --}}
                @props(['prev', 'next'])
                @if ($prev || $next) 
                <div {{ $attributes->class(['NextPrevPagination row g-4 my-4']) }} id="">
                    @isset($prev->id) 
                    <div class="col-sm-4">
                        <a class="prev p-2 d-block" title="{{ $prev->title }}" href="{{ route('blog.show', $prev) }}">Предыдущий пост</a>
                    </div>
                    @endisset 
                    <div class="col-sm-4">
                        
                    </div>
                    @isset($next->id) 
                    <div class="col-sm-4"> 
                        <a class="next p-2 d-block" title="{{ $next->title }}" href="{{ route('blog.show', $next) }}">Следующий пост</a>
                    </div>
                    @endisset 
                </div>
                @endif 
