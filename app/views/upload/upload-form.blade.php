@extends('layouts.master')

@section('sidebar')
    @parent

    <p>Hello, {{ @$user }}</p>
@stop

@section('content')
    <form method="POST" enctype="multipart/form-data">
        <input id="userfile" name="userfile" type="file" />
        <button>Add</button>
    </form>
@stop