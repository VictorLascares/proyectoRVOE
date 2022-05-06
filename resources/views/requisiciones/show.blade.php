@extends('layouts.layout')
@section('header')
  <x-bar />
  <x-navbar />
@endsection
@section('main-content')
  <div class="container mb-4 pt-2 pb-4">
    <div class="">
      <h1 class="text-center">{{ $institution->nombre }}</h1>
      <img style="max-width: 10rem" src="{{ asset('img/institutions/' . $institution->logotipo) }}"
        alt="Logotipo Institución">
      <h2>Carrera</h2>
      <p>{{ $career->nombre }}</p>
      <h2>Requisicion</h2>
      <p class="requisition__state">{{ $data->estado }}</p>
    </div>


    <div class="pb-4">
      <div class="d-flex justify-content-center gap-5">
        <div class="d-flex flex-column align-items-center gap-2">
          <h5 class="text-center w-75 text-uppercase">Evaluacion de formatos</h5>
          <div class="bg-danger p-4 rounded-circle">
            <i class="text-light bi bi-file-earmark-text h4"></i>
          </div>
          <ul class="p-0">
            <li class="list-unstyled">
              <a href="#" data-bs-toggle="modal" data-bs-target="#review1Modal">
                Revision 1
              </a>
            </li>
            <li class="list-unstyled">
              <a href="#" data-bs-toggle="modal" data-bs-target="#review2Modal">
                Revision 2
              </a>
            </li>
            <li class="list-unstyled">
              <a href="#" data-bs-toggle="modal" data-bs-target="#review3Modal">
                Revision 3
              </a>
            </li>
          </ul>
        </div>
        <div class="d-flex flex-column align-items-center gap-2">
          <h5 class="text-center w-75 text-uppercase">Evaluacion de las Instalaciones</h4>
            <div class="bg-danger p-4 rounded-circle">
              <i class="text-light bi bi-building h4"></i>
            </div>
            <a href="{{url('/evaluate/elements',$data->id)}}">
              Evaluar
            </a>
        </div>
        <div class="d-flex flex-column align-items-center gap-2">
          <h5 class="text-center w-75 text-uppercase">Evaluacion de los Planes</h5>
          <div class="bg-danger p-4 rounded-circle">
            <i class="text-light bi bi-list-task h4"></i>
          </div>
          <a href="#" data-bs-toggle="modal" data-bs-target="#eva3Modal">
            Evaluar
          </a>
        </div>
        <div class="d-flex flex-column align-items-center gap-2">
          <h5 class="text-center w-75 text-uppercase">Generacion de la OTA</h5>
          <div class="bg-danger p-4 rounded-circle">
            <i class="text-light bi bi-filetype-doc h4"></i>
          </div>
          <a href="">Evaluar</a>
        </div>
      </div>
    </div>


    <div class="modal fade" id="review1Modal" tabindex="-1" aria-labelledby="review2lLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header text-center">
            <h5 class="modal-title text-uppercase w-100" id="docsModalLabel">Verificar Formatos</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="mb-2" method="POST" action="{{ url('/update/formats') }}">
              @csrf
              <input type="hidden" name="requisition_id" value="{{ $data->id }}">
              @foreach ($formats as $format)
                <div class="d-flex justify-content-between align-items-center mb-2 p-2">
                  <p>
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
                  </p>
                  <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="anexo{{ $loop->iteration }}" id="btnYes-{{$format->id}}" autocomplete="off" @if ($format->valido) checked @endif>
                    <label class="btn btn-outline-success text-uppercase" for="btnYes-{{$format->id}}">Si</label>

                    <input type="radio" class="btn-check btn-No" name="anexo{{ $loop->iteration }}" id="btnNo-{{$format->id}}" autocomplete="off" @if (!$format->valido) checked @endif>
                    <label class="btn btn-outline-danger text-uppercase" for="btnNo-{{$format->id}}">No</label>
                  </div>
                </div>
              @endforeach
              <div class="d-grid col-6 mx-auto">
                <button class="btn btn-success" type="submit">Guardar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="review2Modal" tabindex="-1" aria-labelledby="review2ModalLabel" aria-hidden="true">
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
                      placeholder="Justificación" value="{{$format->justificacion}}">
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

    <div class="modal fade" id="review3Modal" tabindex="-1" aria-labelledby="review3ModalLabel" aria-hidden="true">
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
                      placeholder="Justificación" value="{{$format->justificacion}}">
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
    const checkBoxes = document.querySelectorAll('.btn-check')

    cargarEventListener()

    function cargarEventListener() {
      checkBoxes.forEach(checkBox => {
        checkBox.addEventListener('click', verificarEstado)
      });
    }

    function verificarEstado(e) {
      const checkBox = e.target
      if(checkBox.classList.contains('btn-No')) {
        console.log('si');
        checkBox.value = !this.checked
      } else {
        checkBox.value = this.checked
      }
      console.log(checkBox)
    }
  </script>
@endsection
