@extends('admin.layouts.master')

@section('content')
    <p>
        <a id="new-album" class="btn btn-primary" href="#">
            <i class="icon-plus align-top bigger-125"></i>
            Create a new album
        </a>
    </p>
    <ul class="ace-thumbnails">
        @foreach ($albums as $album)

        <li style="width: 200px; height: 200px">
            <a href="{{ URL::action('AlbumsController@show', array($album->id)) }}" title="{{ $album->title }}" >

                @if(!empty($album->file_name))
                    <img alt="200x200" src="{{ URL::asset('gallery/thumbs/' . $album->file_name) }}" />
                @else
                    <img alt="200x200" src="{{ URL::asset('gallery/thumbs/' . $default_cover) }}" />
                @endif

                <div class="tags">
                        <span class="label-holder">
                            <span class="label label-info">{{ $album->title }}</span>
                        </span>
                </div>
            </a>
            <div class="tools">

                <a href="#album-edit-form" class="album-edit-form" data-album-id="{{ $album->id }}" data-album-title="{{ @$album->title }}" data-album-description="{{ @$album->description }}">
                    <i class="icon-pencil"></i>
                </a>

                <a href="{{ URL::to('album/destroy/' . $album->id) }}" title="Delete the album">
                    <i class="icon-remove red"></i>
                </a>
            </div>
        </li>

        @endforeach
    </ul>

    @include('admin.modals.album-new-modal')
    @include('admin.modals.album-edit-modal')

@stop

@section('scripts')

    <!-- page specific plugin scripts -->
    <script src="{{ URL::asset('assets/js/admin/data-to-form.js') }}"></script>

    <!-- inline scripts related to this page -->
    <script>
        $(".album-edit-form").dataToForm({
            callback: function(){ $("#album-edit-modal-form").modal("show"); }
        });

        $("#new-album").bind("click", function(){
            $('#album-new-modal-form').modal("show");
        })

        $("#bootbox-confirm").on(ace.click_event, function() {
            bootbox.confirm("Are you sure?", function(result) {
                if(result) {
                    return true;
                }else{
                    return false;
                }
            });
        });

    </script>

@stop