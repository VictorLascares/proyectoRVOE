@extends('layouts.layout')
@section('header')
  <x-bar />
  <x-navbar />
@endsection
@section('main-content')
  <div class="container mb-5 mt-4">
    <form class="row g-2 mb-5">
      <div class="col-md-6">
        <div class="form-floating mb-2">
          <input type="text" class="form-control" id="institutionName" placeholder="Nombre de la Institución" value="{{$institution->nombre}}">
          <label for="institutionName">Nombre de la Institución</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-floating">
          <input type="text" class="form-control" id="institutionName" placeholder="Nombre del Director" value="{{$institution->director}}">
          <label for="institutionName">Nombre del Director</label>
        </div>
      </div>
      <div class="col-12">
        <div class="input-group form-group">
          <input type="file" class="form-control" id="institutionLogo" name="logotipo">
        </div>
      </div>
      <div class="d-flex justify-content-center gap-2">
        <form action="" class="mb-2 d-flex justify-content-end">
          <button class="btn btn-danger" type="submit">
            <i class="bi bi-trash"></i>
            Eliminar
          </button>
        </form>
        <button class="btn btn-success" type="submit">
          <i class="bi bi-arrow-repeat"></i>
          Actualizar
        </button>
      </div>
    </form>

    <section>
      <div class="d-flex justify-content-between align-items-center">
        <h2 class="">Carreras</h2>
        <form action="">
          <a data-bs-target="#loginModal" data-bs-toggle="modal" type="submit" class="btn btn-success">Nueva Carrera</a>
        </form>
      </div>

      <div class="list-group mt-3">
        @foreach ($careers as $career)
          <a href="" class="d-flex justify-content-between text-decoration-none text-dark list-group-item list-group-item-action align-items-center">
            <p class="m-0">{{$career->nombre}}</p>
            <p class="m-0">{{$career->titulo}}</p>
          </a>
        @endforeach
      </div>

      <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header text-center">
              <h5 class="modal-title text-uppercase w-100" id="exampleModalLabel">Nueva Carrera</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form class="mb-2" method="POST" action="{{ route('careers.store',$institution->id) }}">
                @csrf
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" id="carreerName" name="nombre" placeholder="Nombre de la Carrera">
                  <label for="careerName">Nombre de la Carrera</label>
                </div>
                <div class="form-floating">
                  <input type="text" class="form-control" name="titulo" id="careerTitle"
                    placeholder="Contraseña">
                  <label for="careerTitle">Titulo de la Carrera</label>
                </div>
                <input type="hidden" name="institution_id" value="{{$institution->id}}">
                <div class="d-grid mt-4">
                  <button class="btn btn-success text-uppercase" type="submit">Agregar</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection
@section('footer')
  <x-footer />
@endsection
