@extends('admin.layouts.master')

@section('head-css')
    <link rel="stylesheet" href="{{ URL::asset('assets/css/photo-tagger.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('assets/css/colorbox.css') }}" />
    @parent
@stop

@section('content')

    <div class="col-sm-12">
        <div class="widget-box">
            <div class="widget-header widget-header-flat">
                <h4>Trending albums this week</h4>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="ace-thumbnails">
                                @foreach ($trending_albums as $album)

                                <li style="width: 200px; height: 200px">
                                    <a href="{{ URL::action('AlbumsController@show', array($album->id)) }}" title="{{ $album->title }}" >

                                        @if(!empty($album->file_name))
                                            <img alt="200x200" src="{{ URL::asset('public_gallery/thumbs/' . $album->file_name) }}" />
                                        @else
                                            <img alt="200x200" src="{{ URL::asset('public_gallery/thumbs/' . $default_cover) }}" />
                                        @endif

                                        <div class="tags">

                                            <span class="label-holder">
                                                <span class="label label-info arrowed"><b>{{ $album->title }}</b></span>
                                            </span>

                                            <span class="label-holder">
                                                <span class="label label-warning">
                                                    {{ $album->no_photos }}
                                                    <i class="icon-picture"></i>
                                                </span>
                                            </span>

                                            <span class="label-holder">
                                                <span class="label label-success">
                                                    {{ $album->no_comments }}
                                                    <i class="icon-comments"></i>
                                                </span>
                                            </span>

                                            <span class="label-holder">
                                                <span class="label label-danger arrowed-in">
                                                    {{ $album->no_likes }}
                                                    <i class="icon-facebook"></i>
                                                </span>
                                            </span>
                                        </div>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="col-sm-12">
        <div class="widget-box">
            <div class="widget-header widget-header-flat">
                <h4>Trending photos this week</h4>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="ace-thumbnails" id="album-from">
                                @foreach ($trending_photos as $photo)

                                <li class="med-photo-thumb" data-photo-id="{{ $photo->id }}">
                                    <a href="{{ URL::asset('public_gallery/images/' . $photo->file_name) }}" data-created="{{ $photo->created_at }}" data-description="{{ $photo->description }}" data-rel="colorbox" data-photo-id="{{ $photo->id }}">
                                        <img alt="200x200" src="{{ URL::asset('public_gallery/thumbs/' . $photo->file_name) }}" data-photo-id="{{ $photo->id }}" data-rotate-current="0" data-photo-status="{{ $photo->status }}" />

                                        <div class="tags">
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
                                                        <i class="icon-facebook"></i>
                                                    </span>
                                                </span>
                                            </span>
                                        </div>
                                    </a>
                                </li>

                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@stop

@section('scripts')
<script src="{{ URL::asset('assets/js/jquery.colorbox-min.js') }}"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="{{ URL::asset('assets/js/chosen.jquery.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/jquery.Jcrop.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/jquery.rotate.js') }}"></script>
<script src="{{ URL::asset('assets/js/admin/crop.custom.js') }}"></script>
<script src="{{ URL::asset('assets/js/admin/photo.transfer.js') }}"></script>
<script src="{{ URL::asset('assets/js/admin/data-to-form.js') }}"></script>
<script src="{{ URL::asset('assets/js/admin/photo-tag-widget.js') }}"></script>
<script src="{{ URL::asset('assets/js/jquery.slimscroll.min.js') }}"></script>

<script type="text/javascript">

    jQuery(function ($) {
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
                    alwaysVisible : true
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

                    container.append('<div class="photo-info">' +
                        '<a class="user" href="{{ URL::action("UsersController@getProfile") }}/' + data.user.id + '">' +
                            '<img class="photo-info-img" src="{{ URL::asset("public_users/thumbs/") }}/' + data.user.picture + '">' +
                            '<div class="photo-info-user">' + data.user.first_name + ' ' + data.user.last_name + '</div>' +
                        '</a>' +
                        '<div class="photo-info-date">' + data.photo.created_at + '</div>' +
                        '<div class="photo-description">' + data.photo.description + '</div>' +
                    '</div>');
                    container.append('<div class="fb-photo-likes fb-like" data-href="https://developers.facebook.com/docs/plugins/" data-width="260" data-layout="standard" data-action="like" data-show-faces="false" data-share="false"></div>')
                    container.append('<div class="fb-photo-comments fb-comments" data-href="http://onlinetv.lt?id=6" data-width="260" data-num-posts="3"></div>');
                    FB.XFBML.parse();

                }
            }
        };

        $('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);
        $("#cboxLoadingGraphic").append("<i class='icon-spinner orange'></i>");//let's add a custom loading icon
    });

</script>
@stop