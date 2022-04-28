@extends('layouts.layout')
@section('header')
  <x-bar />
  <x-navbar />
@endsection
@section('main-content')
  <div class="root pb-5 mb-2">
    <div class="form-register">
      <div class="form-register__body">
        <form method="POST" action="{{url('/update/formats')}}" class="step active p-4" id="step-1">
          @csrf
          <h2 class="step__title">Verificar Formatos</h2>
          @foreach ($formats as $format)
          <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="form-check">
                <input type="hidden" name="requisition_id" value="{{$data->id}}">
                <input 
                name="anexo{{$loop->iteration}}" 
                value="{{$format->valido}}" 
                class="form-check-input" 
                type="checkbox" 
                id="flexCheckValido-{{$format->id}}" 
                @if ($format->valido)
                checked
                @endif
                >
                <label class="form-check-label" for="flexCheckValido{{$format->id}}">
                  {{$format->formato}}
                </label>
              </div>
              <div class="form-floating">
                <input name="anexo{{$loop->iteration}}j" type="text" class="form-control" id="justificacion" placeholder="Justificación">
                <label for="justificacion">Justificación</label>
              </div>
            </div>
            @endforeach
            <div class="d-flex justify-content-center gap-2">
              <button class="btn btn-success" type="submit">Guardar</button>
              <button type="button" class="btn btn-success step__button--next" data-to_step="2"
              data-step="1">Siguiente</button>
            </div>
          </form>
          <div class="step" id="step-2">
            <div class="step__header">
              <h2 class="step__title">Información de personal<small>(Paso 2)</small></h2>
            </div>
            <div class="step__body">
              <input type="text" placeholder="Nombre" class="step__input">
              <input type="text" placeholder="Apellido" class="step__input">
              <input type="tel" placeholder="Teléfono" class="step__input">
              <textarea rows="4" cols="80" placeholder="Dirección" class="step__input"></textarea>
            </div>
            <div class="step__footer">
              <button type="button" class="step__button step__button--back" data-to_step="1" data-step="2">Regresar</button>
              <button type="button" class="step__button step__button--next" data-to_step="3"
              data-step="2">Siguiente</button>
            </div>
          </div>
          <div class="step" id="step-3">
            <div class="step__header">
              <h2 class="step__title">Información X<small>(Paso 3)</small></h2>
            </div>
            <div class="step__body">
              <input type="text" placeholder="Dato x" class="step__input">
              <input type="text" placeholder="Dato x" class="step__input">
              <input type="text" placeholder="Dato x" class="step__input">
            </div>
            <div class="step__footer">
              <button type="button" class="step__button step__button--back" data-to_step="2" data-step="3">Regresar</button>
              <button type="submit" class="step__button">Registrarse</button>
            </div>
          </div>
        </div>
      </div>
      <div class="form-register__header">
        <ul class="progressbar">
          <li class="progressbar__option active">paso 1</li>
          <li class="progressbar__option">paso 2</li>
          <li class="progressbar__option">paso 3</li>
        </ul>
      </div>
    </div>
    @endsection
    @section('footer')
    <x-footer />
    @endsection
    @section('script')
    <script>
      let form = document.querySelector('.form-register');
      let progressOptions = document.querySelectorAll('.progressbar__option');
      const checkBoxes = document.querySelectorAll('.form-check-input')

      
    cargarEventListener()
    function cargarEventListener() {
      // Cambiar de formulario
      form.addEventListener('click', cambiarFormulario )

      // 
      checkBoxes.forEach( checkBox => {
        checkBox.addEventListener('click', verificarEstado)
      });
    }


    function verificarEstado(e) {
      const checkBox = e.target
      if (checkBox.checked) {
        checkBox.value = true
      } else {
        checkBox.value = false
      }
      console.log(checkBox);
    }


    function cambiarFormulario(e) {
      let element = e.target;
      let isButtonNext = element.classList.contains('step__button--next');
      let isButtonBack = element.classList.contains('step__button--back');
      if (isButtonNext || isButtonBack) {
        let currentStep = document.getElementById('step-' + element.dataset.step);
        let jumpStep = document.getElementById('step-' + element.dataset.to_step);
        currentStep.addEventListener('animationend', function callback() {
          currentStep.classList.remove('active');
          jumpStep.classList.add('active');
          if (isButtonNext) {
            currentStep.classList.add('to-left');
            progressOptions[element.dataset.to_step - 1].classList.add('active');
          } else {
            jumpStep.classList.remove('to-left');
            progressOptions[element.dataset.step - 1].classList.remove('active');
          }
          currentStep.removeEventListener('animationend', callback);
        });
        currentStep.classList.add('inactive');
        jumpStep.classList.remove('inactive');
      }
    }
  </script>
@endsection
