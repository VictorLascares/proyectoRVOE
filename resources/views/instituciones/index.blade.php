@extends('layouts.app')
@section('titulo')
  Instituciones
@endsection
@section('contenido')
    @if (count($institutions) != 0)
        <div class="flex justify-end items-center mb-3">
            @if (Auth::user()->tipoUsuario != 'direccion')
                <button type="button" data-modal-toggle="new-institution" class="bg-[#13322B] hover:bg-[#0C231E] text-white p-3">Nueva
                    Institución
                </button>
            @endif
        </div>
        <div class="grid grid-cols-4 gap-4">
            @foreach ($institutions as $institution)
                <a class="text-decoration-none institution text-dark relative" href="{{ route('institutions.show', $institution) }}">
                <div class="p-5">
                    <div class="">
                        <img src="{{ asset('img/institutions/' . $institution->logotipo) }}" class="" alt="Logo de la Institución">
                    </div>
                </div>
                <div class="absolute top-0 institution__overlay flex flex-col justify-center items-center p-4 gap-4">
                    <p class="text-lg font-bold uppercase text-center text-gray-400">{{ $institution->nombre }}</p>
                    <p class="text-sm text-center text-gray-400">Director(a): {{ $institution->director }}</p>
                </div>
                </a>
            @endforeach
        </div>
        <div class="my-10">{{ $institutions->links('pagination::tailwind') }}</div>
    @else
        <p class="text-gray-600 text-xl text-center font-bold mt-10">Todavia no hay instituciones</p>
    @endif

  <div id="new-institution" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full max-w-md h-full md:h-auto">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
        <button type="button"
          class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white"
          data-modal-toggle="new-institution">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
              clip-rule="evenodd"></path>
          </svg>
        </button>
        <div class="py-6 px-6 lg:px-8">
          <h3 class="text-center mb-4 uppercase text-gray-500 font-bold dark:text-white">Nueva Solicitud</h3>
          <form class="mb-2" method="POST" action="{{ route('institutions.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-5">
              <input type="text" class="w-full border p-3" id="institutionName" name="nombre"
                placeholder="Nombre de la Institución">
              @error('nombre')
                <p class="text-red-600 text-sm">{{ $message }}</p>
              @enderror
            </div>
            <div class="mb-5">
              <input type="text" class="w-full border p-3" name="director" id="directorName"
                placeholder="Nombre del Director">
              @error('director')
                <p class="text-red-600 text-sm">{{ $message }}</p>
              @enderror
            </div>
            <div class="mb-5">
              <select name="municipalitie_id" class="w-full border p-3" id="municipality"
                aria-label="Floating label select example">
                <option selected disabled>Selecciona un municipio</option>
                @foreach ($municipalities as $municipality)
                  <option value="{{ $municipality->id }}">{{ $municipality->nombre }}</option>
                @endforeach
              </select>
              @error('municipalitie_id')
                <p class="text-red-600 text-sm">{{ $message }}</p>
              @enderror
            </div>
            <div class="mb-5">
              <textarea name="direccion" class="w-full border p-3 resize-none" id="address" placeholder="Dirección"></textarea>
              @error('direccion')
                <p class="text-red-600 text-sm">{{ $message }}</p>
              @enderror
            </div>
            <div class="mb-5">
              <label for="institutionLogo" class="mb-2 block text-gray-500 font-bold">Logo de la
                Institución</label>
              <input type="file" class="form-control" id="institutionLogo" name="logotipo">
              @error('logotipo')
                <p class="text-red-600 text-sm">{{ $message }}</p>
              @enderror
            </div>
            <button class="bg-[#13322B] hover:bg-[#0C231E] text-white w-full p-3 uppercase"
              type="submit">Agregar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
