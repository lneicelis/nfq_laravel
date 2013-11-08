@extends('admin.layouts.master')

@section('head-css')
    @parent
    <link rel="stylesheet" href="{{ URL::asset('assets/css/chosen.css') }}" />
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
        <a class="btn btn-primary" href="{{ URL::to('upload/' . $album->id) }}">
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

                <a href="#" class="photo-crop-form" data-photo-id="" data-photo-description="">
                    <i class="icon-crop" title="Crop photo"></i>
                </a>

                <a href="{{ URL::action('PhotosController@destroy') }}" class="ajax" data-id="{{ $photo->id }}"  data-action="delete-photo" title="Delete photo">
                    <i class="icon-remove red"></i>
                </a>
            </div>
        </li>

    @endforeach
    </ul>

    @include('admin.modals.photo-list-modal')

@stop


@section('scripts')

    <!-- page specific plugin scripts -->

    <script src="{{ URL::asset('assets/js/jquery.colorbox-min.js') }}"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script src="{{ URL::asset('assets/js/chosen.jquery.min.js') }}"></script>

    <!-- inline scripts related to this page -->

    <script type="text/javascript">


            var $moveFrom = "#album-from";
            var $moveTo = "#album-to";
            var $albumToId = "data-album-to-id";
            var $photoId = "data-photo-id";
            var $postTransferUrl = "{{ URL::action('PhotosController@postTransfer') }}";
            var $getPhotos = "{{ URL::action('PhotosController@getPhotos') }}";
        $(function(){
            function initDragAndDrop(){
                $( "li", "#album-from").draggable({
                    cancel: "a.ui-icon", // clicking an icon won't initiate dragging
                    revert: "invalid", // when not dropped, the item will revert back to its initial position
                    containment: "document",
                    helper: "clone",
                    cursor: "move"
                });

                $("#album-to").droppable({
                    accept: "#album-from > li",
                    activeClass: "custom-state-active",
                    drop: function( event, ui ) {
                        var url = $postTransferUrl;
                        var obj = ui.draggable;
                        var data = {
                            album_id: $($moveTo).attr($albumToId),
                            photo_id: $(obj).attr($photoId)
                        }

                        $.ajax({
                            url: url,
                            type: "post",
                            data: data,
                            success: function(result,status,xhr){
                                movePhotoAnimation( obj );
                                gritter("success", "Success", "The photo has been successfully moved.");
                                console.log('ajax move success');
                            },
                            error:function(xhr,status,error){
                                event.preventDefault();
                                gritter("error", "Error", "There was an error. The the request was denied. Please try again.");
                                console.log('ajax move error');
                            }
                        });

                    }
                });
            }

            function movePhotoAnimation( $item ) {
                $item.fadeOut(function() {

                    $item.find( "div.tools" ).remove();
                    $item.appendTo( $moveTo ).fadeIn(function() {
                        $item
                            .animate({
                                width: "100px",
                                height: "100px"})
                            .find("img")
                            .animate({
                                width: "100px",
                                height: "100px"});
                    });
                });
            }

            $(".select-album").change(function(event)
            {
                event.preventDefault();

                var url = $getPhotos;
                var id = $(this).val();
                var list = '';
                var appendTo = "#album-to";
                var data = {
                    _token: token, //global token, that has been set in the head of HTML
                    id : id
                };

                $.ajax({
                    url: url,
                    type: "post",
                    data: data,
                    success: function(result,status,xhr){
                        $.each(result, function (index, value) {
                            list = list +
                                "<li class=\"small-photo-thumb\">" +
                                "<a href=\"\" title=\"\">" +
                                "<img class=\"small-photo-thumb\" src=\"{{ URL::asset('gallery/thumbs/') }}/" + value.file_name + "\" />" +
                                "</a>" +
                                "</li>";
                        });
                        $(appendTo).attr($albumToId, id).empty().append(list);
                        initDragAndDrop();
                    },
                    error:function(xhr,status,error){
                        //gritter("error", "Error", "There was an error. The the request was denied. Please try again.");
                    }
                });

            });
        });
    </script>


@stop
