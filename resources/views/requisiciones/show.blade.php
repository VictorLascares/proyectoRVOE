@extends('layouts.layout')
@section('header')
  <x-bar />
  <x-navbar />
@endsection
@section('main-content')
  <div class="container mb-4 pt-2 pb-4">
    <h1 class="text-center">{{ $institution->nombre }}</h1>
    <img src="{{ asset('img/institutions/' . $institution->logotipo) }}" alt="Logotipo Institución">
    <h2>Carrera</h2>
    <p>{{ $career->nombre }}</p>
    <h2>Requisicion</h2>
    <p>{{ $data->estado }}</p>


    <div class="pb-4">
      <div class="d-flex justify-content-center gap-5">
        <button class="py-3 px-5 btn btn-danger" data-bs-target="#docsModal" data-bs-toggle="modal" type="button"><span
            class="h3">1</span></button>
        <button class="py-3 px-5 btn btn-danger" data-bs-target="#docsModal" data-bs-toggle="modal" type="button"><span class="h3">2</span></button>
        <button class="py-3 px-5 btn btn-danger" type="button"><span class="h3">3</span></button>
        <button class="py-3 px-5 btn btn-danger" type="button"><span class="h3">4</span></button>
      </div>
    </div>

    <div class="modal fade" id="docsModal" tabindex="-1" aria-labelledby="docsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header text-center">
            <h5 class="modal-title text-uppercase w-100" id="docsModalLabel">Verificar Formatos</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="mb-2" method="POST" action="{{ url('/update/formats') }}">
              @csrf
              @foreach ($formats as $format)
                <div class="d-flex justify-content-between align-items-center mb-3 p-2">
                  <div class="form-check">
                    <input type="hidden" name="requisition_id" value="{{ $data->id }}">
                    <input name="anexo{{ $loop->iteration }}" value="{{ $format->valido }}" class="form-check-input"
                      type="checkbox" id="flexCheckValido-{{ $format->id }}"
                      @if ($format->valido) checked @endif>
                    <label class="form-check-label" for="flexCheckValido{{ $format->id }}">
                      @switch($format->formato)
                        @case(1)
                          Plan de Estudios
                          @break
                        @case(2)
                          Mapa Curricular
                          @break
                        @case(3)
                          Programa de Estudio
                          @break
                        @case(4)
                          Estructura e instalaciones
                          @break
                        @case(5)
                          Plataforma Tecnológica
                          @break
                        @default
                          Formato desconocido
                      @endswitch
                    </label>
                  </div>
                  <div class="form-floating">
                    <input name="anexo{{ $loop->iteration }}j" type="text" class="form-control" id="justificacion"
                      placeholder="Justificación">
                    <label for="justificacion">Justificación</label>
                  </div>
                </div>
              @endforeach
              <div class="d-grid gap-2 col-6 mx-auto">
                <button class="btn btn-success" type="submit">Guardar</button>
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
@section('script')
  <script>
    const checkBoxes = document.querySelectorAll('.form-check-input')

    cargarEventListener()

    function cargarEventListener() {
      checkBoxes.forEach(checkBox => {
        checkBox.addEventListener('click', verificarEstado)
      });
    }

    function verificarEstado(e) {
      const checkBox = e.target
      checkBox.value = this.checked
      console.log(checkBox);
    }
  </script>
@endsection
