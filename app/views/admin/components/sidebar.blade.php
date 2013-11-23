<div class="sidebar" id="sidebar">
    <div class="sidebar-shortcuts" id="sidebar-shortcuts">

    </div><!-- #sidebar-shortcuts -->

    <ul class="nav nav-list">
        <li>
            <a href="{{ URL::action('DashboardController@getIndex') }}">
                <i class="icon-dashboard"></i>
                <span class="menu-text"> Dashboard </span>
            </a>
        </li>

        <li>
            <a href="{{ URL::action('UsersController@getUsers') }}">
                <i class="icon-group"></i>
                <span class="menu-text"> Users </span>
            </a>
        </li>

        <li>
            <a href="{{ URL::action('AlbumsController@index') }}">
                <i class="icon-picture"></i>
                <span class="menu-text"> Gallery </span>
            </a>
        </li>
    </ul><!-- /.nav-list -->

    <div class="sidebar-collapse" id="sidebar-collapse">
        <i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
    </div>

    <script type="text/javascript">
        try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
    </script>
</div>
