@extends('layouts.layout')
@section('header')
  <x-bar />
  <x-navbar />
@endsection
@section('main-content')
  <x-login />
  <div class="container-sm p-4 my-5">
    <form class="row g-3">
      <div class="col-md-6">
        <div class="form-floating mb-3">
          <select id="institutionsSelect" class="form-control">
            <option selected disabled>-- Seleccione una Institución --</option>
            @foreach ($institutions as $institution)
                <option value="{{ $institution->id }}">{{$institution->nombre}}</option>
            @endforeach
          </select>
          <label for="institutionSelect" class="form-label">Instituciones</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-floating mb-3">
          <select id="inputState" class="form-control">
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
          <label for="inputState" class="form-label"> Area de estudios</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-floating mb-3">
          <select id="municipalitySelect" class="form-control">
            <option selected disabled>-- Seleccione un Municipio --</option>
            @foreach ($municipalities as $municipality)
                <option value="{{ $municipality->id }}">{{$municipality->nombre}}</option>
            @endforeach
          </select>
          <label for="municipalitySelect" class="form-label">Municipio</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-floating mb-3">
          <select id="inputState" class="form-control">
            <option selected disabled>-- Seleccione la modalidad --</option>
            <option value="Presencial">Presencial</option>
            <option value="Distancia">Distancia</option>
            <option value="Hibrida">Hibrida</option>
          </select>
          <label for="inputState" class="form-label">Modalidad</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-floating mb-3">
          <select id="inputState" class="form-control">
            <option selected disabled>-- Seleccione el status --</option>
            <option>Activo</option>
            <option>Inactivo</option>
            <option>Latencia</option>
            <option>Revocado</option>
            <option>Pendiente</option>
            <option>Revocado</option>
          </select>
          <label for="inputState" class="form-label">Status</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-floating mb-3">
          <input type="email" class="form-control" id="floatingInput" placeholder="RVOE o acuerdo">
          <label for="floatingInput">RVOE o acuerdo</label>
        </div>
      </div>
      <div class="col-12">
        <div class="d-flex gap-2">
          <div class="col-6">
            <div class="d-grid">
              <button type="button" class="btn btn-success btn-lg">Limpiar</button>
            </div>
          </div>
          <div class="col-6">
            <div class="d-grid">
              <button type="submit" class="btn btn-success btn-lg">Buscar</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection
@section('footer')
  <x-footer />
@endsection
