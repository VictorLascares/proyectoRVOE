@extends('layouts.app')
@section('titulo')
    Evaluación de factibilidad y pertinencia
@endsection
@section('contenido')
    <div class="container-fluid pb-4 mb-4 px-4">
        <form action="{{ url('/update/opinions') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-col">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8 max-h-screen">
                    <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="overflow-hidden">
                            <table id="elementsTable" class="min-w-full">
                                <thead class="border-b bg-green-900">
                                    <tr>
                                        <th class="text-sm font-bold text-white px-6 py-4 text-left">Criterio</th>
                                        <th class="text-sm font-bold text-white px-6 py-4 text-center">Indicadores</th>
                                    </tr>
                                </thead>
                                <input type="hidden" name="requisition_id" value="{{ $requisition->id }}">
                                <tbody class="bg-white">
                                    @foreach (range(1, 29) as $i)
                                        @foreach ($opinions as $opinion)
                                            @if ($opinion->opinion == $i)
                                                <tr class="border-b">
                                                    <td class="text-sm text-start text-gray-900 font-light px-6 py-4">
                                                        <p class="max-w-sm">{{ $opinionNames[$i - 1] }}</p>
                                                    </td>
                                                    <td class="text-sm text-center text-gray-900 font-light">
                                                        <select name="opinion{{$i}}" id="">
                                                            <option value="suficiente">Suficiente</option>
                                                            <option value="insuficiente">Insuficiente</option>
                                                            <option value="na">No</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <div class="w-full">
        <textarea name="opinionC" class="resize-none border w-full" placeholder="Observaciones"></textarea>
    </div>

    <div class="my-3">
        <label for="opinionFormat" class="block font-bold mb-3 text-lg">Formato de Factibilidad y Pertinencia</label>
        <input class="w-1/2 border" name="opinionFormat" type="file" accept="application/pdf" id="opinionFormat">
    </div>

    <div class="flex justify-end">
        <button class="text-white py-2 px-4 bg-green-900 hover:bg-green-700" type="submit">
            Guardar
        </button>
    </div>
    </form>
    </div>
@endsection
