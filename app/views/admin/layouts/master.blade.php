<!-- Stored in app/views/layouts/frame.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Gallery - Ace Admin</title>

    <meta name="description" content="responsive photo gallery using colorbox" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    @section('head-css')
        @include('admin.resources.css')
    @show

    @section('head-js')
        @include('admin.resources.javascript')
    @show


</head>

<body>
    <div class="navbar navbar-default" id="navbar">

		<div class="navbar-container" id="navbar-container">
			<div class="navbar-header pull-left">
				<a href="{{ URL::to('/') }}" class="navbar-brand">
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
							<img class="nav-user-photo" src="{{ URL::asset('assets/avatars/user.jpg') }}" alt="Jason's Photo" />
                                <span class="user-info">
                                    <small>Welcome,</small>
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

            <div class="main-content" style="margin-left: 0px;">
                <div class="breadcrumbs" id="breadcrumbs">
                    <ul class="breadcrumb">
                        <li>
                            <i class="icon-home home-icon"></i>
                            <a href="{{ URL::to('/') }}">Home</a>
                        </li>
                        @if(!empty($crumb))
                            @foreach($crumb as $href)
                                <li>
                                    <a href="{{ $href['url'] }}">{{ $href['title'] }}</a>
                                </li>
                            @endforeach
                        @endif
                    </ul><!-- .breadcrumb -->
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

    @include('admin.resources.js_bottom')
    @include('admin.resources.gritter')

	@yield('scripts')

</body>
</html>
