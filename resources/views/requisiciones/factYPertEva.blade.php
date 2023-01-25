@extends('layouts.app')
@section('titulo')
    Evaluación de factibilidad y pertinencia
@endsection
@section('contenido')
    <div class="container-fluid pb-4 mb-4 px-4">
        <form action="{{ url('/update/elements') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-col">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8 max-h-screen">
                    <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="overflow-hidden">
                            <table id="elementsTable" class="min-w-full">
                                <thead class="border-b bg-green-900">
                                    <tr>
                                        <th class="text-sm font-bold text-white px-6 py-4 text-center">Puntaje por Criterio
                                        </th>
                                        <th class="text-sm font-bold text-white px-6 py-4 text-left">Criterio</th>
                                        <th class="text-sm font-bold text-white px-6 py-4 text-center">Indicadores</th>
                                    </tr>
                                </thead>
                                <input type="hidden" name="requisition_id" value="{{ $requisition->id }}">
                                <tbody class="bg-white">
                                    @foreach (range(1, 29) as $i)
                                        {{-- @foreach ($plans as $plan) --}}
                                        {{-- @if ($plan->plan == $i) --}}
                                        <tr class="border-b">
                                            <td class="text-sm text-center text-gray-900 font-light px-6 py-4">
                                                <p class="max-w-sm">{{ $nombresCriterios[$i - 1]['criterioPts'] }}</p>
                                            </td>
                                            <td class="text-sm text-gray-900 font-light px-6 py-4">
                                                <p class="max-w-sm">{{ $nombresCriterios[$i - 1]['criterio'] }}</p>
                                            </td>
                                            <td class="text-sm text-gray-900 font-light">
                                                <fieldset id="indicador" class="flex justify-center items-center gap-4">
                                                    <div class="flex items-center gap-1">
                                                        <label for="">Suficiente</label>
                                                        <input type="radio" name="indicador" id="">
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        <label for="">Insuficiente</label>
                                                        <input type="radio" name="indicador" id="">
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        <label for="">No</label>
                                                        <input type="radio" name="indicador" id="">
                                                    </div>
                                                </fieldset>

                                            </td>
                                        </tr>
                                        {{-- @endif --}}
                                        {{-- @endforeach --}}
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <div class="w-full">
        <textarea required class="resize-none border w-full" placeholder="Observaciones"></textarea>
    </div>

    <div class="my-3">
        <label for="" class="block font-bold mb-3 text-lg">Formato de Factibilidad y Pertinencia</label>
        <input class="w-1/2 border" name="" type="file" id="">
    </div>

    <div class="flex justify-end">
        <button class="text-white py-2 px-4 bg-green-900 hover:bg-green-700" type="submit">
            Guardar
        </button>
    </div>
    </form>
    </div>
@endsection
