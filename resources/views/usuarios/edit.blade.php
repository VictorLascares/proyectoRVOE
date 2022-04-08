@extends('layouts.layout')
@section('header')
    <x-bar />
    <x-navbar />
@endsection
@section('main-content')
<div class="container-sm">
    <div class="p-3">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title text-center text-uppercase">Actualizar Usuario</h1>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="name_id" name="nombre" value="{{ $user->nombres}}" placeholder="Nombres" >
                                <label for="name_id" class="control-label">Nombres</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="{{ $user->apellidos}}" placeholder="Apellidos">
                                <label for="street1_id" class="control-label">Apellidos</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="correo" name="correo" value="{{ $user->correo}}" placeholder="Correo Elctronico">
                                <label for="city_id" class="control-label">Correo electronico</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="tel" name="telefono" value="{{ $user->telefono}}" placeholder="Numero de Telefono">
                                <label for="city_id" class="control-label">Numero de Telefono</label>
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
                                <input type="password" class="form-control" id="password" name="contrasenia" value="{{ Hash::check($user->contrasenia)}}" placeholder="Confirmar Contraseña">
                                <label for="city_id" class="control-label">Contraseña</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Actualizar</button>
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