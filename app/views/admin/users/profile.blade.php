@extends('admin.layouts.master')

@section('head-css')
    @parent
    <link rel="stylesheet" href="{{ URL::asset('assets/css/jquery-ui-1.10.3.custom.min.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('assets/css/select2.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('assets/css/bootstrap-editable.css') }}" />

@stop

@section('content')

<div class="col-xs-12">
<!-- PAGE CONTENT BEGINS -->

<div class="hr dotted"></div>

<div>
    <div id="user-profile-1" class="user-profile row">
        <div class="col-xs-12 col-sm-3 center">
            <div>
                <span class="profile-picture">
                    <img id="avatar" class="editable img-responsive" alt="Alex's Avatar" src="{{ URL::asset('assets/avatars/profile-pic.jpg') }}" />
                </span>

                <div class="space-4"></div>

                <div class="width-80 label label-info label-xlg arrowed-in arrowed-in-right">
                    <div class="inline position-relative">
                        <span class="white">{{ $user->first_name }} {{ $user->last_name }}</span>
                    </div>
                </div>
            </div>

            <div class="space-6"></div>

            <div class="profile-contact-info">
                <div class="profile-contact-links align-left">
                    <a class="btn btn-link ajax" href="{{ URL::action('UsersController@postFollow') }}" data-id="{{ $user->id }}">
                        <i class="icon-plus-sign bigger-120 green"></i>
                        Follow
                    </a>

                    @if($user->website)
                    <a class="btn btn-link" href="#">
                        <i class="icon-globe bigger-125 blue"></i>
                        {{ $user->website }}
                    </a>
                    @endif

                </div>

            </div>

            <div class="hr hr12 dotted"></div>

            <div class="clearfix">
                <div class="grid2">
                    <span class="bigger-175 blue">25</span>

                    <br />
                    Followers
                </div>

                <div class="grid2">
                    <span class="bigger-175 blue">12</span>

                    <br />
                    Following
                </div>
            </div>

            <div class="hr hr16 dotted"></div>
        </div>

        <div class="col-xs-12 col-sm-9">
            <div class="center">
                <span class="btn btn-app btn-sm btn-light no-hover">
                    <span class="line-height-1 bigger-170 blue"> 1,411 </span>

                    <br />
                    <span class="line-height-1 smaller-90"> Views </span>
                </span>

                <span class="btn btn-app btn-sm btn-yellow no-hover">
                    <span class="line-height-1 bigger-170"> {{ $albums->count('id') }} </span>

                    <br />
                    <span class="line-height-1 smaller-90"> Albums </span>
                </span>

                <span class="btn btn-app btn-sm btn-pink no-hover">
                    <span class="line-height-1 bigger-170"> {{ (integer)$albums->sum('no_photos') }} </span>

                    <br />
                    <span class="line-height-1 smaller-90"> Photos </span>
                </span>

                <span class="btn btn-app btn-sm btn-grey no-hover">
                    <span class="line-height-1 bigger-170"> {{ $no_comments }} </span>

                    <br />
                    <span class="line-height-1 smaller-90"> Comments </span>
                </span>

                <span class="btn btn-app btn-sm btn-success no-hover">
                    <span class="line-height-1 bigger-170"> {{ $no_likes }} </span>

                    <br />
                    <span class="line-height-1 smaller-90"> Likes </span>
                </span>

                <span class="btn btn-app btn-sm btn-primary no-hover">
                    <span class="line-height-1 bigger-170"> {{ (integer)$photos->sum('no_tags') }} </span>

                    <br />
                    <span class="line-height-1 smaller-90"> Tags </span>
                </span>
            </div>

        <div class="space-12"></div>

        <div class="profile-user-info profile-user-info-striped">
            <div class="profile-info-row">
                <div class="profile-info-name"> First name </div>

                <div class="profile-info-value">
                    <span class="editable" id="first_name">{{ $user->first_name }}</span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name"> Last name </div>

                <div class="profile-info-value">
                    <span class="editable" id="last_name">{{ $user->last_name }}</span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name"> Age </div>

                <div class="profile-info-value">
                    <span class="editable" id="age">{{ $user->age }}</span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name"> E-mail </div>

                <div class="profile-info-value">
                    <span>{{ $user->email }}</span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name"> Joined </div>

                <div class="profile-info-value">
                    <span>{{ $user->activated_at }}</span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name"> Last Login </div>

                <div class="profile-info-value">
                    <span>{{ $user->last_login }}</span>
                </div>
            </div>

            @if($can_edit)
            <div class="profile-info-row">
                <div class="profile-info-name"> Website </div>

                <div class="profile-info-value">
                    <spanclass="editable" id="website">{{ $user->website }}</span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name"> Skype </div>

                <div class="profile-info-value">
                    <spanclass="editable" id="skype">{{ $user->skype }}</span>
                </div>
            </div>
            @endif

        </div>

        <div class="space-20"></div>

            <div class="widget-box transparent">
                <div class="widget-header widget-header-small">
                    <h4 class="blue smaller">
                        <i class="icon-rss orange"></i>
                        Recent Activities
                    </h4>

                    <div class="widget-toolbar action-buttons">
                        <a href="#" data-action="reload">
                            <i class="icon-refresh blue"></i>
                        </a>

                        &nbsp;
                        <a href="#" class="pink">
                            <i class="icon-trash"></i>
                        </a>
                    </div>
                </div>

                <div class="widget-body">
                    <div class="widget-main padding-8">
                        <div id="profile-feed-1" class="profile-feed">
                            <div class="profile-activity clearfix">
                                <div>
                                    <img class="pull-left" alt="Alex Doe's avatar" src="{{ URL::asset('assets/avatars/avatar5.png') }}" />
                                    <a class="user" href="#"> Alex Doe </a>
                                    changed his profile photo.
                                    <a href="#">Take a look</a>

                                    <div class="time">
                                        <i class="icon-time bigger-110"></i>
                                        an hour ago
                                    </div>
                                </div>

                                <div class="tools action-buttons">
                                    <a href="#" class="blue">
                                        <i class="icon-pencil bigger-125"></i>
                                    </a>

                                    <a href="#" class="red">
                                        <i class="icon-remove bigger-125"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="profile-activity clearfix">
                                <div>
                                    <img class="pull-left" alt="Susan Smith's avatar" src="{{ URL::asset('assets/avatars/avatar1.png') }}" />
                                    <a class="user" href="#"> Susan Smith </a>

                                    is now friends with Alex Doe.
                                    <div class="time">
                                        <i class="icon-time bigger-110"></i>
                                        2 hours ago
                                    </div>
                                </div>

                                <div class="tools action-buttons">
                                    <a href="#" class="blue">
                                        <i class="icon-pencil bigger-125"></i>
                                    </a>

                                    <a href="#" class="red">
                                        <i class="icon-remove bigger-125"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PAGE CONTENT ENDS -->
</div><!-- /.col -->


@stop

@section('scripts')

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

        @if($can_edit)
        //editables on first profile page
        $.fn.editable.defaults.mode = 'inline';
        $.fn.editableform.buttons = '<button type="submit" class="btn btn-info editable-submit"><i class="icon-ok icon-white"></i></button>'+
            '<button type="button" class="btn editable-cancel"><i class="icon-remove"></i></button>';

        //editables

        $('#first_name, #last_name, #age, #website, #skype').editable({
            type: 'text',
            pk: 1,
            url: "{{ URL::action('UsersController@postUpdateProfile') }}",
            title: 'Enter username',
            params: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response, newValue){

            }
        });
        @endif

    });
    </script>
@stop