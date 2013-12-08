@extends('admin.layouts.master')

@section('head-css')
@parent

@stop

@section('content')

    @include('admin.search.photos')
    @include('admin.search.albums')
    @include('admin.search.users')

@stop