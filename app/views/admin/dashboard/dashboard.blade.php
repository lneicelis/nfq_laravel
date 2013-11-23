@extends('admin.layouts.master')

@section('content')

    <div class="col-sm-5 infobox-container">

        <div class="widget-box">

            <div class="widget-header">
                <h4 class="lighter smaller pull-left">
                    <i class="icon-group blue"></i>
                    Users
                </h4>
            </div>

            <div class="widget-body">

                <div class="infobox infobox-green  ">
                    <div class="infobox-icon">
                        <i class="icon-group"></i>
                    </div>

                    <div class="infobox-data">
                        <span class="infobox-data-number">{{ count($users) }}</span>
                        <div class="infobox-content">Total users</div>
                    </div>
                </div>

                <div class="infobox infobox-blue  ">
                    <div class="infobox-icon">
                        <i class="icon-key"></i>
                    </div>

                    <div class="infobox-data">
                        <span class="infobox-data-number">X</span>
                        <div class="infobox-content">Admins</div>
                    </div>
                </div>

                <div class="space-6"></div>
            </div>

        </div>

    </div>

    <div class="col-sm-7 infobox-container">
        <div class="widget-box">
            <div class="widget-header">
                <h4 class="lighter smaller pull-left">
                    <i class="icon-camera blue"></i>
                    Gallery
                </h4>
            </div>

            <div class="widget-body">

                <div class="infobox infobox-green  ">
                    <div class="infobox-icon">
                        <i class="icon-folder-open"></i>
                    </div>

                    <div class="infobox-data">
                        <span class="infobox-data-number">{{ count($albums) }}</span>
                        <div class="infobox-content">Albums</div>
                    </div>
                </div>

                <div class="infobox infobox-blue  ">
                    <div class="infobox-icon">
                        <i class="icon-picture"></i>
                    </div>

                    <div class="infobox-data">
                        <span class="infobox-data-number">{{ count($photos) }}</span>
                        <div class="infobox-content">Photos</div>
                    </div>
                </div>

                <div class="infobox infobox-pink  ">
                    <div class="infobox-icon">
                        <i class="icon-tags"></i>
                    </div>

                    <div class="infobox-data">
                        <span class="infobox-data-number">{{ count($photo_tags) }}</span>
                        <div class="infobox-content">Tags</div>
                    </div>
                </div>

                <div class="infobox infobox-red  ">
                    <div class="infobox-icon">
                        <i class="icon-comments"></i>
                    </div>

                    <div class="infobox-data">
                        <span class="infobox-data-number">7</span>
                        <div class="infobox-content">Comments</div>
                    </div>
                </div>

                <div class="infobox infobox-orange2  ">
                    <div class="infobox-icon">
                        <i class="icon-facebook"></i>
                    </div>
                    <div class="infobox-data">
                        <span class="infobox-data-number">7</span>
                        <div class="infobox-content">Likes</div>
                    </div>

                </div>

                <div class="infobox infobox-blue2  ">
                    <div class="infobox-progress">
                        <div class="easy-pie-chart percentage" data-percent="42" data-size="46">
                            <span class="percent">42</span>%
                        </div>
                    </div>

                    <div class="infobox-data">
                        <span class="infobox-text">traffic used</span>

                        <div class="infobox-content">
                            <span class="bigger-110">~</span>
                            58GB remaining
                        </div>
                    </div>
                </div>

                <div class="space-6"></div>

            </div>
        </div>
    </div>
@stop