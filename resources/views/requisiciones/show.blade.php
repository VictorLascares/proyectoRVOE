@extends('layouts.layout')
@section('header')
  <x-bar />
  <x-navbar />
@endsection
@section('main-content')
  <div class="container mb-4 pt-2 pb-4">
    <div class="solicitud my-4">
      <img class="solicitud__img img-thumbnail"
        src="{{ asset('img/institutions/' . $institution->logotipo) }}" alt="Logotipo Institución">
      <div class="solicitud__contenido rounded">
        <p class="h4"><span class="fw-bold">Institución: </span>{{ $institution->nombre }}</p>
        <p class="h4"><span class="fw-bold">Carrera: </span>{{ $career->nombre }}</p>
        <p class="h4"><span class="fw-bold">Estado: </span>{{ $data->estado }}</p>
        @if ($data->fecha_vencimiento)
          <p class="h4"><span class="fw-bold">Fecha de Vencimiento: </span>{{ $data->fecha_vencimiento }}</p>
        @endif
        @if ($data->fecha_latencia)
          <p class="h4"><span class="fw-bold">Fecha de Latencia: </span>{{ $data->fecha_latencia }}</p>
        @endif
        @if (Auth::user()->tipoUsuario == 'administrador')
          @if ($data->estado == 'activo' || $data->estado == 'latencia' || $data->estado == 'revocado')
            <form action="{{ route('requisitions.update', $data->id) }}" method="POST" enctype="multipart/form-data">
              @method('PUT')
              @csrf
              <div class="btn-group">
                <select class="form-select" name="estado">
                  <option selected disabled>Selecciona un estado</option>
                  @switch($data->estado)
                    @case('activo')
                      <option value="latencia">Latencia</option>
                      <option value="revocado">Revocado</option>
                    @break

                    @case('latencia')
                    @case('revocado')
                      <option value="activo">Activo</option>
                    @break
                  @endswitch
                </select>
                <button class="btn boton-green text-light" type="submit">
                  Actualizar
                </button>
              </div>
            </form>
          @endif
        @endif

      </div>
    </div>



    <div class="pb-4">
      <div class="evaluaciones">
        <a href="#" @if ($data->noEvaluacion < 4) data-bs-target="#review{{ $data->noEvaluacion }}Modal" @endif
          data-bs-toggle="modal" type="button" id="evaFormatos"
          class="m-0 formatos btn btn-danger @if ($data->noEvaluacion > 3 || $data->estado == 'rechazado') disabled
          @elseif (Auth::user()->tipoUsuario == 'planeacion' && $data->noEvaluacion == 3)
          disabled @endif ">
          <h3 class="text-center text-uppercase">Formatos</h3>
          <i class="text-light bi bi-file-earmark-text h1"></i>
          <p>
            Revision @if ($data->noEvaluacion < 4)
              {{ $data->noEvaluacion }}
            @else
              3
            @endif
          </p>
        </a>
        <a href="{{ url('/evaluate/elements', $data->id) }}"
          class="instalaciones @if ($data->noEvaluacion != 4 or $data->estado == 'rechazado' or Auth::user()->tipoUsuario == 'planeacion') disabled @endif btn btn-danger">
          <h3 class="text-center text-uppercase">Instalaciones</h3>
          <i class="text-light bi bi-building h1"></i>
        </a>
        <a href="{{ url('/evaluate/plans', $data->id) }}"
          class="planes @if ($data->noEvaluacion != 5 or $data->estado == 'rechazado') disabled @endif btn btn-danger">
          <h3 class="text-center text-uppercase">Planes</h3>
          <i class="text-light bi bi-list-task h1"></i>
        </a>
        <a download="OTAReq-{{ $data->id }}" href="{{ url('/download', $data->id) }}"
          class="ota btn btn-danger @if ($data->noEvaluacion != 6 or $data->estado == 'rechazado') disabled @endif">
          <h3 class="text-center text-uppercase">OTA</h3>
          <i class="text-light bi bi-cloud-download h1"></i>
        </a>
      </div>
      @if (Auth::user()->tipoUsuario == 'administrador')
        <div class="d-flex justify-content-end mt-4">
          <a href="{{ url('/evaluacion-anterior', $data->id) }}" class="btn boton-green text-light">Modificar
            Evaluación</a>
        </div>
      @endif
    </div>

    @if (!empty($errors))
      <div class="pb-4">
        @foreach ($errors as $error)
          @if ($error->justificacion)
            <p class="alert alert-danger">Revision {{ $data->noEvaluacion - 1 }}.- Evaluación de Formatos -
              {{ $error->justificacion }} ({{ $formatNames[$error->formato - 1] }})</p>
          @else
            @if ($error->observacion)
              <p class="alert alert-danger">Evaluación de las Instalaciones.- {{ $error->observacion }} (Elemento
                {{ $error->elemento }})</p>
            @else
              <p class="alert alert-danger">Evaluación de los Planes.-{{ $error->comentario }} (Plan
                {{ $error->plan }})</p>
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
                        <input name="anexo{{ $i }}j" type="text" class="form-control"
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
