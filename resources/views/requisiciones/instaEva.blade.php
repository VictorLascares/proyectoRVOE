@extends('layouts.layout')
@section('header')
  <x-bar />
  <x-navbar />
@endsection
@section('main-content')
  <div class="container-lg pb-4 mb-4">
    <h1 class="text-center mt-3">Evaluación de Instalaciones</h1>
    <form action="{{ url('/update/elements') }}" method="POST" class="p-4">
      @csrf
    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Elemento</th>
          <th scope="col">Existencia</th>
          <th scope="col">Observacion</th>
        </tr>
      </thead>
        <input type="hidden" name="requisition_id" value="{{ $requisition->id }}">
        <tbody>
          @foreach ($elements as $element)
            <tr>
              <th scope="row">
                {{$loop->iteration}}
              </th>
              <td class="w-50">
                <p>{{ $elementName[$loop->iteration-1 ] }}</p>
              </td>
              <td>
                <div class="btn-group" role="group">
                  <input type="radio" class="btn-check" name="elemento{{ $loop->iteration }}"
                    value="{{ $element->existente }}" id="btnYes-{{ $element->id }}" autocomplete="off"
                    @if ($element->existente) checked @endif>
                  <label class="btn btn-outline-success text-uppercase" for="btnYes-{{ $element->id }}">Si</label>
  
                  <input type="radio" class="btn-check btn-No" name="elemento{{ $loop->iteration }}"
                    id="btnNo-{{ $element->id }}" value="{{ $element->existente }}" autocomplete="off"
                    @if (!$element->existente) checked @endif>
                  <label class="btn btn-outline-danger text-uppercase" for="btnNo-{{ $element->id }}">No</label>
                </div>
              </td>
              <td class="p-0">
                <textarea rows="3" id="inputSighting-{{$element->id}}" name="elemento{{ $loop->iteration }}o" placeholder="Observación" class="border-0 w-100 pt-1 resize-none fs-6"></textarea>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <div class="d-flex justify-content-end">
        <input class="btn btn-success" type="submit" value="Guardar">
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

      if(checkBox.value == 'true'){
        inputSighting.required = false
      } else {
        inputSighting.required = true
      }
      console.log(inputSighting);
    }
  </script>
@endsection
