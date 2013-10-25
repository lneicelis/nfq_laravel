@extends('layouts.master')

@section('sidebar')
    @foreach ($message as $msg)
        <p>{{ $msg }}</p>
    @endforeach
@stop

@section('content')
<form method="POST" id="new_album">
    <label for="title">Title</label>
    <input id="title" name="title" type="text" />
    <button>Create new album</button>
</form>
@stop