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
          <input type="text" class="form-control" id="floatingInput" placeholder="Nombre de la Institución">
          <label for="floatingInput">Nombre de la Institución</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-floating mb-3">
          <select id="inputState" class="form-control text-center">
            <option selected disabled>-- Seleccione el área de estudios --</option>
          </select>
          <label for="inputState" class="form-label"> Area de estudios</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-floating mb-3">
          <select id="inputState" class="form-control text-center">
            <option selected disabled>-- Seleccione un Municipio --</option>
          </select>
          <label for="inputState" class="form-label">Municipio</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-floating mb-3">
          <select id="inputState" class="form-control text-center">
            <option selected disabled>-- Seleccione la modalidad --</option>
          </select>
          <label for="inputState" class="form-label">Modalidad</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-floating mb-3">
          <select id="inputState" class="form-control text-center">
            <option selected disabled>-- Seleccione el status --</option>
            <option>Activo</option>
            <option>Inactivo</option>
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
