@extends('layouts.master')

@section('content')
    <p><a href="{{ URL::to('gallery') }}">Back to the gallery</a></p>

    <form method="POST" id="new_album">
        <label for="title">Title</label>
        <input id="title" name="title" type="text" />
        <button>Create new album</button>
    </form>
@stop