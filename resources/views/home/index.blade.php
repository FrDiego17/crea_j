@extends('layouts.app-master')

@section('content')

    <h1>Hola</h1>

    @auth
        <p>Autenticado </p>
        
    @endauth

    @guest
        <p>No autenticado <a href="/login">Re</a></p>
    @endguest
@endsection


