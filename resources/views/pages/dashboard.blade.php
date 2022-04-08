@extends('layouts.layout')
@section('header')
    <x-bar />
    <x-navbar />
@endsection
@section('main-content')
    <div class="new-request">
        @php
            var_dump($nombre)
        @endphp
        <img src="{{ asset('img/nuevo.svg')}}" alt="Icono de nuevo">
    </div>
@endsection
@section('footer')
    <x-footer />
@endsection