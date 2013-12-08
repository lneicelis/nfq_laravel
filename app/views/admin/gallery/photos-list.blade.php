@extends('admin.layouts.master')

@section('head-css')

<link rel="stylesheet" href="{{ URL::asset('assets/css/chosen.css') }}" xmlns="http://www.w3.org/1999/html"/>
    <link rel="stylesheet" href="{{ URL::asset('assets/css/jquery.Jcrop.min.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('assets/css/photo-tagger.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('assets/css/colorpicker.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('assets/css/colorbox.css') }}" />

    @parent
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
        <div class="album-likes" likes-type="album" likes-obj-id="{{ $album->id }}" likes-href="{{ URL::action('LikesController@postGetLikes') }}"></div>
    </div><!-- /.page-header -->
@stop

@section('content')

    @if($can_edit)
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
    @endif

    <div class="col-xs-12 no-padding-left">
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
                <a href="{{ URL::asset('public_gallery/images/' . $photo->file_name) }}" data-created="{{ $photo->created_at }}" data-description="{{ $photo->description }}" data-rel="colorbox" data-photo-id="{{ $photo->id }}">
                    <img alt="200x200" src="{{ URL::asset('public_gallery/thumbs/' . $photo->file_name) }}" data-photo-id="{{ $photo->id }}" data-rotate-current="0" data-photo-status="{{ $photo->status }}" />

                    <div class="tags">
                        <span class="label-holder">
                            <span class="label label-danger ">
                                {{ $photo->no_views }}
                                <i class="icon-eye-open"></i>
                            </span>
                        </span>

                        <span class="label-holder">
                            <span class="label label-info arrowed">
                                {{ $photo->no_tags }}
                                <i class="icon-tags"></i>
                            </span>
                        </span>

                        <span class="label-holder">
                            <span class="label label-warning">
                                {{ $photo->no_comments }}
                                <i class="icon-comments"></i>
                            </span>
                        </span>

                        <span class="label-holder">
                            <span class="label label-success arrowed-in">
                                {{ $photo->no_likes }}
                                <i class="icon-heart"></i>
                            </span>
                        </span>
                    </div>
                </a>

                @if($can_edit)
                    <div class="tools">

                        <a href="{{ URL::action('AlbumsController@postSetCover') }}" class="ajax" data-id="{{ $photo->id }}" title="Make cover photo">
                            <i class="icon-link"></i>
                        </a>

                        <a href="#" class="photo-edit-form" data-photo-id="{{ $photo->id }}" data-photo-description="{{ $photo->description }}">
                            <i class="icon-pencil" title="Edit photo"></i>
                        </a>

                        <a href="{{ URL::asset('public_gallery/images/' . $photo->file_name) }}" class="photo-crop-form" data-photo-id="{{ $photo->id }}">
                            <i class="icon-crop" title="Crop photo"></i>
                        </a>

                        <a href="{{ URL::asset('public_gallery/images/' . $photo->file_name) }}" class="photo-tag-form" data-photo-id="{{ $photo->id }}">
                            <i class="icon-tags" title="Tag photo"></i>
                        </a>

                        <a href="{{ URL::action('PhotosController@postStatus') }}" class="ajax" data-id="{{ $photo->id }}" data-after="change-visibility">
                            @if($photo->status === 0)
                                <i class="icon-eye-open" title="Make this photo visible"></i>
                            @else
                                <i class="icon-eye-close" title="Make this photo invisible"></i>
                            @endif
                        </a>

                        <a href="{{ URL::action('PhotosController@postRotate', array('direction' =>'right')) }}" class="ajax" data-id="{{ $photo->id }}" data-after="rotate-right">
                            <i class="icon-rotate-right" title="Rotate photo 90"></i>
                        </a>

                        <a href="{{ URL::action('PhotosController@postRotate', array('direction' =>'left')) }}" class="ajax" data-id="{{ $photo->id }}" data-after="rotate-left">
                            <i class="icon-rotate-left" title="Rotate photo -90"></i>
                        </a>

                        <a href="{{ URL::action('PhotosController@destroy', array('photo_id' => $photo->id)) }}" class="ajax"  data-after="delete-photo" title="Delete photo">
                            <i class="icon-remove red"></i>
                        </a>
                    </div>
                @endif
            </li>

        @endforeach
        </ul>
    </div>

    <div class="col-xs-12 center">
        {{ $photos->links(); }}
    </div>

    @if($can_edit)
        @include('admin.gallery.modals.photo-edit-modal')
        @include('admin.gallery.modals.photo-crop-modal')
        @include('admin.gallery.modals.photo-tag-modal')
    @endif
        @include('admin.gallery.modals.colorbox-modal')

@stop


