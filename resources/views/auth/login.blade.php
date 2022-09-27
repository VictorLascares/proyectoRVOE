@extends('layouts.app')
@section('titulo')
    Iniciar Sesión
@endsection
@section('contenido')
    <form class="max-w-md mx-auto my-10" method="POST" action="{{ route('login') }}" novalidate>
        @csrf
        @if (session('mensaje'))
            <p class="bg-red-600 text-white my-2 text-sm p-2 text-center">{{ session('mensaje') }}</p>
        @endif
        <div class="mb-5">
            <label for="email" class="mb-2 block uppercase text-gray-500">Correo Electrónico</label>
            <input type="email" class="border p-3 w-full @error('email') border-red-600 @enderror" id="email" name="email" placeholder="name@example.com">

           @error('email')
                <p class="text-red-600 text-sm">{{ $message }}</p>
           @enderror
        </div>
        <div class="mb-5">
            <label for="password" class="mb-2 block uppercase text-gray-500">Contraseña</label>
            <input type="password" class="border p-3 w-full @error('password') border-red-600 @enderror" name="password" id="password"
            placeholder="Contraseña">

            @error('password')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-5">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember" class="text-gray-500 text-sm">Mantener mi sesión abierta</label>
        </div>
        <input class="bg-[#13322B] hover:bg-[#0C231E] transition-colors cursor-pointer uppercase font-bold w-full p-3 text-white" type="submit" value="Iniciar Sesión">
    </form>
@endsection
