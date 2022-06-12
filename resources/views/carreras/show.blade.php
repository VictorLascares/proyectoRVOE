@extends('layouts.app')
@section('header')
  
  
@endsection
@section('main-content')
  <div class="container mb-5 mt-4 pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>{{ $career->nombre }}</h2>
      @if (Auth::user()->tipoUsuario != 'direccion')
          <form method="POST" action="{{ route('careers.destroy', $career->id) }}" class="d-flex justify-content-end">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" type="submit">
              <i class="bi bi-trash"></i>
              Eliminar
            </button>
          </form>
      @endif
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
          <select id="careerArea" class="form-control" name="area_id" required>
            <option selected disabled>-- Seleccione el Area --</option>
            @foreach ($areas as $area)
              <option @if ($area->id == $career->area_id) selected @endif value="{{ $area->id }}">
                {{ $area->nombre }}
              </option>
            @endforeach
          </select>
          <label for="careerArea" class="form-label">Area de la Carrera</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-floating mb-3">
          <select id="careerModality" class="form-control" name="modalidad" required>
            <option selected disabled>-- Seleccione la Modalidad --</option>
            <option value="Presencial" @if ($career->modalidad == 'Presencial') selected @endif>Presencial</option>
            <option value="Distancia" @if ($career->modalidad == 'Distancia') selected @endif>Distancia</option>
            <option value="Hibrida" @if ($career->modalidad == 'Hibrida') selected @endif>Hibrida</option>
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
      @if (Auth::user()->tipoUsuario != 'direccion')
        <div class="d-flex justify-content-center align-items-center">
          <button class="btn boton-green text-light" type="submit">
            <i class="bi bi-arrow-repeat"></i>
            Actualizar
          </button>
        </div>
      @endif
    </form>

    <section>
      <div class="d-flex justify-content-between align-items-center">
        <h2>Requisiciones</h2>
        @if (Auth::user()->tipoUsuario !== 'direccion')
          <button type="button" data-bs-target="#careersModal" data-bs-toggle="modal" type="submit"
            class="btn boton-green text-light">
            <i class="bi bi-plus-circle"></i>
            Nueva Requisición
          </button>
        @endif
      </div>

      <table class="table able-bordered table-condensed table-striped table-hover">
        <thead>
          <tr>
            <th scope="col">Meta</th>
            <th scope="col">Creación</th>
            <th scope="col">Ultima Actualización</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($requisitions as $requisition)
            <tr class="table-row" data-href="{{ route('requisitions.show', $requisition->id) }}">
              <td>{{ $requisition->meta }}</td>
              <td>{{ $requisition->created_at }}</td>
              <td>{{ $requisition->updated_at }}</td>
            </tr>
            </a>
          @endforeach
        </tbody>
      </table>

      <div class="modal fade" id="careersModal" tabindex="-1" aria-labelledby="careersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header text-center">
              <h5 class="modal-title text-uppercase w-100" id="careersModalLabel">Nueva Requisición</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form class="mb-2" method="POST" action="{{ route('requisitions.store') }}">
                @csrf
                <div class="form-floating mb-3">
                  <input type="hidden" value="{{ $career->id }}" name="career_id">
                  <select id="requisitionGoal" class="form-control" name="meta" required>
                    <option selected disabled>-- Seleccione la Meta --</option>
                    <option value="solicitud">Solicitud</option>
                    <option value="domicilio">Domicilio</option>
                    <option value="planEstudios">Plan de Estudios</option>
                  </select>
                  <label for="careerModality" class="form-label">Meta de la Requisición</label>
                </div>
                <input type="hidden" name="career_id" value="{{ $career->id }}">
                <div class="d-grid mt-4">
                  <button class="btn boton-green text-light text-uppercase" type="submit">Agregar</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsectionp
@section('footer')
  
@endsection
@section('script')
  <script>
    $(document).ready(function($) {
      $(".table-row").click(function() {
        window.document.location = $(this).data("href");
      })
    })
  </script>
@endsection
