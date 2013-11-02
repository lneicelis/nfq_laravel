@extends('layouts.master')

@section('sidebar')
    <p>{{ @$message }}</p>
@stop

@section('content')
    <a href="{{ $logout_url }}">Logout</a>
@stop