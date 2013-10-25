@extends('layouts.master')

@section('sidebar')
    @parent

    <p>Hello, {{ @$message }}</p>
@stop

@section('content')
    <p><a href="{{ URL::to('album/' . $album_id) }}">Go back to the album</a></p>
    <form method="POST" enctype="multipart/form-data">
        <input id="userfile" name="userfile" type="file" />
        <button>Add</button>
    </form>
@stop