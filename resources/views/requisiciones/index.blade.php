@extends('layouts.layout')
@section('header')
  <x-bar />
  <x-navbar />
@endsection
@section('main-content')
  <div class="container-fluid py-4">
    <div class="row row-cols-1 row-cols-md-5 g-4">
      @foreach ($requisitions as $requisition)
        <div class="col">
          <a href="" class="text-decoration-none text-dark">
            <div class="card
              @switch($requisition->estado)
                @case('pendiente')
                @case('latencia')
                  bg-warning
                  @break     
              @endswitch"
            >
              <div class="card-body">
                <p class="card-text text-center text-uppercase">{{$requisition->estado}}</p>
              </div>
            </div>            
          </a>
        </div>
      @endforeach
    </div>
  </div>
  <div class="new-request">
    <img data-bs-target="#loginModal" data-bs-toggle="modal" src="{{ asset('img/nuevo.svg') }}" alt="Icono de nuevo">
  </div>
  <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header text-center">
          <h5 class="modal-title text-uppercase w-100" id="exampleModalLabel">Nueva Solicitud</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form class="mb-2" method="POST" action="{{ url('/validar') }}">
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
            <div class="form-floating">
              <select id="institutions" class="form-control">
                <option selected disabled>-- Seleccione la Institución --</option>
                @foreach ($institutions as $institution)
                  <option value="{{ $institution->id }}">{{ $institution->nombre }}</option>
                @endforeach
              </select>
              <label for="institutions" class="form-label">Institución</label>
            </div>
            <div class="d-grid mt-4">
              <button class="btn btn-success text-uppercase" type="submit">Iniciar Sesión</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('footer')
  <x-footer />
  <script>
    const institutions = document.querySelector('#institutions')

    institutions.addEventListener('change', () => {
      fetch('')
        .then(response => response.json())
        .then(data => console.log(data))
        .catch(err => {
          console.log(err)
        });

    })
  </script>
@endsection
