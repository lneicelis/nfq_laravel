@extends('admin.layouts.login')

@section('content')

<div id="signup-box" class="signup-box visible widget-box no-border">
    <div class="widget-body">
        <div class="widget-main">
            <h4 class="header green lighter bigger">
                <i class="icon-group blue"></i>
                Change password
            </h4>

            <div class="space-6"></div>
            <p> Enter new password: </p>

            <form method="post">
                <fieldset>
                    {{ Form::token() }}
                    <label class="block clearfix">
                            <span class="block input-icon input-icon-right">
                                <input name="password" type="password" class="form-control" placeholder="New password" />
                                <i class="icon-lock"></i>
                            </span>
                    </label>

                    <label class="block clearfix">
                            <span class="block input-icon input-icon-right">
                                <input name="confirm_password" type="password" class="form-control" placeholder="Repeat new password" />
                                <i class="icon-retweet"></i>
                            </span>
                    </label>

                    <div class="space-24"></div>

                    <div class="clearfix">

                        <button type="submit" class="width-65 pull-right btn btn-sm btn-success">
                            Change password
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