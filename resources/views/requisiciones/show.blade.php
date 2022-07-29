@extends('layouts.app')
@section('titulo')
  Información de la Solicitud
@endsection
@section('contenido')
    <div class="flex justify-center items-center  mb-4 py-4">
        <img src="{{ $institution->logotipo }}"
    alt="Logotipo Institución">
    </div>
    <div class="md:grid md:grid-cols-2 md:gap-4">
      <div class="border">
        <div class="border-b">
            <h2 class="text-gray-600 md:col-span-2 text-center p-3 text-xl font-bold uppercase">Información de la Institución</h2>
        </div>
        <div>
            <p class="p-3 text-lg font-bold">Nombre: <span class="font-normal">{{ $institution->nombre }}</span></p>
        </div>
        <div>
            <p class="p-3 text-lg font-bold">Director(a): <span class="font-normal">{{ $institution->director }}</span></p>
        </div>
        <div>
            <p class="p-3 text-lg font-bold">Dirección: <span class="font-normal">{{ $institution->direccion }}</span></p>
        </div>
      </div>
      <div class="border mt-4 md:mt-0">
        <div class="border-b">
            <h2 class="text-gray-600 text-center p-3 text-xl font-bold uppercase">Información de la Carrera</h2>
        </div>
        <div>
            <p class="p-3 text-lg font-bold">Nombre: <span class="font-normal">{{ $career->nombre }}</span></p>
        </div>
        <div>
            <p class="p-3 text-lg font-bold">Modalidad: <span class="font-normal">{{ $career->modalidad }}</span></p>
        </div>
        <div>
            <p class="p-3 text-lg font-bold">Area: <span class="font-normal">{{ $area->nombre }}</span></p>
        </div>
      </div>
    </div>

    <div class="flex justify-end items-center gap-4">
        @if ($data->fecha_vencimiento)
            <div>
                <p class="font-bold">Fecha de Vencimiento: <span class="font-normal">{{ $data->fecha_vencimiento }}</span></p>
            </div>
        @endif
    
        @if ($data->fecha_latencia)
            <div>
                <p class="font-bold">Fecha de Latencia: <span class="font-normal">{{ $data->fecha_latencia }}</span></p>
            </div>
        @endif
    </div>


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
                <button class="bg-[#13322B] hover:bg-[#0C231E] py-2 px-3 text-white" type="submit">
                    Actualizar
                </button>
              </div>
            </form>
        @endif
    @endif

    <div class="my-10">
        <h2 class="text-center mb-5 uppercase text-2xl">Evaluación de la Solicitud</h2>
      <div class="grid sm:grid-cols-2 lg:grid-cols-6 gap-4">
        <a
          href="{{ route('evaluate.formats', $data->id) }}" type="button" id="evaFormatos"
          class="p-3 text-white formatos bg-[#13322B] hover:bg-[#0C231E] flex flex-col justify-center items-center @if ($data->noEvaluacion != 1)
          disabled
            @endif">
          <p class="uppercase">Formatos</p>
          <p>Evaluación <span class="font-bold">1</span></p>
        </a>
        <a href="{{ route('evaluate.formats', $data->id) }}" class="p-3 text-white formatos bg-[#13322B] hover:bg-[#0C231E] flex flex-col justify-center items-center @if ($data->noEvaluacion != 2)
            disabled
        @endif">
            <p class="uppercase">Formatos</p>
            <p>Evaluación <span class="font-bold">2</span></p>
        </a>
        <a href="{{ route('evaluate.formats' ,$data->id) }}" class="p-3 text-white formatos bg-[#13322B] hover:bg-[#0C231E] flex flex-col justify-center items-center @if ($data->noEvaluacion != 3 || Auth()->user()->tipoUsuario == 'planeacion')
            disabled
        @endif">
            <p class="uppercase">Formatos</p>
            <p>Evaluación <span class="font-bold">3</span></p>
        </a>
        <a href="{{ url('/evaluate/elements', $data->id) }}"
          class="p-3 text-white bg-[#13322B] hover:bg-[#0C231E] flex flex-col justify-center items-center @if ($data->noEvaluacion != 4 || Auth()->user()->tipoUsuario == 'planeacion')
          disabled
      @endif">
          <p class="uppercase">Instalaciones</p>
          <p>Evaluación <span class="font-bold">4</span></p>
        </a>
        <a href="{{ url('/evaluate/plans', $data->id) }}"
          class="p-3 text-white bg-[#13322B] hover:bg-[#0C231E] flex flex-col justify-center items-center @if ($data->noEvaluacion != 5 || Auth()->user()->tipoUsuario == 'planeacion')
          disabled
      @endif">
          <p class="uppercase">Planes</p>
          <p>Evaluación <span class="font-bold">5</span></p>
        </a>
        <a download="OTAReq-{{ $data->id }}" href="{{ url('/download', $data->id) }}"
          class="p-3 text-white bg-[#13322B] hover:bg-[#0C231E] flex flex-col justify-center items-center @if ($data->noEvaluacion != 6 || Auth()->user()->tipoUsuario == 'planeacion')
          disabled
      @endif">
          <p class="uppercase">ota</p>
          <p>Evaluación <span class="font-bold">6</span></p>
        </a>
      </div>
       
      @if (Auth::user()->tipoUsuario == 'administrador')
        <div class="flex justify-end mt-4">
          <a href="{{ url('/evaluacion-anterior', $data->id) }}" class="py-2 px-4 bg text-white bg-[#13322B] hover:bg-[#0C231E]">Modificar
            Evaluación</a>
        </div>
      @endif
    </div>

    {{-- @if (!empty($errors))
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
  @endif --}}

   

    @if ($data->formatoInstalaciones)
      <div class="p-5">
        <h2 class="text-center mb-5 uppercase text-2xl">Evidencia de Evaluación de las Instalaciones</h2>
        <div class="w-1/2 mx-auto">
            <img src="{{  $data->formatoInstalaciones }}" alt="Formato de instalaciones">
        </div>
      </div>
    @endif
@endsection

@section('script')
<script>
  const checkBtnRadios = document.querySelectorAll('.btn-check')
  const checkBoxes1 = document.querySelectorAll('.review2Checkbox')

  cargarEventListener()

  function cargarEventListener() {
    checkBtnRadios.forEach(radio => {
      radio.addEventListener('click', review1)
    });

    checkBoxes1.forEach(checkBox => {
      checkBox.addEventListener('click', review2)
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
</script>
@endsection

