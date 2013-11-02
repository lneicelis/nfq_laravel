@extends('admin.layouts.login')

@section('content')

    <div id="forgot-box" class="forgot-box visible widget-box no-border">
        <div class="widget-body">
            <div class="widget-main">
                <h4 class="header red lighter bigger">
                    <i class="icon-key"></i>
                    Retrieve Password
                </h4>

                <div class="space-6"></div>
                <p>
                    Enter your email and to receive instructions
                </p>

                <form action="{{ URL::action('UsersController@postResetPassword') }}" method="post">
                    <fieldset>
                        <label class="block clearfix">
                            <span class="block input-icon input-icon-right">
                                <input name="email" type="email" class="form-control" placeholder="Email" />
                                <i class="icon-envelope"></i>
                            </span>
                        </label>

                        <div class="clearfix">
                            <button type="submit" class="width-35 pull-right btn btn-sm btn-danger">
                                <i class="icon-lightbulb"></i>
                                Send Me!
                            </button>
                        </div>
                    </fieldset>
                </form>
            </div><!-- /widget-main -->

            <div class="toolbar center">
                <a href="{{ URL::action('UsersController@getLogin') }}" class="back-to-login-link">
                    Back to login
                    <i class="icon-arrow-right"></i>
                </a>
            </div>
        </div><!-- /widget-body -->
    </div><!-- /forgot-box -->

@stop