@extends('admin.layouts.master')

@section('page-header')
    <div class="page-header">
        <h1>
            Following
            <small>
                <i class="icon-double-angle-right"></i>
                    {{ $user->first_name . ' ' . $user->last_name }} follows:
            </small>
        </h1>
    </div><!-- /.page-header -->
    @stop

@section('content')

    @if(!empty($users))

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

    @endif

@stop