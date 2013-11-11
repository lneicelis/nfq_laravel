@extends('admin.layouts.master')

@section('head-css')
    @parent

    <link rel="stylesheet" href="{{ URL::asset('assets/css/dropzone.css') }}" />
@stop

@section('content')

    <form action="{{ URL::action('PhotosController@postUpload', array('album_id' => $album_id)) }}" method="post" enctype="multipart/form-data" id="userfile" class="dropzone">
        <div class="fallback">
            <input name="file" type="file" multiple="" />
            <button>Upload</button>
        </div>
    </form>

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

    <script src="{{ URL::asset('assets/js/dropzone.min.js') }}"></script>

    <!-- ace scripts -->

    <script src="{{ URL::asset('assets/js/ace-elements.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/ace.min.js') }}"></script>

    <!-- inline scripts related to this page -->

    <script type="text/javascript">
        Dropzone.autoDiscover = false;
        jQuery(function($){

            try {
                $(".dropzone").dropzone({
                    paramName: "file", // The name that will be used to transfer the file
                    maxFilesize: 2, // MB
                    addRemoveLinks : true,
                    //change the previewTemplate to use Bootstrap progress bars
                    previewTemplate: "<div class=\"dz-preview dz-file-preview\">\n  <div class=\"dz-details\">\n    <div class=\"dz-filename\"><span data-dz-name></span></div>\n    <div class=\"dz-size\" data-dz-size></div>\n    <img data-dz-thumbnail />\n  </div>\n  <div class=\"progress progress-small progress-striped active\"><div class=\"progress-bar progress-bar-success\" data-dz-uploadprogress></div></div>\n  <div class=\"dz-success-mark\"><span></span></div>\n  <div class=\"dz-error-mark\"><span></span></div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n</div>"
                });
            } catch(e) {
                alert('Dropzone.js does not support older browsers!');
            }

        });
    </script>

@stop