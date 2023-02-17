@extends('layouts.app')
@section('titulo')
    {{ $institution->name }}
@endsection
@section('contenido')
    <div class="flex justify-end items-center mb-3">
        @if (Auth::user()->typeOfUser != 'direccion')
            <form method="POST" action="{{ route('institutions.destroy', $institution->id) }}"
                class="d-flex justify-content-end">
                @csrf
                @method('DELETE')
                <button class="bg-red-600 hover:bg-red-800 rounded-lg p-2 text-white" type="submit">
                    Eliminar
                </button>
            </form>
        @endif
    </div>

    <div class="md:grid md:grid-cols-2 md:gap-5 mb-10">
        <div class="flex-1 flex justify-center items-center rounded-xl border mb-4 md:mb-0 py-4 md:py-0">
            <img src="{{ $institution->logo }}" alt="Logo de la Institución">
        </div>
        <div class="bg-white rounded-xl p-5">
            <form action="{{ route('institutions.update', $institution->id) }}" method="POST" enctype="multipart/form-data" novalidate>
                @method('PUT')
                @csrf
                <div class="md:col-span-2 my-3">
                    <label for="institutionName" class="mb-2 block uppercase text-gray-500 font-bold">Nombre de la
                        Institución</label>
                    <input name="name" type="text" class="w-full rounded-xl bg-gray-50 border-gray-200 p-3" id="institutionName"
                        placeholder="Nombre de la Institución" value="{{ $institution->name }}">
                </div>
                <div class="md:col-span-2 my-3">
                    <label for="owner" class="mb-2 block uppercase text-gray-500 font-bold">Titular</label>
                    <input name="owner" type="text" class="w-full rounded-xl bg-gray-50 border-gray-200 p-3" id="owner" placeholder="Nombre del Titular" value="{{ $institution->owner }}">
                </div>
                <div class="md:col-span-2 my-3">
                    <label for="legalRep" class="mb-2 block uppercase text-gray-500 font-bold">Representante Legal o
                        Sociedad Civil</label>
                    <input name="legalRep" type="text" class="w-full rounded-xl bg-gray-50 border-gray-200 p-3" id="legalRep"
                        value="{{ $institution->legalRep }}">
                </div>
                <div class="my-3">
                    <label for="email" class="mb-2 block uppercase text-gray-500 font-bold">Correo
                        Institucional</label>
                    <input name="email" type="email" class="w-full rounded-xl bg-gray-50 border-gray-200 p-3" id="email"
                        value="{{ $institution->email }}">
                </div>
                <div class="my-3">
                    <label for="municipality" class="mb-2 block uppercase text-gray-500 font-bold">Municipio</label>
                    <select name="municipalitie_id" class="w-full rounded-xl bg-gray-50 border-gray-200 p-3" id="municipality"
                        aria-label="Floating label select example">
                        <option selected disabled>Selecciona un municipio</option>
                        @foreach ($municipalities as $municipality)
                            <option @if ($municipality->id == $institution->municipalitie_id) selected @endif value="{{ $municipality->id }}">
                                {{ $municipality->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2 my-3">
                    <label for="address" class="mb-2 block uppercase text-gray-500 font-bold">Dirección</label>
                    <textarea name="address" class="w-full rounded-xl bg-gray-50 border-gray-200 p-3  resize-none" id="address" placeholder="Dirección">{{ $institution->address }}</textarea>
                </div>
                <div class="md:col-span-2 my-3">
                    <label for="institutionLogo" class="mb-2 block uppercase text-gray-500 font-bold">Logo de la
                        Institución</label>
                    <input type="file" class="w-full rounded-xl bg-gray-50 border-gray-200" id="institutionLogo" name="logo">
                </div>
                @if (Auth::user()->typeOfUser == 'planeacion' || Auth::user()->typeOfUser == 'administrador')
                    <div class="flex justify-center mt-4">
                        <button class="bg-green-900 hover:bg-green-700 rounded-lg text-white py-2 px-10" type="submit">
                            Actualizar
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>


    <section>
        <div class="flex justify-between items-center">
            <h2 class="text-3xl">Carreras</h2>
            @if (Auth::user()->typeOfUser == 'planeacion' || Auth::user()->typeOfUser == 'administrador')
                <button type="button" data-modal-toggle="new-career" type="submit"
                    class="bg-green-900 hover:bg-green-700 rounded-lg text-white py-2 px-4">
                    Nueva Carrera
                </button>
            @endif
        </div>

        @if (count($careers) != 0)
            <div class="mt-3">
                @foreach ($careers as $career)
                    <a href="{{ route('careers.show', $career->id) }}"
                        class="flex justify-between items-center hover:bg-gray-300 p-3 border-b">
                        {{ $career->name }}
                        @foreach ($areas as $area)
                            @if ($area->id == $career->area_id)
                                <span>{{ $area->name }}</span>
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
        <div class="relative p-4 h-full md:h-auto">
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
                <div class="p-4">
                    <h3 class="text-center mb-4 uppercase text-gray-500 font-bold dark:text-white">Nueva Carrera</h3>
                    <form class="mb-2" method="POST" action="{{ route('careers.store', $institution->id) }}">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label for="careerName" class="mb-2 block uppercase text-gray-500 font-bold">Nombre de la
                                    Carrera</label>
                                <input type="text" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3" id="carreerName" name="name" placeholder="Nombre de la Carrera">
                            </div>
                            <div>
                                <label for="careerArea" class="mb-2 block uppercase text-gray-500 font-bold">Área de
                                    Estudio</label>
                                <select id="careerArea" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3" name="area_id" required>
                                    <option selected disabled>Seleccione área de estudios</option>
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="modality" class="mb-2 block uppercase text-gray-500 font-bold">Modalidad de
                                    la Carrera</label>
                                <select id="modality" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3" name="modality" required>
                                    <option selected disabled>Seleccione la Modalidad</option>
                                    <option value="Escolarizado">Escolarizado</option>
                                    <option value="Semiescolarizado">Semiescolarizado</option>
                                    <option value="No escolarizado">No escolarizado</option>
                                    <option value="Dual">Dual</option>
                                </select>
                            </div>
                            <div>
                                <label for="typePeriod" class="mb-2 block uppercase text-gray-500 font-bold">Tipo de
                                    Periodo</label>
                                <select id="typePeriod" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3" name="typeOfPeriod" required>
                                    <option selected disabled>Seleccione tipo de Periodo</option>
                                    <option value="Semestral">Semestral</option>
                                    <option value="Cuatrimestral">Cuatrimestral</option>
                                </select>
                            </div>
                            <div>
                                <label for="numPeriods" class="mb-2 block uppercase text-gray-500 font-bold">Numero de
                                    Periodos</label>
                                <input type="number" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3" name="numOfPeriods" id="numPeriods"
                                    placeholder="Duración de la Carrera">
                            </div>
                        </div>
                        <input type="hidden" name="institution_id" value="{{ $institution->id }}">
                        <button class="bg-green-900 hover:bg-green-700 text-white w-full p-3 uppercase mt-4"
                            type="submit">Agregar</button>
                    </form>
                </div>
            </div>
        </div>
    @endsection
