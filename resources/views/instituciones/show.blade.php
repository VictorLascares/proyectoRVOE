@extends('layouts.layout')
@section('header')
  <x-bar />
  <x-navbar />
@endsection
@section('main-content')
  <div class="container mb-5 mt-4">
    <div class="d-flex align-items-center justify-content-end gap-2">
      <form method="POST" action="{{ route('institutions.destroy', $institution->id) }}"
        class="d-flex justify-content-end">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger" type="submit">
          <i class="bi bi-trash"></i>
          Eliminar
        </button>
      </form>
    </div>
    <div class="d-flex justify-content-center mb-3">
      <img src="{{ asset('img/institutions/' . $institution->logotipo) }}" class="img-fluid rounded-start"
        style="max-width: 100px" alt="Logo de la Institución">
    </div>
    <form class="row g-2 mb-3" action="{{ route('institutions.update', $institution->id) }}" method="POST"
      enctype="multipart/form-data">
      @method('PUT')
      @csrf
      <div class="col-md-6">
        <div class="form-floating mb-2">
          <input name="nombre" type="text" class="form-control" id="institutionName"
            placeholder="Nombre de la Institución" value="{{ $institution->nombre }}">
          <label for="institutionName">Nombre de la Institución</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-floating">
          <input name="director" type="text" class="form-control" id="institutionName" placeholder="Nombre del Director"
            value="{{ $institution->director }}">
          <label for="institutionName">Nombre del Director</label>
        </div>
      </div>
      <div class="col-12">
        <div class="form-floating mb-2">
          <select name="municipalitie_id" class="form-select" id="municipality"
            aria-label="Floating label select example">
            <option selected disabled>Selecciona un municipio</option>
            @foreach ($municipalities as $municipality)
              <option @if ($municipality->id == $institution->municipalitie_id) selected @endif value="{{ $municipality->id }}">
                {{ $municipality->nombre }}
              </option>
            @endforeach
          </select>
          <label for="municipality">Municipio</label>
        </div>
      </div>
      <div class="col-12">
        <div class="input-group form-group">
          <input type="file" class="form-control" id="institutionLogo" name="logotipo">
        </div>
      </div>
      <div class="d-flex justify-content-center">
        <button class="btn btn-success" type="submit">
          <i class="bi bi-arrow-repeat"></i>
          Actualizar
        </button>
      </div>
    </form>

    <section class="pb-5">
      <div class="d-flex justify-content-between align-items-center">
        <h2 class="">Carreras</h2>
        <button type="button" data-bs-target="#careersModal" data-bs-toggle="modal" type="submit" class="btn btn-success">
          <i class="bi bi-plus-circle"></i>
          Nueva Carrera
        </button>
      </div>

      <div class="list-group mt-3">
        @foreach ($careers as $career)
          <a href="{{ route('careers.show', $career->id) }}"
            class="d-flex justify-content-between text-decoration-none text-dark list-group-item list-group-item-action align-items-center">
            <p class="m-0">{{ $career->nombre }}</p>
            <p class="m-0">{{ $career->titulo }}</p>
          </a>
        @endforeach
      </div>
      <div class="d-flex justify-content-center align-items-center mt-4">
        <nav aria-label="Page navigation example">
          <ul class="pagination m-0">
            <li class="page-item"><a class="page-link  text-success" href="#">Previous</a></li>
            <li class="page-item"><a class="page-link  text-success" href="#">1</a></li>
            <li class="page-item"><a class="page-link  text-success" href="#">2</a></li>
            <li class="page-item"><a class="page-link  text-success" href="#">3</a></li>
            <li class="page-item"><a class="page-link  text-success" href="#">Next</a></li>
          </ul>
        </nav>
      </div>

      <div class="modal fade" id="careersModal" tabindex="-1" aria-labelledby="careersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header text-center">
              <h5 class="modal-title text-uppercase w-100" id="careersModalLabel">Nueva Carrera</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form class="mb-2" method="POST" action="{{ route('careers.store', $institution->id) }}">
                @csrf
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" id="carreerName" name="nombre"
                    placeholder="Nombre de la Carrera">
                  <label for="careerName">Nombre de la Carrera</label>
                </div>
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" name="titulo" id="careerTitle"
                    placeholder="Titulo de la Carrera">
                  <label for="careerTitle">Titulo de la Carrera</label>
                </div>
                <div class="form-floating mb-3">
                  <select id="careerArea" class="form-control" name="area" required>
                    <option selected disabled>-- Seleccione el área de estudios --</option>
                    <option value="">Arquitectura, Urbanismo y Diseño</option>
                    <option value="">Artes</option>
                    <option value="">Agronomía Veterinaria</option>
                    <option value="">Ciencias Biológicas</option>
                    <option value="">Ciencias Físico Matemáticas</option>
                    <option value="">Ciencias Sociales</option>
                    <option value="">Económico Administrativas</option>
                    <option value="">Educación</option>
                    <option value="">Humanidades</option>
                    <option value="">Ingenierías</option>
                  </select>
                  <label for="careerArea" class="form-label">Área de Estudio</label>
                </div>
                <div class="row">
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
                      <input type="number" class="form-control" name="duracion" id="careerDuration"
                        placeholder="Duración de la Carrera">
                      <label for="careerDuration">Duración de la Carrera</label>
                    </div>
                  </div>
                </div>
                <input type="hidden" name="institution_id" value="{{ $institution->id }}">
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