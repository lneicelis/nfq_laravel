@if(!empty($albums))
<div class="col-xs-12 no-padding-left">
    <div class="page-header">
        <h1>
            Search
            <small>
                <i class="icon-double-angle-right"></i>
                Showing albums for "{{{ $needle }}}"
            </small>
        </h1>
    </div><!-- /.page-header -->
    <ul class="ace-thumbnails">
        @foreach ($albums as $album)

        <li style="width: 200px; height: 200px">
            <a href="{{ URL::action('AlbumsController@show', array($album->id)) }}" title="{{ $album->title }}" >

                @if(!empty($album->file_name))
                <img alt="200x200" src="{{ URL::asset('public_gallery/thumbs/' . $album->file_name) }}" />
                @else
                <img alt="200x200" src="{{ URL::asset('public_gallery/thumbs/' . $default_cover) }}" />
                @endif

                <div class="tags">

                        <span class="label-holder">
                            <span class="label label-info arrowed"><b>{{ $album->title }}</b></span>
                        </span>

                        <span class="label-holder">
                            <span class="label label-warning">
                                {{ $album->no_photos }}
                                <i class="icon-picture"></i>
                            </span>
                        </span>

                        <span class="label-holder">
                            <span class="label label-success">
                                {{ $album->no_comments }}
                                <i class="icon-comments"></i>
                            </span>
                        </span>

                        <span class="label-holder">
                            <span class="label label-danger arrowed-in">
                                {{ $album->no_likes }}
                                <i class="icon-heart"></i>
                            </span>
                        </span>
                </div>
            </a>
        </li>

        @endforeach
    </ul>
</div>
<div class="col-xs-12 no-padding-left center">
    {{ $users->links() }}
</div>

@endif