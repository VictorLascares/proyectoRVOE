@extends('layouts.layout')
@section('header')
  <x-bar />
  <x-navbar />
@endsection
@section('main-content')
  <div class="container py-4">
    <form class="mb-4 filtros">
      <select id="filtro-anio" class="custom-select">
        <option selected disabled>Filtrar por Año</option>
      </select>
      <select id="filtro-estado" class="custom-select">
        <option selected disabled>Filtrar por estado</option>
        <option value="activo">Activo</option>
        <option value="latencia">Latencia</option>
        <option value="revocado">Revocado</option>
        <option value="inactivo">Inactivo</option>
        <option value="pendiente">Pendiente</option>
        <option value="rechazado">Rechazado</option>
      </select>
      <button 
        type="reset" 
        id="limpiar-filtro" 
        class="boton boton-green rounded py-2"
      >
        Limpiar Filtros
      </button>
    </form>

    <div class="requisiciones">
      @foreach ($requisitions as $requisition)
          <a 
            data-fecha="{{ $requisition->created_at->format('m-d-y') }}"
            data-estado="{{ $requisition->estado }}"
            href="{{ route('requisitions.show', $requisition->id) }}"
            class="w-100 rounded requisicion requisition text-center text-decoration-none py-3 @switch($requisition->estado) @case('pendiente')
            @case('latencia')
              bg-light-yellow
              text-dark-yellow
              @break
            @case('activo')
              bg-light-green
              text-dark-green
              @break
            @case('revocado')
            @case('inactivo')
            @case('rechazado')
              bg-light-red 
              text-dark-red
            @endswitch"
            style="width: 13rem">
            <p class="text-uppercase fw-bold m-0">{{ $requisition->estado }}</p>
            <p class="m-0">{{ $requisition->created_at->format('m-d-Y') }}</p>
          </a>
      @endforeach
    </div>
  </div>
  <button 
    data-bs-toggle="modal" 
    data-bs-target="#requisitionModal" 
    type="button"
    class="new-request boton boton-green h2 py-2 px-3 rounded">
    +
  </button>
  <div class="modal fade" id="requisitionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header text-center">
          <h5 class="modal-title text-uppercase w-100" id="exampleModalLabel">Nueva Requisición</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form class="mb-2" method="POST" action="{{ route('requisitions.store') }}">
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
            <div class="form-floating  mb-3">
              <select id="institutions" class="form-control">
                <option selected disabled>-- Seleccione la Institución --</option>
                @foreach ($institutions as $institution)
                  <option value="{{ $institution->id }}">{{ $institution->nombre }}</option>
                @endforeach
              </select>
              <label for="institutions" class="form-label">Institución</label>
            </div>
            <div class="form-floating">
              <select id="careers" class="form-control" name="career_id">
                <option selected disabled>-- Seleccione la Carrera --</option>
              </select>
              <label for="careers" class="form-label">Carrera</label>
            </div>
            <div class="d-grid mt-4">
              <button class="btn btn-success text-uppercase" type="submit">Agregar</button>
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
@section('script')
  <script>
    const filtroEstado = document.querySelector('#filtro-estado')
    const requisiciones = document.querySelectorAll('.requisicion')
    const btnLimpiarFiltros = document.querySelector('#limpiar-filtro')
    const filtroAnio = document.querySelector('#filtro-anio')

    cargarEventListeners()


    function cargarEventListeners() {
      document.addEventListener("DOMContentLoaded", llenarSelectAnios)
      filtroEstado.addEventListener('change', filtrarPorEstado)
      btnLimpiarFiltros.addEventListener('click', limpiarFiltros)
      filtroAnio.addEventListener('change', filtrarPorAnio)
    }



    function filtrarPorEstado(e) {
      const option = e.target.value
      requisiciones.forEach(req => {
        if (req.getAttribute('data-estado') != option) {
          req.classList.add('hide')
        }
      })
    }

    function filtrarPorAnio(e) {
      const anio = e.target.value
      requisiciones.forEach(req => {
        if (req.getAttribute('data-fecha').split('-')[2] != anio) {
          req.classList.add('hide')
        }
      })
    }


    function llenarSelectAnios() {
      const anios = []
      requisiciones.forEach(req => {
        const anio = req.getAttribute('data-fecha').split('-')[2]
        if (!anios.includes(anio)) {
          anios.push(anio)
        }
      });
      for (let i = 0; i < anios.length; i++) {
        const option = document.createElement('option')
        option.value = anios[i]
        option.textContent = anios[i]
        filtroAnio.append(option)
      }
    }


    function limpiarFiltros() {
      requisiciones.forEach(req => {
        req.classList.remove('hide')
      })
    }


    $(document).ready(function() {
      $('#institutions').on('change', function() {
        let institutionId = $(this).val()
        $.get('careers', {
          institutionId: institutionId
        }, function(careers) {
          $('#careers').empty()
          $.each(careers, function(index, value) {
            $('#careers').append(`<option value='${index}'>${value}</option>`)
          });
        })
      })
    })
  </script>
@endsection
