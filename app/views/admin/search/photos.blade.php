@extends('admin.layouts.master')

@section('head-css')
@parent

@stop

@section('page-header')
<div class="page-header">
    <h1>
        Search
        <small>
            <i class="icon-double-angle-right"></i>
            Showing results for "{{{ $needle }}}"
        </small>
    </h1>
</div><!-- /.page-header -->
@stop

@section('content')

@if(!empty($photos))
<ul class="ace-thumbnails" id="album-from">
    @foreach ($photos as $photo)

    <li class="med-photo-thumb" data-photo-id="{{ $photo->id }}">
        <a href="{{ URL::asset('public_gallery/images/' . $photo->file_name) }}" title="{{ $photo->description }}" data-rel="colorbox" data-photo-id="{{ $photo->id }}">
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
@endif

@stop