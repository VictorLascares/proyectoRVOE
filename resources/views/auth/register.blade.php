@extends('layouts.app')
@section('titulo')
    Registro de Usuarios
@endsection
@section('contenido')
<div class="md:flex md:justify-center px-4 my-20">
    <div class="md:w-10/12">
        <form method="POST" action="{{ route('users.store') }}" novalidate>
            @csrf
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="mb-2 block uppercase text-gray-500 font-bold">Nombre</label>
                    <input type="text" class="w-full rounded-lg border p-3  @error('name') border-red-600 @enderror" id="name" name="name" placeholder="Nombre">
                    @error('name')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email" class="mb-2 block uppercase text-gray-500 font-bold">Correo electronico</label>
                    <input type="email" class="w-full rounded-lg border p-3  @error('email') border-red-600 @enderror" id="email" name="email" placeholder="Correo Electronico">
                    @error('email')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="telefono" class="mb-2 block uppercase text-gray-500 font-bold">Numero de Telefono</label>
                    <input type="text" name="telefono" class="w-full rounded-lg border p-3  @error('telefono') border-red-600 @enderror" id="telefono" placeholder="Número de telefono">
                    @error('telefono')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="tipoUsuario" class="mb-2 block uppercase text-gray-500 font-bold">Tipo de Usuario</label>
                    <select id="tipoUsuario" class="w-full rounded-lg border p-3  @error('tipoUsuario') border-red-600 @enderror" name="tipoUsuario">
                        <option selected disabled>-- Seleccione el tipo de usuario --</option>
                        <option value="administrador">Administrador</option>
                        <option value="planeacion">Planeación</option>
                        <option value="direccion">Dirección</option>
                    </select>
                    @error('tipoUsuario')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password" class="mb-2 block uppercase text-gray-500 font-bold">Contraseña</label>
                    <input type="password" class="w-full rounded-lg border p-3  @error('password') border-red-600 @enderror" id="password" name="password" placeholder="Contraseña">
                    @error('password')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-2 block uppercase text-gray-500 font-bold">
                      Confirmar Contraseña
                    </label>
                    <input 
                      type="password"
                      name="password_confirmation"
                      id="password_confirmation"
                      placeholder="Confirmar contraseña"
                      class="border rounded-lg p-3 w-full "
                    >
                </div>
            </div>
    
            <div class="flex justify-end mt-4">
                <button type="submit" class="rounded-lg bg-green-900 hover:bg-green-700 p-3 text-white  transition-colors w-full md:w-auto">Crear Usuario</button>
            </div>
        </form>
    </div>
</div>
@endsection