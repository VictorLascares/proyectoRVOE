@extends('layouts.app')
@section('titulo')
    {{$institution->nombre}}
@endsection
@section('contenido')
    <div class="flex justify-end items-center mb-3">
      @if (Auth::user()->tipoUsuario != 'direccion')
        <form method="POST" action="{{ route('institutions.destroy', $institution->id) }}"
          class="d-flex justify-content-end">
          @csrf
          @method('DELETE')
          <button class="bg-red-600 hover:bg-red-800 p-2 text-white" type="submit">
            Eliminar
          </button>
        </form>
      @endif
    </div>
    <form class="row g-2 mb-5" action="{{ route('institutions.update', $institution->id) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="md:flex md:justify-between md:gap-4">
            <div class="flex-1 flex justify-center items-center border mb-4 md:mb-0 py-4 md:py-0">
                <img src="{{ asset('img/institutions/' . $institution->logotipo) }}" alt="Logo de la Institución">
            </div>
            <div class="flex-1 md:grid md:grid-cols-2 md:gap-2 border p-2">
                <div class="md:col-span-2 mb-5 md:mb-0">
                    <label for="institutionName" class="mb-2 block uppercase text-gray-500 font-bold">Nombre de la Institución</label>
                    <input name="nombre" type="text" class="w-full border p-3" id="institutionName"
                        placeholder="Nombre de la Institución" value="{{ $institution->nombre }}">
                </div>
                <div class="mb-5 md:mb-0">
                    <label for="institutionName" class="mb-2 block uppercase text-gray-500 font-bold">Nombre del Director</label>
                    <input name="director" type="text" class="w-full border p-3" id="institutionName" placeholder="Nombre del Director"
                        value="{{ $institution->director }}">
                </div>
                <div class="mb-5 md:mb-0">
                    <label for="municipality" class="mb-2 block uppercase text-gray-500 font-bold">Municipio</label>
                    <select name="municipalitie_id" class="w-full border p-3" id="municipality"
                        aria-label="Floating label select example">
                        <option selected disabled>Selecciona un municipio</option>
                        @foreach ($municipalities as $municipality)
                        <option @if ($municipality->id == $institution->municipalitie_id) selected @endif value="{{ $municipality->id }}">
                            {{ $municipality->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label for="institutionLogo" class="mb-2 block uppercase text-gray-500 font-bold">Logo de la Institución</label>
                    <input type="file" class="w-full border" id="institutionLogo" name="logotipo">
                </div>
                <div class="md:col-span-2 mb-5 md:mb-0">
                    <label for="address" class="mb-2 block uppercase text-gray-500 font-bold">Dirección</label>
                    <textarea name="direccion" class="w-full border p-3  resize-none" id="address"
                        placeholder="Dirección">{{ $institution->direccion }}</textarea>
                </div>
                
            </div>
        </div>
        @if (Auth::user()->tipoUsuario == 'planeacion' || Auth::user()->tipoUsuario == 'administrador')
            <div class="flex justify-center mt-4">
                <button class="bg-[#13322B] hover:bg-[#0C231E] text-white py-2 px-4" type="submit">
                    Actualizar
                </button>
            </div>
        @endif
    </form>

    <section class="pb-5">
      <div class="flex justify-between items-center">
        <h2 class="text-3xl">Carreras</h2>
        @if (Auth::user()->tipoUsuario == 'planeacion' || Auth::user()->tipoUsuario == 'administrador')
          <button type="button" data-modal-toggle="new-career" type="submit"
            class="bg-[#13322B] hover:bg-[#0C231E] text-white py-2 px-4">
            Nueva Carrera
          </button>
        @endif
      </div>

      @if (count($careers) != 0)
        <div class="mt-3">
            @foreach ($careers as $career)
                <a href="{{ route('careers.show', $career->id) }}"
                    class="flex justify-between items-center hover:bg-gray-300 p-3 border">
                    {{ $career->nombre }}
                    @foreach ($areas as $area)
                        @if ($area->id == $career->area_id)
                            <span>{{ $area->nombre }}</span>
                        @endif
                    @endforeach
                </a>
            @endforeach
            <div class="my-10">{{ $careers->links('pagination::tailwind') }}</div>
        </div>
      @else
        <p class="text-gray-600 text-xl text-center font-bold mt-10">Todavia no hay carreras</p>
      @endif
    </section>
    <div id="new-career" tabindex="-1" aria-hidden="true"
  class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
        <div class="relative p-4 w-full max-w-md h-full md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button"
                    class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white"
                    data-modal-toggle="new-career">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                    </svg>
                </button>
            <div class="py-6 px-6 lg:px-8">
                <h3 class="text-center mb-4 uppercase text-gray-500 font-bold dark:text-white">Nueva Solicitud</h3>
                <form class="mb-2" method="POST" action="{{ route('careers.store', $institution->id) }}">
                    @csrf
                    <div class="mb-5">
                        <label for="careerName" class="mb-2 block uppercase text-gray-500 font-bold">Nombre de la Carrera</label>
                        <input type="text" class="w-full border p-3" id="carreerName" name="nombre"
                        placeholder="Nombre de la Carrera">
                    </div>
                    <div class="mb-5">
                        <label for="careerArea" class="mb-2 block uppercase text-gray-500 font-bold">Área de Estudio</label>
                        <select id="careerArea" class="w-full border p-3" name="area_id" required>
                            <option selected disabled>-- Seleccione el área de estudios --</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-5">
                        <label for="careerModality" class="mb-2 block uppercase text-gray-500 font-bold">Modalidad de la Carrera</label>
                        <select id="careerModality" class="w-full border p-3" name="modalidad" required>
                            <option selected disabled>-- Seleccione la Modalidad --</option>
                            <option value="Presencial">Presencial</option>
                            <option value="Distancia">Distancia</option>
                            <option value="Hibrida">Hibrida</option>
                        </select>
                    </div>
                    <div class="mb-5">
                        <label for="careerDuration" class="mb-2 block uppercase text-gray-500 font-bold">Duración de la Carrera</label>
                        <input type="number" class="w-full border p-3" name="duracion" id="careerDuration"
                        placeholder="Duración de la Carrera">
                    </div>
                    <input type="hidden" name="institution_id" value="{{ $institution->id }}">
                    <button class="bg-[#13322B] hover:bg-[#0C231E] text-white w-full p-3 uppercase" type="submit">Agregar</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('footer')
  
@endsection
