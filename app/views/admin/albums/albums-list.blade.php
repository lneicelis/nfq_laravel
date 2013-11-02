@extends('admin.layouts.master')

@section('content')
    <p><a href="{{ URL::action('AlbumsController@getNewAlbum') }}">Create a new album</a></p>

    <p>Albums</p>

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

                <a href="#album-edit-form" class="album-edit-form" data-album-id="{{ $album->id }}" data-title="{{ @$album->title }}" data-description="{{ @$album->description }}">
                    <i class="icon-pencil"></i>
                </a>

                <a href="{{ URL::to('album/destroy/' . $album->id) }}" title="Delete the album">
                    <i class="icon-remove red"></i>
                </a>
            </div>
        </li>

        @endforeach
    </ul>

    <div id="album-edit-modal-form" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <form method="post" action="{{ URL::action('AlbumsController@postEdit') }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="blue bigger">Album edit</h4>
                    </div>

                    <div class="modal-body overflow-visible">
                        <div class="row">

                                <div class="col-sm-10 col-sm-offset-1">

                                    <div class="space-4"></div>

                                    <div class="form-group">
                                        <label for="form-field-username">Album information</label>
                                        <div>
                                            {{ Form::token() }}
                                            <input id="album-edit-form-album-id" name="album_id" value="" type="hidden" />
                                            <input id="album-edit-form-title" name="title"  class="input-xxlarge" type="text" id="" placeholder="Title" />
                                            <div class="space-4"></div>
                                            <textarea id="album-edit-form-description" name="description" class="form-control limited" maxlength="250" placeholder="Description"></textarea>
                                        </div>
                                    </div>
                                </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-sm" data-dismiss="modal">
                            <i class="icon-remove"></i>
                            Cancel
                        </button>

                        <button class="btn btn-sm btn-primary">
                            <i class="icon-ok"></i>
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('scripts')

    <!-- basic scripts -->

    <!--[if !IE]> -->

    <script type="text/javascript">
        window.jQuery || document.write("<script src='{{ URL::asset('assets/js/jquery-2.0.3.min.js') }}'>"+"<"+"/script>");
    </script>

    <!-- <![endif]-->

    <!--[if IE]>
    <script type="text/javascript">
        window.jQuery || document.write("<script src='{{ URL::asset('assets/js/jquery-1.10.2.min.js') }}'>"+"<"+"/script>");
    </script>
    <![endif]-->

    <script type="text/javascript">
        if("ontouchend" in document) document.write("<script src='{{ URL::asset('assets/js/jquery.mobile.custom.min.js') }}'>"+"<"+"/script>");
    </script>
    <script src="{{ URL::asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/typeahead-bs2.min.js') }}"></script>

    <!-- page specific plugin scripts -->

    <!-- ace scripts -->

    <script src="{{ URL::asset('assets/js/ace-elements.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/ace.min.js') }}"></script>

    <!-- inline scripts related to this page -->
    <script>
        $('.album-edit-form').on('click', function () {
            var albumId = $(this).attr('data-album-id');
            var title = $(this).attr('data-title');
            var description = $(this).attr('data-description');


            $("#album-edit-form-album-id").attr('value', albumId);
            if(title !== false){
                $("#album-edit-form-title").attr('value', title);
            }
            if(description !== false){
                $("#album-edit-form-description").val(description);
            }else{
                $("#album-edit-form-description").val('');
            }

            $('#album-edit-modal-form').modal('show')
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

    </script>

@stop