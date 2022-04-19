@extends('layouts.layout')
@section('header')
  <x-bar />
  <x-navbar />
@endsection
@section('main-content')
  <div class="container mb-5 mt-4">
    <div class="d-flex align-items-center justify-content-end gap-2 mb-5">
      <form method="POST" action="{{ route('careers.destroy', $career->id) }}" class="d-flex justify-content-end">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger" type="submit">
          <i class="bi bi-trash"></i>
          Eliminar
        </button>
      </form>
    </div>
    <form class="row g-2 mb-3" method="POST" action="{{ route('careers.update', $career->id) }}">
      @csrf
      @method('PUT')
      <div class="col-md-6">
        <div class="form-floating mb-2">
          <input name="nombre" type="text" class="form-control" id="careerName" placeholder="Nombre de la Carrera"
            value="{{ $career->nombre }}">
          <label for="careerName">Nombre de la Carrera</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-floating">
          <input name="titulo" type="text" class="form-control" id="careerTitle" placeholder="Titulo de la Carrera"
            value="{{ $career->titulo }}">
          <label for="institutionName">Titulo de la Carrera</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-floating mb-3">
          <select id="careerModality" class="form-control" name="modalidad" required>
            <option selected disabled>-- Seleccione la Modalidad --</option>
            <option value="Presencial">Presencial</option>
            <option value="Distancia">Distancia</option>
            <option value="Hibrida">Hibrida</option>
          </select>
          <label for="careerModality" class="form-label">Modalidad de la Carrera</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-floating">
          <input type="number" class="form-control" name="duracion" value="{{ $career->duracion }}"
            id="careerDuration" placeholder="Duración de la Carrera">
          <label for="careerDuration">Duración de la Carrera</label>
        </div>
      </div>
      <div class="d-flex justify-content-center align-items-center">
        <button class="btn btn-success" type="submit">
          <i class="bi bi-arrow-repeat"></i>
          Actualizar
        </button>
      </div>
    </form>

    <section>
      <div class="d-flex justify-content-between align-items-center">
        <h2 class="">Requisiciones</h2>
        <button type="button" data-bs-target="#careersModal" data-bs-toggle="modal" type="submit" class="btn btn-success">
          <i class="bi bi-plus-circle"></i>
          Nueva Requisición
        </button>
      </div>

      <table class="table">
        <thead>
          <tr>
            
          </tr>
        </thead>        
        @foreach ($requisitions as $requisition)
          <a href=""
            class="d-flex justify-content-between text-decoration-none text-dark list-group-item list-group-item-action align-items-center">
            <p class="m-0"></p>
            <p class="m-0"></p>
          </a>
        @endforeach
      </table>

      <div class="modal fade" id="careersModal" tabindex="-1" aria-labelledby="careersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header text-center">
              <h5 class="modal-title text-uppercase w-100" id="careersModalLabel">Nueva Requisición</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form class="mb-2" method="POST" action="{{ url('requisition/create',$career->id)}}">
                @csrf
                <div class="form-floating mb-3">
                  <select id="requisitionGoal" class="form-control" name="meta" required>
                    <option selected disabled>-- Seleccione la Meta --</option>
                    <option value="solicitud">Solicitud</option>
                    <option value="domicilio">Domicilio</option>
                    <option value="planEstudios">Plan de Estudios</option>
                  </select>
                  <label for="careerModality" class="form-label">Meta de la Requisición</label>
                </div>
                <input type="hidden" name="career_id" value="{{$career->id}}">
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
