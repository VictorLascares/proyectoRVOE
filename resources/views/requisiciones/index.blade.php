@extends('layouts.layout')
@section('header')
  <x-bar />
  <x-navbar />
@endsection
@section('main-content')
  <div class="container py-4">
    <div class="mb-4">
      <select id="filtro-estado" class="custom-select">
        <option selected>Filtrar por estado</option>
        <option value="activo">Activo</option>
        <option value="latencia">Latencia</option>
        <option value="revocado">Revocado</option>
        <option value="inactivo">Inactivo</option>
        <option value="pendiente">Pendiente</option>
        <option value="rechazado">Rechazado</option>
      </select>
    </div>

    <div class="row row-cols-1 row-cols-md-5 g-4">
      @foreach ($requisitions as $requisition)
        <div 
          class="col requisicion"
          data-estado="{{$requisition->estado}}"
        >
          <a href="{{ route('requisitions.show', $requisition->id) }}"
            class="requisition text-decoration-none text-uppercase fw-bold btn py-4 px-5 @switch($requisition->estado) @case('pendiente')
              @case('latencia')
                btn-warning
                text-dark
                @break
              @case('activo')
                btn-success
                text-light
                @break
              @case('revocado')
              @case('inactivo')
              @case('rechazado')
                text-light
                btn-danger
                @break @endswitch"
            style="width: 13rem">
            {{ $requisition->estado }}
          </a>
        </div>
      @endforeach
    </div>
  </div>
  <button data-bs-toggle="modal" data-bs-target="#requisitionModal" type="button"
    class="new-request btn-success h2 py-2 px-3 rounded-1">
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
    const filtroEstado = document.getElementById('filtro-estado')
    const requisiciones = document.querySelectorAll('.requisicion')

    cargarEventListeners()


    function cargarEventListeners() {
      filtroEstado.addEventListener('change', filtrarPorEstado)
    }



    function filtrarPorEstado(e) {
      const option = e.target.value
      requisiciones.forEach(req => {
        if(req.getAttribute('data-estado')!=option){
          req.classList.add('hide')
        } else {
          req.classList.remove('hide')
        }
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
