@extends('layouts.app')
@section('styles') 
	<link rel="stylesheet" href="{{ asset('css/cropper/cropper.min.css') }}">
@endsection
@section('content') 
            <div class="my-3 p-3 bg-body rounded shadow-sm">     
                <form id="avatarForm" action="{{route('image.update')}}" method="post" enctype="multipart/form-data">
                    @include ('templates.result') 
                    <div class="row">
                        <div class="col-md-9 img-container">
                            <img id="imgPreview" src="/img/camera.png" alt=""/>
                        </div>
                        <div class="col-md-3">
                            <div class="img-preview img-preview-custom rounded-circle">
                                <img class="" src="/img/camera.png" />
                            </div>
                            <input id="inputImage" type="file" class="form-control mb-2" name="avatar" accept="image/jpg, image/jpeg, image/png, image/gif"/>
                            <div class="text-start mb-2">
                                <select id="userBox" name="user" class="form-control selectpicker" data-live-search="true">
                                @foreach ($users as $k => $row)
                                    <option value="{{$k}}" @selected($userid === $k)>
                                        {{$row}}
                                    </option>
                                @endforeach
                                </select>     
                            </div>
                            <div class="text-start mb-2">
                                <select id="aspectRatio" name="aspectRatio" class="form-select">
                                    <option value="1">Аватар</option>
                                    <option value="1.7777777777777777">Ковер</option>
                                    <option value="1.3333333333333333">Фотография</option>
                                    <option value="0.6666666666666666">Портрет</option>
                                    <option value="NaN">Свободно</option>
                                </select>
                            </div>
                            <div class="input-group mb-2">
                                <label class="input-group-text" for="dataWidth">Ширина:</label>
                                <input type="number" class="form-control" id="dataWidth" name="width" placeholder="width"/>
                                <span class="input-group-text">.px</span>
                            </div>
                            <div class="input-group mb-2">
                                <label class="input-group-text" for="dataHeight">Высота:</label>
                                <input type="number" class="form-control" id="dataHeight" name="height" placeholder="height"/>
                                <span class="input-group-text">.px</span>
                            </div>
                            <div class="input-group mb-2">
                                <label class="input-group-text" for="dataX">X:</label>
                                <input type="number" class="form-control" id="dataX" name="x" placeholder="x"/>
                                <span class="input-group-text">.px</span>
                            </div>
                            <div class="input-group mb-2">
                                <label class="input-group-text" for="dataY">Y:</label>
                                <input type="number" class="form-control" id="dataY" name="y" placeholder="y"/>
                                <span class="input-group-text">.px</span>
                            </div>
                            <div class="text-start mb-2">
                                <input type="checkbox" id="cropping" name="cropping" value="1" checked />
                                <label for="cropping"></label>
                                Кадрировать аватар ?
                            </div>
                            @csrf 
                            <button type="submit" class="btn btn-primary mb-3 w-100">Загрузить аватар</button>
                        </div>
                    </div>
                </form>
            </div>
@endsection 
@push('scripts') 
        <script src="/js/jquery.bootstrap-select.js"></script>
        <script src="{{asset('js/cropper/cropper.js')}}"></script>
        <script>
			jQuery(function($) {         
                var $image = $('#imgPreview'),
                    $input = $('#inputImage'),
                    $dataX = $('#dataX'),
                    $dataY = $('#dataY'),
                    $dataWidth  = $('#dataWidth'),
                    $dataHeight = $('#dataHeight'),
                    options = {
                        viewMode: 2,
                        dragMode: 'move',
                        aspectRatio: 1,
                        preview: '.img-preview',
                        crop: function(data) {
                            $dataX.val(Math.round(data.x));
                            $dataY.val(Math.round(data.y));
                            $dataWidth.val(Math.round(data.width));
                            $dataHeight.val(Math.round(data.height));
                        }
                    };

                $image.cropper(options);
                $input.change (function() {
                    var Reader = new FileReader(),
                        Accept = this.accept,
                        files  = this.files,
                        files  = files[0];
                    if(!files) {
                        return;
                    }

                    if(!Accept.includes(files.type)) {
                        alert ('Ваш файл не является изображением!');
                        return;
                    }

                    Reader.onload = function() {
                        $image.cropper('reset', true).cropper('replace', this.result);
                    };
                    Reader.readAsDataURL(files);
                });

                // Options
                $('#aspectRatio').change(function() {
                    var name  = $(this).attr('name'),
                        value = $(this).val();
                    // (value !== 1)
                    //     ? $('.img-preview').removeClass('rounded-circle')
                    //     : $('.img-preview').removeClass('rounded-circle');
                    options[name] = value;
                    $image.cropper('destroy').cropper(options);
                });
         
                $('#avatarForm').submit(function(e) {
                    // e.preventDefault();
                    // let form = $(this),
                    //     method = form.attr('method'),
                    //     action = form.attr('action'),
                    //     params = form.serialize(),
                    //     loader = form.find('.loader'),
                    //     submit = form.find('[type="submit"]'),
                    //     result = null;

                    // $.ajax({
                    //     url: action, 
                    //     data: params,
                    //     method: method,
                    //     beforeSend: function () {
                    //          loader.toggleClass('d-none'); 
                    //          submit.attr('disabled', true);
                    //     }
                    // }).done(function(xhr, status) {
                    //     result = true; alert(xhr);
                    // }).fail(function(xhr, status) { 
                    //     result = null; alert('error');
                    // }).always( function(xhr) {
                    //     loader.toggleClass('d-none'); 
                    //     submit.attr('disabled', null);
                    //     if (result) {
                    //          location.reload();
                    //     }
                    // });
                });
			});
    </script>
@endpush