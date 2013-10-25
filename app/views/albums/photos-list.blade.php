@extends('layouts.master')

@section('sidebar')

    @foreach ($message as $msg)

        <p>{{ $msg }}</p>

    @endforeach

@stop

@section('content')
    <p><a href="{{ URL::to('gallery')}}">Back to the gallery</a></p>
    <p><a href="{{ URL::to('upload/' . $album_id) }}">Upload new photos</a></p>
    @foreach ($photos as $photo)

        <p><img src="{{ URL::to('thumbs'); }}/{{ $photo->file_name }}" /></p>

    @endforeach

@stop