@extends('admin.layouts.master')

@section('head-css')
@parent
    <link rel="stylesheet" href="{{ URL::asset('assets/css/jquery-ui-1.10.3.custom.min.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('assets/css/select2.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('assets/css/bootstrap-editable.css') }}" />

@stop

@section('page-header')
<div class="page-header">
    <h1>
        Settings
        <small>
            <i class="icon-double-angle-right"></i>
            Gallery settings
        </small>
    </h1>
</div><!-- /.page-header -->
@stop

@section('content')


<div class="table-responsive">
    <table id="sample-table-1" class="table table-bordered" style="width: auto">

        <tbody>
            <tr>
                <td class="center"> Facebook app id </td>
                <td>
                    <span class="editable editable-text" id="facebook_app_id">{{ $settings['facebook_app_id'] }}</span>
                </td>
            </tr>

            <tr>
                <td class="center"> Facebook app secret </td>
                <td>
                    <span class="editable editable-text" id="facebook_app_secret">{{ $settings['facebook_app_secret'] }}</span>
                </td>
            </tr>

            <tr>
                <td class="center"> Facebook app admins </td>
                <td>
                    <span class="editable editable-text" id="facebook_app_admins">{{ $settings['facebook_app_admins'] }}</span>
                </td>
            </tr>

            <tr>
                <td class="center"> Max file size </td>
                <td>
                    <span class="editable editable-number" id="max_file_size">{{ $settings['max_file_size'] }}</span> Kb
                </td>
            </tr>

            <tr>
                <td class="center"> Allowed mime types (separated by colon) </td>
                <td>
                    <span class="editable editable-text" id="mimes">{{ $settings['mimes'] }}</span>
                </td>
            </tr>

            <tr>
                <td class="center"> Max photo width </td>
                <td>
                    <span class="editable editable-number" id="max_photo_width">{{ $settings['max_photo_width'] }}</span> px
                </td>
            </tr>

            <tr>
                <td class="center"> Max photo height </td>
                <td>
                    <span class="editable editable-number" id="max_photo_height">{{ $settings['max_photo_height'] }}</span> px
                </td>
            </tr>

            <tr>
                <td class="center"> Thumbnail width </td>
                <td>
                    <span class="editable editable-number" id="thumb_width">{{ $settings['thumb_width'] }}</span> px
                </td>
            </tr>

            <tr>
                <td class="center"> Thumbnail height </td>
                <td>
                    <span class="editable editable-number" id="thumb_height">{{ $settings['thumb_height'] }}</span> px
                </td>
            </tr>

        </tbody>
    </table>
</div>

@stop

@section('scripts')
<script src="{{ URL::asset('assets/js/fuelux/fuelux.spinner.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/jquery-ui-1.10.3.custom.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/jquery.ui.touch-punch.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/bootbox.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/jquery.slimscroll.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/jquery.hotkeys.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/x-editable/bootstrap-editable.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/x-editable/ace-editable.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/jquery.maskedinput.min.js') }}"></script>

<script type="text/javascript">
    jQuery(function($) {

        //editables on first profile page
        $.fn.editable.defaults.mode = 'inline';
        $.fn.editableform.buttons = '<button type="submit" class="btn btn-info editable-submit"><i class="icon-ok icon-white"></i></button>'+
            '<button type="button" class="btn editable-cancel"><i class="icon-remove"></i></button>';

        //editables

        $('.editable-text').editable({
            type: 'text',
            pk: 1,
            url: "{{ URL::action('SettingsController@postGallerySettings') }}",
            title: '',
            params: {
                _token: "{{ csrf_token() }}",
                type: "gallery"
            },
            success: function(response, newValue){

            }
        });
        $('.editable-number').editable({
            type: 'spinner',
            pk: 1,
            url: "{{ URL::action('SettingsController@postGallerySettings') }}",
            title: '',
            params: {
                _token: "{{ csrf_token() }}",
                type: "gallery"
            },
            spinner : {
                min : 1, max:100000, step:1
            }
        });
        $(document).find('.spinner-input').removeClass('input-mini');


    });
</script>
@stop