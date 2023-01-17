@extends('layouts.app')
@section('contenido')
    <div class="container mx-auto md:grid md:grid-cols-2 gap-10 mt-12 p-5 items-center">
        <div>
            <h1 class="text-green-800 font-black text-6xl">
                Iniciar Sesión y Administra las{{ ' ' }}
                <span class="text-black">Solicitudes</span>
            </h1>
        </div>
        <div class="mt-20 md:mt-5 shadow-lg px-5 py-10 rounded-xl bg-white">
            <form method="POST" action="{{ route('login') }}" novalidate>
                <div class="my-5">
                    @csrf
                    @if (session('mensaje'))
                        <p class="bg-red-600 text-white my-2 text-sm p-2 text-center">{{ session('mensaje') }}</p>
                    @endif
                    <div class="my-5">
                        <label for="email" class="block uppercase text-gray-600">Correo Electrónico</label>
                        <input type="email"
                            class="border-gray-200 p-3 mt-3 w-full bg-gray-50 rounded-xl focus:outline-none"
                            id="email" name="email" placeholder="correo@ejemplo.com">
                        @error('email')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="my-5">
                        <label for="password" class="block uppercase text-gray-500">Contraseña</label>
                        <input type="password"
                            class="border-gray-200 p-3 mt-3 w-full bg-gray-50 rounded-xl focus:outline-none"
                            name="password" id="password" placeholder="Contraseña">
                        @error('password')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="my-5 flex justify-between items-center">
                        <div>
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember" class="text-gray-500 text-sm">Mantener mi sesión abierta</label>
                        </div>
                        <a href="#" class="text-gray-500 text-sm underline hover:text-green-600">Olvidé mi contraseña</a>
                    </div>
                    <input
                        class="rounded-xl bg-green-900 hover:bg-green-700 transition-colors cursor-pointer uppercase font-bold w-full py-3 text-white"
                        type="submit" value="Iniciar Sesión">
                </div>
            </form>
        </div>
    </div>
@endsection
