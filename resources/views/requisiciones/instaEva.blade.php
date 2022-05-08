@extends('layouts.layout')
@section('header')
  <x-bar />
  <x-navbar />
@endsection
@section('main-content')
  <div class="container-lg pb-4 mb-4">
    <form action="{{url('/update/elements')}}" method="POST" class="p-4">
      @csrf
      <input type="hidden" name="requisition_id" value="{{$requisition->id}}">
      @foreach ($elements as $element)
        <div class="d-flex justify-content-center gap-2">
          <p>Elemento {{ $element->elemento }}</p>
          <div class="">
            <input type="radio" class="btn-check" name="elemento{{ $loop->iteration }}" value="{{$element->existente}}" id="btnYes-{{ $element->id }}"
              autocomplete="off" @if ($element->existente) checked @endif>
            <label class="btn btn-outline-success text-uppercase" for="btnYes-{{ $element->id }}">Si</label>

            <input type="radio" class="btn-check btn-No" name="elemento{{ $loop->iteration }}" id="btnNo-{{ $element->id }}" value="{{$element->existente}}" autocomplete="off" @if (!$element->existente) checked @endif>
            <label class="btn btn-outline-danger text-uppercase" for="btnNo-{{ $element->id }}">No</label>
          </div>
          <input type="text" name="elemento{{$loop->iteration}}o">
        </div>
      @endforeach
      <input type="submit" value="Guardar">
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
      if(checkBox.classList.contains('btn-No')){
        checkBox.value = !this.checked
      } else {
        checkBox.value = this.checked
      }
    }
  </script>
@endsection
