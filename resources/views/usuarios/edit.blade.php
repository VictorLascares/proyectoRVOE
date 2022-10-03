@extends('layouts.app')
@section('titulo')
  Actualizar Usuario
@endsection
@section('contenido')
<div class="md:flex md:justify-center px-4 my-20">
    <div class="md:w-10/12">
        <form method="POST" action="{{ route('users.update', $user->id) }}" novalidate>
            @csrf
            @method('PUT')
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="mb-2 block uppercase text-gray-500 font-bold">Nombre</label>
                    <input type="text" class="w-full rounded-lg border p-3 @error('name') border-red-600 @enderror" id="name" name="name" placeholder="Nombre" value="{{$user->name}}">
                    @error('name')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email" class="mb-2 block uppercase text-gray-500 font-bold">Correo electronico</label>
                    <input type="email" class="w-full rounded-lg border p-3 @error('email') border-red-600 @enderror" id="email" name="email" placeholder="Correo Electronico" value="{{$user->email}}">
                    @error('email')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="telefono" class="mb-2 block uppercase text-gray-500 font-bold">Numero de Telefono</label>
                    <input type="text" name="telefono" class="w-full rounded-lg border p-3 @error('telefono') border-red-600 @enderror" id="telefono" value="{{$user->telefono}}">
                    @error('telefono')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="tipoUsuario" class="mb-2 block uppercase text-gray-500 font-bold">Tipo de Usuario</label>
                    <select id="tipoUsuario" class="w-full rounded-lg border p-3 @error('tipoUsuario') border-red-600 @enderror" name="tipoUsuario">
                        <option selected disabled>-- Seleccione el tipo de usuario --</option>
                        <option value="administrador" @if ($user->tipoUsuario =='administrador') selected @endif>Administrador</option>
                        <option value="planeacion" @if ($user->tipoUsuario =='planeacion') selected @endif>Planeación</option>
                        <option value="direccion" @if ($user->tipoUsuario =='direccion') selected @endif>Dirección</option>
                    </select>
                    @error('tipoUsuario')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="flex justify-end mt-4">
                <button type="submit" class="bg-[#13322B] hover:bg-[#0C231E] rounded-lg p-3 text-white transition-colors w-full md:w-auto">Actualizar</button>
            </div>
        </form>
        
        <form action="{{ route('users.updatePSW', $user->id) }}" method="POST" class="mt-20">
            @csrf
            @method('PUT')
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="mb-2 block uppercase text-gray-500 font-bold">Contraseña</label>
                    <input type="password" class="w-full rounded-lg border p-3 @error('password') border-red-600 @enderror" id="password" name="password" placeholder="Contraseña">
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
                      class="rounded-lg border p-3 w-full"
                    >
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <button type="submit" class="bg-[#13322B] hover:bg-[#0C231E] rounded-lg p-3 text-white transition-colors w-full md:w-auto">Actualizar Contraseña</button>
            </div>
        </form>
    </div>
</div>
@endsection

{{-- @section('script')
  <script>
    const passwordInput = document.querySelector('#password')
    const confirmedPswInput = document.querySelector('#confirmedPassword')
    const btnPsw = document.querySelector('#btn-change-psw')
    const form = document.querySelector('#changePsw')

    initListeners()

    function initListeners() {
      form.addEventListener('submit', changePassword)
    }

    function changePassword(e) {
      if (passwordInput.value != confirmedPswInput.value) {
        e.preventDefault()
        confirmedPswInput.classList.add('is-invalid')
        passwordInput.classList.add('is-invalid')
      }
    }
  </script>
@endsection --}}
