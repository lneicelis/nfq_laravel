@extends('admin.layouts.master')

@section('content')

    @if($can_edit)
        <p>
            <a id="new-album" class="btn btn-primary" href="#">
                <i class="icon-plus align-top bigger-125"></i>
                Create a new album
            </a>
        </p>
    @endif

    <div class="col-xs-12 no-padding-left">
        <ul class="ace-thumbnails">
            @foreach ($albums as $album)

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
                                    <i class="icon-heart"></i>
                                </span>
                            </span>
                    </div>
                </a>

                @if($can_edit)
                    <div class="tools">

                        <a href="#album-edit-form" class="album-edit-form" data-album-id="{{ $album->id }}" data-album-title="{{ @$album->title }}" data-album-description="{{ @$album->description }}">
                            <i class="icon-pencil"></i>
                        </a>

                        <a href="{{ URL::action('AlbumsController@destroy', array('album_id' => $album->id)) }}" title="Delete the album">
                            <i class="icon-remove red"></i>
                        </a>
                    </div>
                @endif
            </li>

            @endforeach
        </ul>
    </div>

    <div class="col-xs-12xs center">
        {{ $albums->links(); }}
    </div>

    @include('admin.gallery.modals.album-new-modal')
    @include('admin.gallery.modals.album-edit-modal')

@stop

@section('scripts')

    <!-- page specific plugin scripts -->
    <script src="{{ URL::asset('assets/js/admin/data-to-form.js') }}"></script>

    <!-- inline scripts related to this page -->
    <script>
        (function($){
            $(".album-edit-form").dataToForm({
                callback: function(){ $("#album-edit-modal-form").modal("show"); }
            });

            $("#new-album").bind("click", function(){
                $('#album-new-modal-form').modal("show");
            });

            $("#bootbox-confirm").on(ace.click_event, function() {
                bootbox.confirm("Are you sure?", function(result) {
                    if(result) {
                        return true;
                    }else{
                        return false;
                    }
                });
            });
            $.albumCommentsUrl = "{{ URL::action('AlbumsController@postComment') }}";
        })(jQuery)
    </script>

@stop