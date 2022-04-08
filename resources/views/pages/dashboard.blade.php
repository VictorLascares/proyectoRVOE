@extends('layouts.layout')
@section('header')
    <x-bar />
    <x-navbar />
@endsection
@section('main-content')
    <div class="new-request">
        <img src="{{ asset('img/nuevo.svg')}}" alt="Icono de nuevo">
    </div>
@endsection
@section('footer')
    <x-footer />
@endsection