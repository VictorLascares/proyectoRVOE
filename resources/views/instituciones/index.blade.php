@extends('layouts.app')
@section('titulo')
  Instituciones
  <span class="text-gray-500">registradas</span>
@endsection
@section('contenido')
    <div class="flex justify-end items-center mt-4">
        @if (Auth::user()->typeOfUser != 'direccion')
            <a href="{{route('institutions.create')}}" class="bg-green-900 hover:bg-green-700 rounded-lg text-white p-3">Nueva Institución</a>
        @endif
    </div>
    @if (count($institutions) != 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4 mt-10">
            @foreach ($institutions as $institution)
                <a class="text-decoration-none institution text-dark relative border" href="{{ route('institutions.show', $institution) }}">
                <div class="p-5 flex justify-center items-center">
                    <img src="{{ $institution->logo }}" alt="Logo de la Institución">
                </div>
                <div class="absolute top-0 institution__overlay flex flex-col justify-center items-center p-2 gap-4">
                    <p class="text-lg font-bold uppercase text-center text-gray-400">{{ $institution->name }}</p>
                    <p class="text-sm text-center text-gray-400">Titular: {{ $institution->owner }}</p>
                    <p class="text-sm text-center text-gray-400">Correo: {{ $institution->email }}</p>
                </div>
                </a>
            @endforeach
        </div>
        <div class="my-10">{{ $institutions->links('pagination::tailwind') }}</div>
    @else
        <p class="text-gray-600 text-xl text-center font-bold mt-10">Todavia no hay instituciones</p>
    @endif
@endsection
