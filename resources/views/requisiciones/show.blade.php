@extends('layouts.app')
@section('titulo')
  Información de la Solicitud
@endsection
@section('contenido')
  <div class="flex justify-center items-center  mb-4 py-4">
    <img src="{{ $institution->logotipo }}" alt="Logotipo Institución">
  </div>
  <div class="md:grid md:grid-cols-2 md:gap-4">
    <div class="border rounded-md shadow-lg">
      <div class="border-b">
        <h2 class="text-gray-600 md:col-span-2 text-center p-3 text-xl font-bold uppercase">Información de la Institución
        </h2>
      </div>
      <p class="p-2 text-lg font-bold">Nombre: <span class="font-normal">{{ $institution->nombre }}</span></p>
      <p class="p-2 text-lg font-bold">Titular: <span class="font-normal">{{ $institution->titular }}</span></p>
      <p class="p-2 text-lg font-bold">Representante legal o Asociación Civil: <span
          class="font-normal">{{ $institution->repLegal }}</span></p>
      <p class="p-2 text-lg font-bold">Correo Institucional: <span class="font-normal">{{ $institution->email }}</span>
      </p>
      <p class="p-2 text-lg font-bold">Dirección: <span class="font-normal">{{ $institution->direccion }}</span></p>
    </div>
    <div class="border mt-4 md:mt-0 rounded-md shadow-lg">
      <div class="border-b">
        <h2 class="text-gray-600 text-center p-3 text-xl font-bold uppercase">Información de la Carrera</h2>
      </div>
      <p class="p-2 text-lg font-bold">Nombre: <span class="font-normal">{{ $career->nombre }}</span></p>
      <p class="p-2 text-lg font-bold">Modalidad: <span class="font-normal">{{ $career->modalidad }}</span></p>
      <p class="p-2 text-lg font-bold">Duración: <span class="font-normal">{{ $career->numPeriodo }} @if ($career->tipoPeriodo == 'Semestral')
            Semestres
          @elseif($career->tipoPeriodo == 'Cuatrimestral')
            Cuatrimestres
          @endif
        </span></p>
      <p class="p-2 text-lg font-bold">Área: <span class="font-normal">{{ $area->nombre }}</span></p>
    </div>
  </div>

  <div class="md:flex md:justify-between md:items-center gap-4 mt-4 mb-4">
    @if ($data->rvoe)
      <div class="md:flex gap-4">
        <p class="font-bold">Rvoe o Acuerdo: <span class="font-normal">{{ $data->rvoe }}</span></p>
        <p class="font-bold">Estado: <span class="font-normal">{{ $data->estado }}</span></p>
      </div>
    @endif
    <div>
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
      <a href="{{ route('evaluate.formats', ['requisition_id' => $data->id, 'no_evaluation' => 1]) }}" type="button" id="evaFormatos"
        class="rounded-md p-3 text-white formatos bg-[#13322B] hover:bg-[#0C231E] @if ($data->noEvaluacion == 1) bg-blue-500 hover:bg-blue-800 @endif flex flex-col justify-center items-center @if (Auth()->user()->tipoUsuario == 'direccion') disabled @endif">
        <p class="uppercase">Formatos</p>
        <p>Revisión <span class="font-bold">1</span></p>
      </a>
      <a href="{{ route('evaluate.formats', ['requisition_id' => $data->id, 'no_evaluation' => 2]) }}"
        class="rounded-md p-3 text-white formatos bg-[#13322B] hover:bg-[#0C231E] @if ($data->noEvaluacion == 2) bg-blue-500 hover:bg-blue-800 @endif flex flex-col justify-center items-center @if ($data->noEvaluacion < 2 || Auth()->user()->tipoUsuario == 'direccion') disabled @endif">
        <p class="uppercase">Formatos</p>
        <p>Revisión <span class="font-bold">2</span></p>
      </a>
      <a href="{{ route('evaluate.formats', ['requisition_id' => $data->id, 'no_evaluation' => 3]) }}"
        class="rounded-md p-3 text-white formatos bg-[#13322B] hover:bg-[#0C231E] @if ($data->noEvaluacion == 3) bg-blue-500 hover:bg-blue-800 @endif flex flex-col justify-center items-center @if (Auth()->user()->tipoUsuario == 'planeacion' || $data->estado == 'rechazado') disabled @endif">
        <p class="uppercase">Formatos</p>
        <p>Revisión <span class="font-bold">3</span></p>
      </a>
      <a href="{{ url('/evaluate/elements', $data->id) }}"
        class="rounded-md p-3 text-white bg-[#13322B] hover:bg-[#0C231E] @if ($data->noEvaluacion == 4) bg-blue-500 hover:bg-blue-800 @endif flex flex-col justify-center items-center @if ($data->noEvaluacion < 4 || Auth()->user()->tipoUsuario == 'planeacion') disabled @endif">
        <p class="uppercase">Instalaciones</p>
        <p>Evaluación <span class="font-bold">2</span></p>
      </a>
      <a href="{{ url('/evaluate/plans', $data->id) }}"
        class="rounded-md p-3 text-white bg-[#13322B] hover:bg-[#0C231E] @if ($data->noEvaluacion == 5) bg-blue-500 hover:bg-blue-800 @endif flex flex-col justify-center items-center @if ($data->noEvaluacion < 5 || Auth()->user()->tipoUsuario == 'planeacion') disabled @endif">
        <p class="uppercase">Planes</p>
        <p>Evaluación <span class="font-bold">3</span></p>
      </a>
      @if ($data->estado == 'rechazado' && $data->noEvaluacion == 6)
        <a download="OTAReq-{{ $data->id }}" href="{{ url('/download/status', $data->id) }}" class="rounded-md p-3 text-white bg-[#13322B] hover:bg-[#0C231E] @if ($data->noEvaluacion == 6) bg-blue-500 hover:bg-blue-800 @endif flex flex-col justify-center items-center @if ($data->noEvaluacion < 6 || Auth()->user()->tipoUsuario == 'planeacion') disabled @endif">
            <p>Descargar</p>
            <p class="uppercase">ESTADO</p>
        </a>  
      @else
        <a download="OTAReq-{{ $data->id }}" href="{{ url('/download', $data->id) }}"
            class="rounded-md p-3 text-white bg-[#13322B] hover:bg-[#0C231E] @if ($data->noEvaluacion == 6) bg-blue-500 hover:bg-blue-800 @endif flex flex-col justify-center items-center @if ($data->noEvaluacion < 6 || Auth()->user()->tipoUsuario == 'planeacion') disabled @endif">
            <p>Descargar</p>
            <p class="uppercase">ota</p>
        </a>  
      @endif
      
    </div>
    <form action="{{ route('solicitud', $data->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div>
        <textarea name="evaluacion" class="w-full resize-none"
        placeholder="Si o No"></textarea>
      </div>
      <div class="flex justify-center items-center mt-4">
        <button class="text-white bg-[#13322B] hover:bg-[#0C231E] px-10 py-3" type="submit">Pasar al administrador</button>
      </div>
    </form>
    
    @if (Auth::user()->tipoUsuario == 'administrador')
      <div class="flex justify-end mt-4">
        <a href="{{ url('/evaluacion-anterior', $data->id) }}"
          class="rounded-md py-2 px-4 bg text-white bg-[#13322B] hover:bg-[#0C231E]">Modificar
          Evaluación</a>
      </div>
    @endif
  </div>

  @if (!empty($errors))
    <div class="pb-4 w-4/6 mx-auto text-center">
      @foreach ($errors as $error)
        @if ($error->justificacion)
          <p class="bg-red-400 p-4 text-red-900 mb-4">Revision {{ $data->noEvaluacion - 1 }}.- Evaluación de Formatos -
            {{ $error->justificacion }} ({{ $formatNames[$error->formato - 1] }})</p>
        @elseif ($error->observacion)
          <p class="bg-red-400 p-4 text-red-900">Evaluación de las Instalaciones.- {{ $error->observacion }} (Elemento
            {{ $error->elemento }})</p>
        @elseif ($error->comentario)
          <p class="bg-red-400 p-4 text-red-900">Evaluación de los Planes.-{{ $error->comentario }} (Plan
            {{ $error->plan }})</p>
        @endif
      @endforeach
    </div>
  @endif



  @if ($data->formatoInstalaciones)
    <div class="p-5">
      <h2 class="text-center mb-5 uppercase text-2xl">Evidencia de Evaluación de las Instalaciones</h2>
      <div class="w-1/2 mx-auto">
        <img src="{{ $data->formatoInstalaciones }}" alt="Formato de instalaciones">
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
