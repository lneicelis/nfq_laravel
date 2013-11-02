@extends('admin.layouts.master')

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

    <p><a href="{{ URL::to('upload/' . $album->id) }}">Upload new photos</a></p>

    <ul class="ace-thumbnails">
    @foreach ($photos as $photo)

        <li style="width: 200px; height: 200px">
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

                <a href="{{ URL::to('photo/destory') }}" class="ajax" data-id="{{ $photo->id }}" title="Delete photo">
                    <i class="icon-remove red"></i>
                </a>
            </div>
        </li>

    @endforeach
    </ul>

<div id="photo-edit-modal-form" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" action="{{ URL::to('photo/edit') }}">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="blue bigger">Photo edit</h4>
                </div>

                <div class="modal-body overflow-visible">
                    <div class="row">

                        <div class="col-sm-10 col-sm-offset-1">

                            <div class="space-4"></div>

                            <div class="form-group">
                                <label for="form-field-username">Photo information</label>
                                <div>
                                    <input id="photo-edit-form-album-id" name="photo_id" value="" type="hidden" />

                                    <textarea id="photo-edit-form-description" name="description" class="form-control limited" maxlength="250" placeholder="Description"></textarea>
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


    <script src="{{ URL::asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/typeahead-bs2.min.js') }}"></script>

    <!-- page specific plugin scripts -->

    <script src="{{ URL::asset('assets/js/jquery.colorbox-min.js') }}"></script>

    <!-- ace scripts -->

    <script src="{{ URL::asset('assets/js/ace-elements.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/ace.min.js') }}"></script>

    <!-- inline scripts related to this page -->

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

        $('.photo-edit-form').on('click', function () {
            var photoId = $(this).attr('data-photo-id');
            var description = $(this).attr('data-photo-description');

            $("#photo-edit-form-album-id").attr('value', photoId);

            if(description !== false){
                $("#photo-edit-form-description").val(description);
            }else{
                $("#photo-edit-form-description").val('');
            }

            $('#photo-edit-modal-form').modal('show')
        });

        $(".bootbox-confirm").on(ace.click_event, function() {
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
