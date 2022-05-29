@extends('layouts.layout')
@section('header')
  <x-bar />
  <x-navbar />
@endsection
@section('main-content')
  <div class="container-sm">
    <div class="p-3">
      <h1 class="text-center text-uppercase mb-3">Actualizar Usuario</h1>
      <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
          <div class="col-md-6">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="name_id" name="nombre" value="{{ $user->nombres }}"
                placeholder="Nombres">
              <label for="name_id" class="control-label">Nombres</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno"
                value="{{ $user->apellidos }}" placeholder="Apellidos">
              <label for="street1_id" class="control-label">Apellidos</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating mb-3">
              <select id="inputState" class="form-control" name="tipoUsuario" required>
                <option selected disabled>-- Seleccione el tipo de usuario --</option>
                <option value="administrador" @if ($user->tipoUsuario == 'administrador') selected @endif>Administrador</option>
                <option value="planeacion" @if ($user->tipoUsuario == 'planeacion') selected @endif>Planeación</option>
                <option value="direccion" @if ($user->tipoUsuario == 'direccion') selected @endif>Dirección</option>
              </select>
              <label for="inputState" class="form-label">Tipo de Usuario</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="tel" name="telefono" value="{{ $user->telefono }}"
                placeholder="Numero de Telefono">
              <label for="city_id" class="control-label">Numero de Telefono</label>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="correo" name="correo" value="{{ $user->correo }}"
              placeholder="Correo Elctronico">
            <label for="city_id" class="control-label">Correo electronico</label>
          </div>
        </div>
        <div class="d-flex justify-content-end gap-2">
          <button type="button" class="btn boton-green text-light" data-bs-target="#updateKeyModal"
            data-bs-toggle="modal">
            <i class="bi bi-key"></i>
          </button>
          <button type="submit" class="btn boton-green text-light">Actualizar</button>
        </div>
      </form>
    </div>
  </div>
  <div class="modal fade" id="updateKeyModal" tabindex="-1" aria-labelledby="updateKeyLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header text-center">
          <h5 class="modal-title text-uppercase w-100" id="docsModalLabel">Actualizar Contraseña</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="changePsw" class="mb-2" method="POST" action="{{ url('user/update', $user->id) }}">
            @method('PUT')
            @csrf
            <div class="form-floating mb-3">
              <input type="password" class="form-control" id="password" placeholder="Password" required>
              <label for="password">Nueva Contraseña</label>
            </div>
            <div class="form-floating mb-4">
              <input type="password" aria-describedby="validationPasswordFeedback" name="contrasenia" class="form-control" id="confirmedPassword" placeholder="Password" required>
              <label for="confirmedPassword">Confirmar Contraseña</label>
              <div id="validationPasswordFeedback" class="invalid-feedback">
                Las contraseñas no coinciden
              </div>
            </div>
            <div class="d-grid col-6 mx-auto">
              <button id="btn-change-psw" class="btn boton-green text-light" type="submit">Actualizar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('footer')
  <x-footer />
@endsection
@section('script')
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
@endsection