@section('scripts')

    <!-- page specific plugin scripts -->

    <script src="{{ URL::asset('assets/js/jquery.colorbox-min.js') }}"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script src="{{ URL::asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/jquery.Jcrop.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/jquery.rotate.js') }}"></script>
    <script src="{{ URL::asset('assets/js/admin/crop.custom.js') }}"></script>
    <script src="{{ URL::asset('assets/js/admin/photo.transfer.js') }}"></script>
    <script src="{{ URL::asset('assets/js/admin/data-to-form.js') }}"></script>
    <script src="{{ URL::asset('assets/js/admin/photo-tag-widget.js') }}"></script>
    <script src="{{ URL::asset('assets/js/admin/likes-widget.js') }}"></script>
    <script src="{{ URL::asset('assets/js/admin/comments-widget.js') }}"></script>
    <script src="{{ URL::asset('assets/js/jquery.slimscroll.min.js') }}"></script>

    <!-- inline scripts related to this page -->

    <script type="text/javascript">
        var $getPhotos = "{{ URL::action('PhotosController@getPhotos') }}";
        var $postTransferUrl = "{{ URL::action('PhotosController@postTransfer') }}";
        var $thumbsUrl = "{{ URL::asset('public_gallery/thumbs') }}";


        jQuery(function ($) {
            $(document).find(".album-likes").showLikes({
                postLikeUrl: "{{ URL::action('LikesController@postLike') }}",
                message: " people like this album."
            });

            var colorbox_params = {
                reposition: true,
                scalePhotos: true,
                scrolling: false,
                previous: '<i class="icon-arrow-left"></i>',
                next: '<i class="icon-arrow-right"></i>',
                close: '&times;',
                current: '{current} of {total}',
                width:'1000px',
                height:'640px',
                onOpen: function () {
                    document.body.style.overflow = 'hidden';
                },
                onClosed: function () {
                    document.body.style.overflow = 'auto';
                },
                onComplete: function (a) {
                    $(document).find(".cboxPhoto").attr("magger-photo-id", $(this).attr("data-photo-id")).css({margin:"0px"})
                        .maggerShow({getTagsUrl: "{{ URL::action('PhotoTagsController@postGet') }}"})
                    $(".photo-tag-container-wrapper").remove();
                    $(document).find(".photo-tag-container").wrap('<div class="photo-tag-container-wrapper"></div>');

                    var container = $('<div></div>')
                        .appendTo($(document).find("#cboxLoadedContent"));
                    container.slimScroll({
                        height: '560px',
                        alwaysVisible : false
                    });
                    $(document).find('.slimScrollDiv').wrap('<div class="photo-info-container"></div>');

                    $.ajax({
                        url: "{{ URL::Action('PhotosController@postGetPhotoInfo') }}",
                        type: "post",
                        data: {"id": $(this).attr('data-photo-id')},
                        success: function(result,status,xhr){
                            console.log(result);
                            showPhotoInfo(result)
                        },
                        error:function(xhr,status,error){
                            $.grit("error", "Error", "There was an error. The the request was denied. Please try again.");
                        }
                    });

                    function showPhotoInfo(data){
                        container.append('<div class="photo-info clearfix">' +
                            '<a class="user" href="{{ URL::action("UsersController@getProfile") }}/' + data.user.id + '">' +
                            '<img class="photo-info-img" src="{{ URL::asset("public_users/thumbs/") }}/' + data.user.picture + '">' +
                            '<div class="photo-info-user">' + data.user.first_name + ' ' + data.user.last_name + '</div>' +
                            '</a>' +
                            '<div class="photo-info-date">' + data.photo.created_at + '</div>' +
                            '<div class="photo-description">' + data.photo.description + '</div>' +
                            '</div>');

                        container.append('<div class="photo-likes" likes-type="photo" likes-obj-id="' + data.photo.id + '" likes-href="{{ URL::action("LikesController@postGetLikes") }}"></div>');
                        $(document).find(".photo-likes").showLikes({
                            postLikeUrl: "{{ URL::action('LikesController@postLike') }}"
                        });

                        container.append('<div class="photo-comments comments-holder" comments-type="photo" comments-obj-id="' + data.photo.id + '" comments-href="{{ URL::action("CommentsController@postShowComments") }}"></div>');
                        $(document).find('.comments-holder').showComments({
                            profileUrl: '{{ URL::action("UsersController@getProfile") }}/',
                            profileImgUrl: '{{ URL::asset("public_users/thumbs/") }}/',
                            formActionUrl: '{{ URL::action("CommentsController@postComment") }}'
                        });
                    }
                }
            };



            $('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);
            $("#cboxLoadingGraphic").append("<i class='icon-spinner orange'></i>");//let's add a custom loading icon

            $('.photo-edit-form').dataToForm({
                callback: function(){ $("#photo-edit-modal-form").modal('show'); }
            });

            $("[data-photo-status=0]").css({opacity: "0.6"});

            $(".photo-tag-form").click(function(event){
                event.preventDefault();

                $("#photo-to-tag").attr("src", $(this).attr('href'));
                $("#photo-to-tag").attr("magger-photo-id", $(this).attr('data-photo-id'));

                $('#photo-tag-modal-form').modal('show');

                $("#photo-to-tag").magger({
                    formCreateUrl: "{{ URL::action('PhotoTagsController@postCreate') }}",
                    formEditUrl: "{{ URL::action('PhotoTagsController@postEdit') }}",
                    getTagsUrl: "{{ URL::action('PhotoTagsController@postGet') }}",
                    formDeleteUrl: "{{ URL::action('PhotoTagsController@postDelete') }}"
                });
            });
        });
    </script>

@stop
