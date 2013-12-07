@extends('admin.layouts.login')

@section('content')
    <div id="login-box" class="login-box visible widget-box no-border">
        <div class="widget-body">
            <div class="widget-main">
                <h4 class="header blue lighter bigger">
                    <i class="icon-coffee green"></i>
                    Please Login
                </h4>

                <div class="space-6"></div>

                <form method="POST" action="{{ URL::action('UsersController@postLogin') }}" autocomplete="on">
                    <fieldset>
                        {{ Form::token() }}
                        <label class="block clearfix">
                            <span class="block input-icon input-icon-right">
                                <input name="email" value="{{{ @$email }}}" type="text" class="form-control" placeholder="Email" />
                                <i class="icon-user"></i>
                            </span>
                        </label>

                        <label class="block clearfix">
                            <span class="block input-icon input-icon-right">
                                <input name="password" type="password" class="form-control" placeholder="Password" />
                                <i class="icon-lock"></i>
                            </span>
                        </label>

                        <div class="space"></div>

                        <div class="clearfix">
                            <label class="inline">
                                <input type="checkbox" name="remember" class="ace" />
                                <span class="lbl"> Remember Me</span>
                            </label>

                            <button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
                                <i class="icon-key"></i>
                                Login
                            </button>
                        </div>

                        <div class="space-4"></div>
                    </fieldset>
                </form>

            </div><!-- /widget-main -->

            <div class="toolbar clearfix">
                <div>
                    <a href="{{ URL::action('UsersController@getResetPassword') }}" class="forgot-password-link">
                        <i class="icon-arrow-left"></i>
                        I forgot my password
                    </a>
                </div>

                <div>
                    <a href="{{ URL::action('UsersController@getRegister') }}" class="user-signup-link">
                        I want to register
                        <i class="icon-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div><!-- /widget-body -->
    </div><!-- /login-box -->

@stop