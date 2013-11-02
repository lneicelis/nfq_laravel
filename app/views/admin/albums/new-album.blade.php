@extends('admin.layouts.master')

@section('content')

    <form method="POST" action="{{ URL::action('AlbumsController@postNewAlbum') }}">
        {{ Form::token() }}
        <label for="title">Title</label>
        <input id="title" name="title" type="text" />
        <button>Create new album</button>
    </form>
@stop