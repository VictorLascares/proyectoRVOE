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
          <button @if ($data->noEvaluacion < 4) data-bs-target="#review{{ $data->noEvaluacion }}Modal" @endif
            data-bs-toggle="modal" type="button" class="@if($data->noEvaluacion > 3) disabled @endif btn btn-danger p-4">
            <i class="text-light bi bi-file-earmark-text h4"></i>
          </button>
          <p>
            Revision @if ($data->noEvaluacion < 4)
              {{ $data->noEvaluacion }}
            @else
              3
            @endif
          </p>
        </div>
        <div class="d-flex flex-column align-items-center gap-2">
          <h5 class="text-center w-75 text-uppercase">Evaluacion de las Instalaciones</h4>
            <a href="{{ url('/evaluate/elements', $data->id) }}" class="@if($data->noEvaluacion != 4) disabled @endif btn btn-danger p-4">
              <i class="text-light bi bi-building h4"></i>
            </a>
        </div>
        <div class="d-flex flex-column align-items-center gap-2">
          <h5 class="text-center w-75 text-uppercase">Evaluacion de los Planes</h5>
          <a href="{{ url('/evaluate/plans', $data->id) }}" class="@if($data->noEvaluacion != 5) disabled @endif btn btn-danger p-4">
            <i class="text-light bi bi-list-task h4"></i>
          </a>
        </div>
        <div class="d-flex flex-column align-items-center gap-2">
          <h5 class="text-center w-75 text-uppercase">Generacion de la OTA</h5>
          <a download="OTAReq-{{ $data->id }}" href="{{ url('/download', $data->id) }}" class="@if($data->noEvaluacion != 6) disabled @endif btn btn-danger p-4">
            <i class="text-light bi bi-cloud-download h4"></i>
          </a>
        </div>
      </div>
      <a href="{{url('/evaluacion-anterior',$data->id)}}" class="btn btn-success">Modificar Evaluación</a>
    </div>

    @if (!empty($errors))
      <div class="pb-4">
        @foreach ($errors as $error)
          @if ($error->justificacion)
            <p class="alert alert-danger">{{ $error->justificacion }} ({{ $formatNames[$error->formato - 1] }})</p>
          @else
            @if ($error->observacion)
              <p class="alert alert-danger">{{ $error->observacion }} (Elemento {{ $error->elemento }})</p>
            @else
              <p class="alert alert-danger">{{ $error->comentario }} (Plan {{ $error->plan }})</p>
            @endif
          @endif
        @endforeach
      </div>
    @endif


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
              @foreach (range(1, 5) as $item)
                @foreach ($formats as $format)
                  @if ($format->formato == $item)
                    <div class="d-flex justify-content-between align-items-center mb-2 p-2">
                      <p>
                        {{ $formatNames[$item - 1] }}
                      </p>
                      <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" name="anexo{{ $item }}"
                          id="btnYes-{{ $format->id }}" autocomplete="off"
                          @if ($format->valido) checked @endif>
                        <label class="btn btn-outline-success text-uppercase" for="btnYes-{{ $format->id }}">Si</label>
                        <input type="radio" class="btn-check btn-No" name="anexo{{ $item }}"
                          id="btnNo-{{ $format->id }}" autocomplete="off"
                          @if (!$format->valido) checked @endif>
                        <label class="btn btn-outline-danger text-uppercase" for="btnNo-{{ $format->id }}">No</label>
                      </div>
                    </div>
                  @endif
                @endforeach
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
              <input type="hidden" name="requisition_id" value="{{ $data->id }}">
              @foreach (range(1, 5) as $i)
                @foreach ($formats as $format)
                  @if ($format->formato == $i)
                    <div class="d-flex justify-content-between align-items-center mb-3 p-2">
                      <div class="form-check">
                        <input name="anexo{{ $i }}" value="{{ $format->valido }}"
                          class="review2Checkbox form-check-input" type="checkbox" id="check-review2-{{ $format->id }}"
                          @if ($format->valido) checked @endif>
                        <label class="form-check-label" for="check-review2-{{ $format->id }}">
                          {{ $formatNames[$i - 1] }}
                        </label>
                      </div>
                      <div class="form-floating">
                        <input name="anexo{{$i}}j" type="text" class="form-control"
                          id="just-review2-{{ $format->id }}" placeholder="Justificación"
                          value="{{ $format->justificacion }}">
                        <label for="just-review2-{{ $format->id }}">Justificación</label>
                      </div>
                    </div>
                  @endif
                @endforeach
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
              @foreach (range(1, 5) as $i)
                @foreach ($formats as $format)
                  @if ($format->formato == $i)
                    <div class="d-flex justify-content-between align-items-center mb-3 p-2">
                      <div class="form-check">
                        <input type="hidden" name="requisition_id" value="{{ $data->id }}">
                        <input name="anexo{{ $i }}" value="{{ $format->valido }}"
                          class="form-check-input review3Checkbox" type="checkbox" id="check-review3-{{ $format->id }}"
                          @if ($format->valido) checked @endif>
                        <label class="form-check-label" for="check-review3-{{ $format->id }}">
                          {{ $formatNames[$i - 1] }}
                        </label>
                      </div>
                      <div class="form-floating">
                        <input name="anexo{{ $i }}j" type="text" class="form-control"
                          id="just-review3-{{ $format->id }}" placeholder="Justificación"
                          value="{{ $format->justificacion }}">
                        <label for="just-review3-{{ $format->id }}">Justificación</label>
                      </div>
                    </div>
                  @endif
                @endforeach
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
    const checkBtnRadios = document.querySelectorAll('.btn-check')
    const checkBoxes1 = document.querySelectorAll('.review2Checkbox')
    const checkBoxes2 = document.querySelectorAll('.review3Checkbox')

    cargarEventListener()

    function cargarEventListener() {
      checkBtnRadios.forEach(radio => {
        radio.addEventListener('click', review1)
      });

      checkBoxes1.forEach(checkBox => {
        checkBox.addEventListener('click', review2)
      })

      checkBoxes2.forEach(checkBox => {
        checkBox.addEventListener('click', review3)
      })
    }

    function review1(e) {
      const radioBtn = e.target
      if (radioBtn.classList.contains('btn-No')) {
        radioBtn.value = !this.checked
      } else {
        radioBtn.value = this.checked
      }
    }

    function review2(e) {
      const checkBox = e.target
      const id = checkBox.id.split('-')[2]
      const justInput = document.getElementById(`just-review2-${id}`)
      checkBox.value = checkBox.checked
      if (checkBox.value == 'true') {
        justInput.required = false
      } else {
        justInput.required = true
      }
    }

    function review3(e) {
      const checkBox = e.target
      const id = checkBox.id.split('-')[2]
      const justInput = document.getElementById(`just-review3-${id}`)
      checkBox.value = checkBox.checked
      if (checkBox.value == 'true') {
        justInput.required = false
      } else {
        justInput.required = true
      }
    }
  </script>
@endsection
