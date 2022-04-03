@extends('layouts.layout')
@section('header')
    <x-bar />
    <x-navbar />
@endsection
@section('main-content')
<div class="container-sm p-3 my-5">
    <div class="p-3">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title text-center text-uppercase">Nuevo Usuario</h1>
            </div>
            <div class="card-body">
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="name_id" name="nombre" value="" placeholder="Nombres" >
                                <label for="name_id" class="control-label">Nombres</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="" placeholder="Apellidos">
                                <label for="street1_id" class="control-label">Apellidos</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="correo" name="correo" value="" placeholder="Correo Elctronico">
                                <label for="city_id" class="control-label">Correo electronico</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="tel" name="telefono" value="" placeholder="Numero de Telefono">
                                <label for="city_id" class="control-label">Numero de Telefono</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select id="inputState" class="form-control text-center">
                                    <option selected disabled>-- Seleccione el tipo de usuario --</option>
                                </select>
                                <label for="inputState" class="form-label">Tipo de Usuario</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="tel" name="telefono" value="" placeholder="Contraseña">
                                <label for="city_id" class="control-label">Contraseña</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="tel" name="telefono" value="" placeholder="Confirmar Contraseña">
                                <label for="city_id" class="control-label">Confirmar Contraseña</label>
                            </div>
                        </div>
                        
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Crear Usuario</button>
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