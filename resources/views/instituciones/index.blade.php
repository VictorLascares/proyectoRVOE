@extends('layouts.layout')
@section('header')
  <x-bar />
  <x-navbar />
@endsection
@section('main-content')
  <div class="container-sm pb-5 mb-4">
    <div class="row pt-4">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h1 class="card-title text-uppercase">Instituciones</h1>
            @if (Auth::user()->tipoUsuario == 'planeacion')
              <button type="button" data-bs-target="#institutionsModal" data-bs-toggle="modal" class="btn btn-success">Nueva
                Institución</button>
            @endif
          </div>
          <div class="card-body">
            <div class="d-flex flex-column justify-content-center align-items-center gap-2">
              @foreach ($institutions as $institution)
                <a class="text-decoration-none text-dark institution"
                  href="{{ route('institutions.show', $institution) }}">
                  <div class="card p-2" style="max-width: 600px;">
                    <div class="row g-0">
                      <div class="col-md-4">
                        <img src="{{ asset('img/institutions/' . $institution->logotipo) }}"
                          class="img-fluid rounded-start" alt="Logo de la Institución">
                      </div>
                      <div class="col-md-8">
                        <div class="card-body">
                          <h3 class="text-center card-title">{{ $institution->nombre }}<h3>
                          <p class="fs-5 text-center">Director(a): {{ $institution->director }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="institution__overlay rounded"></div>
                </a>
              @endforeach
            </div>
          </div>
          <div class="card-footer d-flex justify-content-center">
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
        </div>
      </div>
    </div>
    <div class="modal fade" id="institutionsModal" tabindex="-1" aria-labelledby="institutionsModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header text-center">
            <h5 class="modal-title text-uppercase w-100" id="institutionsModalLabel">Nueva Institución</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="mb-2" method="POST" action="{{ route('institutions.store') }}"
              enctype="multipart/form-data">
              @csrf
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="institutionName" name="nombre"
                  placeholder="Nombre de la Institución">
                <label for="institutionName">Nombre de la Institución</label>
              </div>
              <div class="form-floating mb-3">
                <input type="text" class="form-control" name="director" id="directorName"
                  placeholder="Nombre del Director">
                <label for="directorName">Nombre del Director</label>
              </div>
              <div class="form-floating mb-3">
                <select name="municipalitie_id" class="form-select" id="municipality"
                  aria-label="Floating label select example">
                  <option selected disabled>Selecciona un municipio</option>
                  @foreach ($municipalities as $municipality)
                    <option value="{{ $municipality->id }}">{{ $municipality->nombre }}</option>
                  @endforeach
                </select>
                <label for="municipality">Municipio</label>
              </div>
              <div class="form-floating mb-3">
                <textarea name="direccion" class="form-control resize-none" id="address" placeholder="Dirección"></textarea>
                <label for="address">Dirección</label>
              </div>
              <div class="input-group form-group mb-3">
                <input type="file" class="form-control" id="institutionLogo" name="logotipo">
              </div>
              <div class="d-grid mt-4">
                <button class="btn btn-success text-uppercase" type="submit">Agregar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('footer')
  <x-footer />
@endsection
