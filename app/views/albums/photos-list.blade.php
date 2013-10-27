@extends('layouts.master')

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

    <p><a href="{{ URL::to('gallery')}}">Back to the gallery</a></p>
    <p><a href="{{ URL::to('upload/' . $album->id) }}">Upload new photos</a></p>

    <ul class="ace-thumbnails">
    @foreach ($photos as $photo)

        <li style="width: 200px; height: 200px">
            <a href="{{ URL::asset('images/' . $photo->file_name) }}" title="{{ $photo->description }}" data-rel="colorbox">
                <img alt="200x200" src="{{ URL::asset('thumbs/' . $photo->file_name) }}" />

            </a>

            <div class="tools">

                <a href="{{ URL::to('album/set_cover/' . $photo->id) }}" title="Make cover photo">
                    <i class="icon-link"></i>
                </a>

                <a href="#">
                    <i class="icon-pencil" title="Edit photo"></i>
                </a>

                <a href="{{ URL::to('photo/destory/' . $photo->id) }}" title="Delete photo">
                    <i class="icon-remove red"></i>
                </a>
            </div>
        </li>

    @endforeach
    </ul>
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



@stop
