@extends('layouts.app')
@section('titulo')
    Registro de Usuarios
@endsection
@section('contenido')
<div class="md:flex md:justify-center px-4">
    <div class="md:w-10/12">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label for="inputNames" class="mb-2 block uppercase text-gray-500 font-bold">Nombres</label>
                    <input type="text" class="w-full border p-3 rounded-lg" id="inputNames" name="nombres" placeholder="Nombres" required>
                </div>
                <div>
                    <label for="inputSurnames" class="mb-2 block uppercase text-gray-500 font-bold">Apellidos</label>
                    <input type="text" class="w-full border p-3 rounded-lg" id="inputSurnames" name="apellidos" placeholder="Apellidos" required>
                </div>
                <div>
                    <label for="email" class="mb-2 block uppercase text-gray-500 font-bold">Correo electronico</label>
                    <input type="email" class="w-full border p-3 rounded-lg" id="email" name="correo" placeholder="Correo Elctronico" required>
                </div>
                <div>
                    <label for="phone" class="mb-2 block uppercase text-gray-500 font-bold">Numero de Telefono</label>
                    <input type="text" class="w-full border p-3 rounded-lg" id="phone" name="telefono" placeholder="Numero de Telefono"
                    required>
                </div>
                <div>
                    <label for="inputState" class="mb-2 block uppercase text-gray-500 font-bold">Tipo de Usuario</label>
                    <select id="inputState" class="w-full border p-3 rounded-lg" name="tipoUsuario" required>
                        <option selected disabled>-- Seleccione el tipo de usuario --</option>
                        <option value="administrador">Administrador</option>
                        <option value="planeacion">Planeación</option>
                        <option value="direccion">Dirección</option>
                    </select>
                </div>
                <div>
                    <label for="password" class="mb-2 block uppercase text-gray-500 font-bold">Contraseña</label>
                    <input type="password" class="w-full border p-3 rounded-lg" id="password" name="contrasenia" placeholder="Confirmar Contraseña" required>
                </div>
            </div>
    
            <div class="flex justify-end mt-4">
                <button type="submit" class="bg-[#13322B] hover:bg-[#0C231E] p-3 text-white rounded-lg transition-colors w-full md:w-auto">Crear Usuario</button>
            </div>
        </form>
    </div>
</div>
@endsection