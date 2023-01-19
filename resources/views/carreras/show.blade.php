@extends('layouts.app')
@section('contenido')
    <div class="md:grid md:grid-cols-2 md:gap-10 my-10">
        <div>
            <h2 class="text-center text-2xl text-gray-500 font-bold uppercase mb-2">
                {{ $career->name }}
            </h2>
            <div class="bg-white rounded-xl p-5">
                <div class="flex justify-end items-center mb-4">
                    @if (Auth::user()->typeOfUser != 'direccion')
                        <form method="POST" action="{{ route('careers.destroy', $career->id) }}"
                            class="flex justify-content-end" novalidate>
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-600 hover:bg-red-800 rounded-lg p-2 text-white" type="submit">
                                Eliminar
                            </button>
                        </form>
                    @endif
                </div>
                <form method="POST" action="{{ route('careers.update', $career->id) }}" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="my-2">
                        <label for="careerName" class="mb-2 block uppercase text-gray-500 font-bold">Nombre de la
                            Carrera</label>
                        <input name="name" type="text" class="w-full rounded-xl bg-gray-50 border-gray-200 p-3"
                            id="careerName" placeholder="Nombre de la Carrera" value="{{ $career->name }}">
                    </div>
                    <div class="my-2">
                        <label for="careerArea" class="mb-2 block uppercase text-gray-500 font-bold">Area de la
                            Carrera</label>
                        <select id="careerArea" class="w-full rounded-xl bg-gray-50 border-gray-200 p-3" name="area_id"
                            required>
                            <option selected disabled>-- Seleccione el Area --</option>
                            @foreach ($areas as $area)
                                <option @if ($area->id == $career->area_id) selected @endif value="{{ $area->id }}">
                                    {{ $area->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="my-2">
                        <label for="careerModality" class="mb-2 block uppercase text-gray-500 font-bold">Modalidad de la
                            Carrera</label>
                        <select id="careerModality" class="w-full rounded-xl bg-gray-50 border-gray-200 p-3" name="modality"
                            required>
                            <option selected disabled>-- Seleccione la Modalidad --</option>
                            <option value="Escolarizado" @if ($career->modality == 'Escolarizado') selected @endif>Escolarizado
                            </option>
                            <option value="Semiescolarizado" @if ($career->modality == 'Semiescolarizado') selected @endif>
                                Semiescolarizado
                            </option>
                            <option value="No escolarizado" @if ($career->modality == 'No escolarizado') selected @endif>No
                                escolarizado
                            </option>
                            <option value="Dual" @if ($career->modality == 'Dual') selected @endif>Dual</option>
                        </select>
                    </div>
                    <div class="md:grid md:grid-cols-2 md:gap-4">
                        <div class="my-2">
                            <label for="typeOfPeriod" class="mb-2 block uppercase text-gray-500 font-bold">Tipo de
                                Periodo</label>
                            <select id="typeOfPeriod" class="w-full rounded-xl bg-gray-50 border-gray-200 p-3"
                                name="typeOfPeriod" required>
                                <option selected disabled>-- Seleccione el tipo de periodo --</option>
                                <option value="Semestral" @if ($career->typeOfPeriod == 'Semestral') selected @endif>Semestral
                                </option>
                                <option value="Cuatrimestral" @if ($career->typeOfPeriod == 'Cuatrimestral') selected @endif>
                                    Cuatrimestral
                                </option>
                            </select>
                        </div>
                        <div class="my-2">
                            <label for="numPeriods" class="mb-2 block uppercase text-gray-500 font-bold">Numero de
                                Periodos</label>
                            <input type="number" class="w-full rounded-xl bg-gray-50 border-gray-200 p-3"
                                name="numOfPeriods" value="{{ $career->numOfPeriods }}" id="numPeriods"
                                placeholder="Numero de Periodos">
                        </div>
                    </div>


                    @if (Auth::user()->typeOfUser != 'direccion')
                        <div class="flex justify-center items-center mt-4">
                            <button class="bg-green-900 hover:bg-green-700 rounded-xl text-white py-2 w-full"
                                type="submit">
                                Actualizar
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>


        <section>
            <h2 class="text-2xl text-center text-gray-500 font-bold uppercase">Solicitudes</h2>
            <div class="flex justify-end mb-5">
                @if (Auth::user()->tipoUsuario !== 'direccion')
                    <button type="button" data-modal-toggle="new-request" type="submit"
                        class="bg-green-900 hover:bg-green-700 rounded-xl text-white py-2 px-4">
                        Nueva Solicitud
                    </button>
                @endif
            </div>


            @if (count($requisitions) != 0)
                @foreach ($requisitions as $requisition)
                    <div class="bg-white border border-gray-200 rounded-xl flex justify-between items-end p-5 mb-2">
                        <div>
                            <p class="font-bold">
                                Tipo de trámite:
                                <span class="font-light">
                                    {{ $requisition->procedure }}
                                </span>
                            </p>
                            <p class="font-bold">
                                Estado de la solicitud:
                                <span class="font-light">
                                    {{ $requisition->status }}
                                </span>
                            </p>
                            <p class="font-bold">
                                Fecha de creación:
                                <span class="font-light">
                                    {{ $requisition->created_at }}
                                </span>
                            </p>
                            <p class="font-bold">
                                Ultima Actualización
                                <span class="font-light">
                                    {{ $requisition->updated_at }}
                                </span>
                            </p>
                        </div>
                        <a class="text-green-900 hover:text-green-700 hover:underline"
                            href="{{ route('requisitions.show', $requisition->id) }}">Revisar solicitud</a>
                    </div>
                @endforeach
            @else
                <p class="text-gray-600 text-xl text-center font-bold mt-10">Todavia no hay solicitudes</p>
            @endif


            <div id="new-request" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
                <div class="relative p-4 w-full max-w-md h-full md:h-auto">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <button type="button"
                            class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white"
                            data-modal-toggle="new-request">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <div class="py-6 px-6 lg:px-8">
                            <h3 class="text-center mb-4 text-xl font-medium text-gray-900 dark:text-white">Nueva Solicitud
                            </h3>
                            <form class="mb-2" method="POST" action="{{ route('requisitions.store') }}">
                                @csrf
                                <input type="hidden" value="{{ $career->id }}" name="career_id">
                                <div class="form-floating mb-3">
                                    <label for="careerModality" class="mb-2 block uppercase text-gray-500 font-bold">Meta
                                        de la Requisición</label>
                                    <select id="requisitionGoal" class="w-full rounded-xl bg-gray-50 border-gray-200 p-3 "
                                        name="procedure" required>
                                        <option selected disabled>-- Seleccione la Meta --</option>
                                        <option value="solicitud">Solicitud</option>
                                        <option value="domicilio">Domicilio</option>
                                        <option value="planEstudios">Plan de Estudios</option>
                                    </select>
                                </div>
                                <input type="hidden" name="career_id" value="{{ $career->id }}">
                                <div class="d-grid mt-4">
                                    <button
                                        class="bg-green-900 hover:bg-green-700 rounded-xl text-white uppercase w-full p-2"
                                        type="submit">Agregar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function($) {
            $(".table-row").click(function() {
                window.document.location = $(this).data("href");
            })
        })
    </script>
@endsection
