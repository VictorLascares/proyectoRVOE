@extends('layouts.layout')
@section('header')
  <x-bar />
  <x-navbar />
@endsection
@section('main-content')
  <div class="container mb-4 pt-2 pb-4">
    <h1 class="text-center">{{$institution->nombre}}</h1>
    <img src="{{ asset('img/institutions/' . $institution->logotipo) }}" alt="Logotipo Institución">
    <h2>Carrera</h2>
    <p>{{$career->nombre}}</p>
    <h2>Requisicion</h2>
    <p>{{$data->estado}}</p>
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
      form.addEventListener('click', cambiarFormulario)

      // 
      checkBoxes.forEach(checkBox => {
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
