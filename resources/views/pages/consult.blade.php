@extends('layouts.layout')
@section('header')
  <x-bar />
  <x-navbar />
@endsection
@section('main-content')
  <x-login />
  <div class="container-sm my-3 pb-4">
    <h1 class="text-center mb-4">Consultar <span class="text-uppercase">rvoe</span></h1>
    <form class="row g-3">
      <div class="col-md-6">
        <div class="form-floating mb-2">
          <select id="institutionsSelect" class="form-control">
            <option selected disabled>-- Seleccione una Institución --</option>
            @foreach ($institutions as $institution)
              <option value="{{ $institution->id }}">{{ $institution->nombre }}</option>
            @endforeach
          </select>
          <label for="institutionSelect" class="form-label">Instituciones</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-floating mb-2">
          <select id="inputState" class="form-control">
            <option selected disabled>-- Seleccione el área de estudios --</option>
            @foreach ($areas as $area)
              <option value="{{ $area->id }}">{{ $area->nombre }}</option>
            @endforeach
          </select>
          <label for="inputState" class="form-label">Area de estudios</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-floating mb-2">
          <select id="municipalitySelect" class="form-control">
            <option selected disabled>-- Seleccione un Municipio --</option>
            @foreach ($municipalities as $municipality)
              <option value="{{ $municipality->id }}">{{ $municipality->nombre }}</option>
            @endforeach
          </select>
          <label for="municipalitySelect" class="form-label">Municipio</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-floating mb-2">
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
        <div class="form-floating mb-2">
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
        <div class="form-floating mb-2">
          <input type="email" class="form-control" id="floatingInput" placeholder="RVOE o acuerdo">
          <label for="floatingInput">RVOE o acuerdo</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="d-grid">
          <button type="reset" class="text-uppercase boton boton-green py-2 rounded">Limpiar</button>
        </div>
      </div>
      <div class="col-md-6">
        <div class="d-grid">
          <button type="submit" class="text-uppercase boton boton-green py-2 rounded">Buscar</button>
        </div>
      </div>
    </form>
  </div>
@endsection
@section('footer')
  <x-footer />
@endsection
