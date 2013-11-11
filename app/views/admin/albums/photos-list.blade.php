@extends('admin.layouts.master')

@section('head-css')
    @parent
    <link rel="stylesheet" href="{{ URL::asset('assets/css/chosen.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('assets/css/jquery.Jcrop.min.css') }}" />
@endsection

@section('page-header')
    <div class="page-header">
        <h1>
            {{ $album->title }}
            <small>
                <i class="icon-double-angle-right"></i>
                {{ $album->description }}
            </small>
        </h1>
    </div><!-- /.page-header -->
@stop

@section('content')

    <p>
        <a class="btn btn-primary" href="{{ URL::action('PhotosController@getUpload', array('album_id' => $album->id)) }}">
            <i class="icon-cloud-upload align-top bigger-125"></i>
            Upload new photos
        </a>
        <a id="transfer" class="btn btn-success" href="#">
            <i class="icon-exchange align-top bigger-125"></i>
            Move photos
        </a>
    </p>

    <div class="col-sm-4 pull-right transfer-div" style="display: none">
        <div class="widget-box">
            <div class="widget-header header-color-blue2">
                <h4 class="lighter smaller">Choose album
                    <select class="chosen-select select-album">
                        <option value="">&nbsp;</option>
                        @foreach($albums as $album)
                        <option value="{{ $album->id }}">{{ $album->title }}</option>
                        @endforeach
                    </select>
                </h4>
            </div>

            <div class="widget-body">
                <div class="widget-main padding-8">

                    <ul id="album-to" class="ace-thumbnails" style="min-height: 345px">

                    </ul>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>

    <ul class="ace-thumbnails" id="album-from">
    @foreach ($photos as $photo)

        <li class="med-photo-thumb" data-photo-id="{{ $photo->id }}">
            <a href="{{ URL::asset('gallery/images/' . $photo->file_name) }}" title="{{ $photo->description }}" data-rel="colorbox">
                <img alt="200x200" src="{{ URL::asset('gallery/thumbs/' . $photo->file_name) }}" />

            </a>

            <div class="tools">

                <a href="{{ URL::action('AlbumsController@postSetCover') }}" class="ajax" data-id="{{ $photo->id }}" title="Make cover photo">
                    <i class="icon-link"></i>
                </a>

                <a href="#" class="photo-edit-form" data-photo-id="{{ $photo->id }}" data-photo-description="{{ $photo->description }}">
                    <i class="icon-pencil" title="Edit photo"></i>
                </a>

                <a href="{{ URL::asset('gallery/images/' . $photo->file_name) }}" class="photo-crop-form" data-photo-id="{{ $photo->id }}">
                    <i class="icon-crop" title="Crop photo"></i>
                </a>

                <a href="{{ URL::action('PhotosController@destroy') }}" class="ajax" data-id="{{ $photo->id }}"  data-action="delete-photo" title="Delete photo">
                    <i class="icon-remove red"></i>
                </a>
            </div>
        </li>

    @endforeach
    </ul>

    @include('admin.modals.photo-list-edit-modal')
    @include('admin.modals.photo-list-crop-modal')

@stop


@section('scripts')

    <!-- page specific plugin scripts -->

    <script src="{{ URL::asset('assets/js/jquery.colorbox-min.js') }}"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script src="{{ URL::asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/jquery.Jcrop.min.js') }}"></script>

    <!-- inline scripts related to this page -->

    <script type="text/javascript">
        var $getPhotos = "{{ URL::action('PhotosController@getPhotos') }}";
        var $postTransferUrl = "{{ URL::action('PhotosController@postTransfer') }}";
        var $thumbsUrl = "{{ URL::asset('gallery/thumbs') }}";

        jQuery(function ($) {

            var jcrop_api;
            var cropContainer = "#photo-crop-container";
            var cropObject = "#photo-crop";

            function setCoords(c)
            {
                $('#x').val(c.x);
                $('#y').val(c.y);
                $('#w').val(c.w);
                $('#h').val(c.h);
            }

            function flush_jcrop() {

                if(jcrop_api){
                    $(cropContainer).empty();
                    jcrop_api.destroy();
                }

            }

            $(".photo-crop-form").click(function(event){
                event.preventDefault();
                flush_jcrop();

                var cropSrc = $(this).attr('href');
                var cropid = $(this).attr('data-photo-id');

                $("#photo-id").attr("value", cropid);
                $(cropContainer).append("<img id=\"photo-crop\" class=\"crop-photo\" src=" + cropSrc + " />");

                $("#photo-crop").Jcrop({
                    onChange:   setCoords,
                    onSelect:   setCoords
                },function(){
                    jcrop_api = this;
                });

                $('#photo-crop-modal-form').modal('show');

            });
        });

        jQuery(function ($) {
            $("#transfer").click(function(event){
                event.preventDefault();
                $(".transfer-div").toggle("fast");
                $(".chosen-select").chosen();
            })

            var colorbox_params = {
                reposition: true,
                scalePhotos: true,
                scrolling: false,
                previous: '<i class="icon-arrow-left"></i>',
                next: '<i class="icon-arrow-right"></i>',
                close: '&times;',
                current: '{current} of {total}',
                maxWidth: '100%',
                maxHeight: '100%',
                onOpen: function () {
                    document.body.style.overflow = 'hidden';
                },
                onClosed: function () {
                    document.body.style.overflow = 'auto';
                },
                onComplete: function () {
                    $.colorbox.resize();
                }
            };

            $('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);
            $("#cboxLoadingGraphic").append("<i class='icon-spinner orange'></i>");//let's add a custom loading icon

        });
    </script>


@stop
