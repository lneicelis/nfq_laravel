@extends('layouts.master')

@section('sidebar')
    <p>{{ @$message }}</p>
@stop

@section('content')
<form method="POST" id="reset_password_form">
    <label for="password">New password</label>
    <input id="password" name="password" type="password" />

    <label for="password_repeat">Repeat new password</label>
    <input id="password_repeat" name="password_repeat" type="password" />

    <button>Reset</button>
</form>
@stop