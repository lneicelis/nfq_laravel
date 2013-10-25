@extends('layouts.master')

@section('sidebar')
    <p>{{ @$message }}</p>
@stop

@section('content')
<form method="POST" id="reset_password_form">
    <label for="email">Email</label>
    <input id="email" name="email" type="text" />
    <button>Reset</button>
</form>
@stop