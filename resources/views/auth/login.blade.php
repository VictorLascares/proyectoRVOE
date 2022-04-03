@extends('layouts.layout')
@section('main-content')
<div class="vh-100 row g-0">
    <div class="h-100 col-lg-6">
        <div class="py-0 h-100 card-body d-flex flex-column justify-content-around">
            <img src="{{ asset('img/logo_sep.svg')}}" style="width: 250px" alt="Logo Secretaria de Educación Pública">
            <form class="mb-2">
                <h4 class=" text-center pb-1 text-uppercase">Iniciar Sesión</h4>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                    <label for="floatingInput">Correo Electrónico</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPassword" placeholder="Contraseña">
                    <label for="floatingPassword">Contraseña</label>
                </div>
                <div class="d-grid mt-4">
                    <button class="btn btn-success text-uppercase" type="button">Iniciar Sesión</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-lg-6 d-flex align-items-center gradient-custom-2 bg-success">
        <div class="mx-auto py-4 px-2">
            <img class="img-fluid" src="{{ asset('img/Logo_gob_mx.svg')}}" style="width: 250px" alt="Logo gobierno de México">
        </div>
    </div>
</div>
@endsection 

