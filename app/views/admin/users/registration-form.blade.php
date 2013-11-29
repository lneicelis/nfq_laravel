@extends('admin.layouts.login')

@section('content')

    <div id="signup-box" class="signup-box visible widget-box no-border">
        <div class="widget-body">
            <div class="widget-main">
                <h4 class="header green lighter bigger">
                    <i class="icon-group blue"></i>
                    New User Registration
                </h4>

                <div class="space-6"></div>
                <p> Enter your details to begin: </p>

                <form action="{{ URL::action('UsersController@postRegister') }}" method="post">
                    <fieldset>
                        <label class="block clearfix">
                            <span class="block input-icon input-icon-right">
                                <input name="first_name" value="{{{ @$input['first_name'] }}}" type="text" class="form-control" placeholder="Name" />
                                <i class="icon-user"></i>
                            </span>
                        </label>

                        <label class="block clearfix">
                            <span class="block input-icon input-icon-right">
                                <input name="last_name" value="{{{ @$input['last_name'] }}}" type="text" class="form-control" placeholder="Last name" />
                                <i class="icon-user"></i>
                            </span>
                        </label>

                        <label class="block clearfix">
                            <span class="block input-icon input-icon-right">
                                <input name="email" value="{{{ @$input['email'] }}}" type="email" class="form-control" placeholder="Email" />
                                <i class="icon-envelope"></i>
                            </span>
                        </label>

                        <label class="block clearfix">
                            <span class="block input-icon input-icon-right">
                                <input name="password" type="password" class="form-control" placeholder="Password" />
                                <i class="icon-lock"></i>
                            </span>
                        </label>

                        <label class="block clearfix">
                            <span class="block input-icon input-icon-right">
                                <input name="confirm_password" type="password" class="form-control" placeholder="Repeat password" />
                                <i class="icon-retweet"></i>
                            </span>
                        </label>
                        <div class="space-24"></div>

                        <div class="clearfix">
                            <button type="reset" class="width-30 pull-left btn btn-sm">
                                <i class="icon-refresh"></i>
                                Reset
                            </button>

                            <button type="submit" class="width-65 pull-right btn btn-sm btn-success">
                                Register
                                <i class="icon-arrow-right icon-on-right"></i>
                            </button>
                        </div>
                    </fieldset>
                </form>
            </div>

            <div class="toolbar center">
                <a href="{{ URL::action('UsersController@getLogin') }}" class="back-to-login-link">
                    <i class="icon-arrow-left"></i>
                    Back to login
                </a>
            </div>
        </div><!-- /widget-body -->
    </div><!-- /signup-box -->

@stop