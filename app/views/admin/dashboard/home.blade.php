@extends('admin.layouts.master')

@section('head-css')
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
                            <ul class="ace-thumbnails">
                                @foreach ($trending_photos as $photo)

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