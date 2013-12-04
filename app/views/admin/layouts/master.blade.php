<!-- Stored in app/views/layouts/frame.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Gallery - Ace Admin</title>

    <meta name="description" content="responsive photo gallery using colorbox" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="cache-control" content="no-store" />
    <meta http-equiv="cache-control" content="must-revalidate" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />

    <meta property="fb:app_id" content="{{ Setting::findSettings('gallery', 'facebook_app_id') }}" />
    <meta property="fb:admins" content="{{ Setting::findSettings('gallery', 'facebook_app_admins') }}" />

    @section('head-css')
        @include('admin.resources.css')
    @show

    @section('head-js')
        @include('admin.resources.javascript')
    @show


</head>

<body>
    <div class="loader-container">
        <div class="loader-curtain"></div>
        <div class="loader">
            <i class="icon-spinner icon-spin orange bigger-500"></i>
        </div>
    </div>

    <div class="navbar navbar-default" id="navbar">

		<div class="navbar-container" id="navbar-container">
			<div class="navbar-header pull-left">
				<a href="{{ URL::action('DashboardController@getHome') }}" class="navbar-brand">
					<small>
						<i class="icon-camera"></i>
						NFQ Gallery
					</small>
				</a><!-- /.brand -->
			</div><!-- /.navbar-header -->

			<div class="navbar-header pull-right" role="navigation">
				<ul class="nav ace-nav">
					<li class="light-blue">
						<a data-toggle="dropdown" href="#" class="dropdown-toggle">
							<img class="nav-user-photo" src="{{ URL::asset('public_users/thumbs/' . $thumb) }}" alt="" />
                                <span class="user-info">
                                    <small>Welcome,</small> {{ Sentry::getUser()->first_name }}
                                </span>
							<i class="icon-caret-down"></i>
						</a>
						<ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">

							<li>
								<a href="#">
									<i class="icon-user"></i>
									Profile
								</a>
							</li>

							<li class="divider"></li>

							<li>
								<a href="{{ URL::to('user/logout') }}">
									<i class="icon-off"></i>
									Logout
								</a>
							</li>
						</ul>
					</li>
					
				</ul><!-- /.ace-nav -->
				
			</div><!-- /.navbar-header -->
			
		</div><!-- /.container -->
		
	</div>

    <div class="main-container" id="main-container">

        <script type="text/javascript">
            try{ace.settings.check('main-container' , 'fixed')}catch(e){}
        </script>

        <div class="main-container-inner">

            <a class="menu-toggler" id="menu-toggler" href="#">
                <span class="menu-text"></span>
            </a>

            @include('admin.components.sidebar')

            <div class="main-content">
                <div class="breadcrumbs" id="breadcrumbs">
                    {{ Breadcrumbs::render() }}

                    <div class="nav-search" id="nav-search">
                        <form method="get" class="form-search" action="{{ URL::action('SearchController@getSearch') }}">
                            <span class="input-icon">
                                <input name="search" type="text" placeholder="Search ..." class="nav-search-input" autocomplete="off" value="{{{ Input::get('search') }}}">
                                <i class="icon-search nav-search-icon"></i>
                            </span>
                        </form>
                    </div>
                </div>

                <div class="page-content">

					@yield('page-header')

					<div class="row-fluid">

                        @if(!empty($alerts))
                            @include('admin.resources.alerts', array('alerts', $alerts))
                        @endif

						@yield('content')	
							
					</div>

                </div><!-- /.page-content -->
            </div><!-- /.main-content -->

        </div><!-- /.main-container-inner -->

        <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
            <i class="icon-double-angle-up icon-only bigger-110"></i>
        </a>
    </div><!-- /.main-container -->
    <div id="fb-root"></div>

    @include('admin.resources.js_bottom')
	@yield('scripts')

    @include('admin.resources.gritter')
    <script type="text/javascript">
        var queries = {{ json_encode(DB::getQueryLog()) }};
        console.log('/****************************** Database Queries ******************************/');
        console.log(' ');
        queries.forEach(function(query) {
            console.log('   ' + query.time + ' | ' + query.query + ' | ' + query.bindings[0]);
        });
        console.log(' ');
        console.log('/****************************** End Queries ***********************************/');
    </script>
</body>
</html>
