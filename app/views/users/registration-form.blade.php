@extends('layouts.master')

@section('sidebar')
    <h1>Please register</h1>
    <p>{{ @$message }}</p>
@stop

@section('content')
    <form method="POST" id="registration_form">
        <label for="email">Email</label><input id="email" name="email" value="{{ @$email }}" type="text" />
        <label for="password">Password</label><input id="password" name="password" type="password" />
        <button>Register</button>
    </form>
@stop