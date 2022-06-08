@extends('layouts.layout')
@section('header')
  <x-bar />
  <x-navbar />
@endsection
@section('main-content')
  <div class="container-sm py-4 mb-5">
    <h1 class="text-center text-uppercase mb-4">Nuevo Usuario</h1>
    <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="row">
        <div class="col-md-6">
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="inputNames" name="nombres" placeholder="Nombres" required>
            <label for="inputNames" class="control-label">Nombres</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="inputSurnames" name="apellidos" placeholder="Apellidos" required>
            <label for="inputSurnames" class="control-label">Apellidos</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating mb-3">
            <input type="email" class="form-control" id="email" name="correo" placeholder="Correo Elctronico" required>
            <label for="email" class="control-label">Correo electronico</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="phone" name="telefono" placeholder="Numero de Telefono"
              required>
            <label for="phone" class="control-label">Numero de Telefono</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating mb-3">
            <select id="inputState" class="form-control text-center" name="tipoUsuario" required>
              <option selected disabled>-- Seleccione el tipo de usuario --</option>
              <option value="administrador">Administrador</option>
              <option value="planeacion">Planeación</option>
              <option value="direccion">Dirección</option>
            </select>
            <label for="inputState" class="form-label">Tipo de Usuario</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating mb-3">
            <input type="password" class="form-control" id="password" name="contrasenia"
              placeholder="Confirmar Contraseña" required onkeyup='checkPassword();'>
            <label for="password" class="control-label">Contraseña</label>
          </div>
        </div>

      </div>
      <div class="d-flex justify-content-end">
        <button type="submit" class="btn boton-green text-light">Crear Usuario</button>
      </div>
    </form>
  </div>
@endsection
@section('footer')
  <x-footer />
@endsection
