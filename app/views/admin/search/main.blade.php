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
    <div class="col-xs-12 no-padding-left">

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
    </div>

    <div class="col-xs-12 no-padding-left center">
        {{ $photos->links() }}
    </div>
    @endif

    @if(!empty($users))

    <div class="col-xs-12 no-padding-left">
        <div id="friends" class="tab-pane active">
            <div class="profile-users clearfix">

            @foreach ($users as $user)
            <div class="itemdiv memberdiv">
                <div class="inline position-relative">
                    <div class="user">
                        <a href="{{ URL::action('UsersController@getProfile', array('user_id' => $user->id)) }}">
                            <img src="{{ URL::asset('public_users/thumbs/' . $user->picture) }}" alt="{{ $user->first_name }} {{ $user->last_name }}">
                        </a>
                    </div>

                    <div class="body">
                        <div class="name">
                            <a href="{{ URL::action('UsersController@getProfile', array('user_id' => $user->id)) }}">
                                {{ $user->first_name }} {{ $user->last_name }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            </div>
            <div class="hr hr10 hr-double"></div>
        </div>
    </div>

    <div class="col-xs-12 no-padding-left center">
        {{ $users->links() }}
    </div>

    @endif

@stop