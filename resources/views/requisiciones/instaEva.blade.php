@extends('layouts.layout')
@section('header')
  <x-bar />
  <x-navbar />
@endsection
@section('main-content')
  <div class="container-fluid pb-4 mb-4 px-4">
    <h1 class="text-center mt-3">Evaluación de Instalaciones</h1>
    <form action="{{ url('/update/elements') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="table-wrapper-scroll-y my-custom-scrollbar">
        <table id="elementsTable" class="table table-striped table-bordered table-sm">
          <thead>
            <tr>
              <th class="th-sm" scope="col">#</th>
              <th class="th-sm" scope="col">Elemento</th>
              <th class="th-sm" scope="col">Existencia</th>
              <th class="th-sm" scope="col">Observacion</th>
            </tr>
          </thead>
          <input type="hidden" name="requisition_id" value="{{ $requisition->id }}">
          <tbody>
            @foreach (range(1, 52) as $i)
              @foreach ($elements as $element)
                @if ($element->elemento == $i)
                  <tr>
                    <th scope="row">
                      {{ $i }}
                    </th>
                    <td class="w-50">
                      <p class="h6">{{ $elementName[$i - 1] }}</p>
                    </td>
                    <td class="text-center" style="height: 100%">
                      <div class="h-100 btn-group" role="group">
                        <input type="radio" class="btn-check" name="elemento{{ $i }}"
                          value="{{ $element->existente }}" id="btnYes-{{ $element->id }}" autocomplete="off"
                          @if ($element->existente) checked @endif>
                        <label class="btn btn-outline-success text-uppercase" for="btnYes-{{ $element->id }}">Si</label>

                        <input type="radio" class="btn-check btn-No" name="elemento{{ $i }}"
                          id="btnNo-{{ $element->id }}" value="{{ $element->existente }}" autocomplete="off"
                          @if (!$element->existente) checked @endif>
                        <label class="btn btn-outline-danger text-uppercase" for="btnNo-{{ $element->id }}">No</label>
                      </div>
                    </td>
                    <td class="p-0">
                      <div class="form-floating">
                        <textarea rows="3" name="elemento{{ $i }}o" class="form-control rounded-0 resize-none fs-6 border-0"
                          placeholder="Observación"
                          id="inputSighting-{{ $element->id }}">{{ $element->observacion }}</textarea>
                        <label for="inputSighting-{{ $element->id }}">Observación</label>
                      </div>
                    </td>
                  </tr>
                @endif
              @endforeach
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="my-3">
        <label for="building-format" class="form-label fw-bold">Formato de Instalaciones</label>
        <input class="form-control" name="formatoInstalaciones" type="file" id="building-format" required>
      </div>
      <div class="d-flex justify-content-end">
        <input class="btn boton-green text-light" type="submit" value="Guardar">
      </div>
    </form>
  </div>
@endsection
@section('footer')
  <x-footer />
@endsection
@section('script')
  <script>
    const botones = document.querySelectorAll('.btn-check')

    iniciarEventos()

    function iniciarEventos() {
      botones.forEach(elemento => {
        elemento.addEventListener('click', evaluarEstado)
      });
    }

    function evaluarEstado(e) {
      const checkBox = e.target
      const id = checkBox.id.split('-')[1]
      const inputSighting = document.getElementById(`inputSighting-${id}`)
      if (checkBox.classList.contains('btn-No')) {
        checkBox.value = !this.checked
      } else {
        checkBox.value = this.checked
      }

      if (checkBox.value == 'true') {
        inputSighting.required = false
      } else {
        inputSighting.required = true
      }

      $(document).ready(function() {
        $('#elementsTable').DataTable({
          "scrollY": "50vh",
          "scrollCollapse": true,
        });
        $('.dataTables_length').addClass('bs-select');
      });
    }
  </script>
@endsection
