@extends('layouts.master')

@section('content')

    @foreach ($albums as $album)

        <p> <a href="{{ URL::to('album/' . $album->id) }}">{{ $album->title }}</a></p>

    @endforeach

@stop