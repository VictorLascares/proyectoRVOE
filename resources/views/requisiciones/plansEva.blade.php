@extends('layouts.app')
@section('titulo')
    Evaluación de Planes y programas de estudio
@endsection
@section('contenido')
    <div class="container-fluid pb-4 mb-4 px-4">
        <form action="{{ url('/update/plans') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-col">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8 max-h-screen">
                    <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="overflow-hidden">
                            <table id="elementsTable" class="min-w-full">
                                <thead class="border-b bg-green-900">
                                    <tr>
                                        <th class="text-sm font-bold text-white px-6 py-4 text-left">#</th>
                                        <th class="text-sm font-bold text-white px-6 py-4 text-left">Aspectos</th>
                                        <th class="text-sm font-bold text-white px-6 py-4 text-center">Puntaje</th>
                                    </tr>
                                </thead>
                                <input type="hidden" name="requisition_id" value="{{ $requisition->id }}">
                                <tbody class="bg-white">
                                    @foreach (range(1, 20) as $i)
                                        @foreach ($plans as $plan)
                                            @if ($plan->plan == $i)
                                                <tr class="border-b">
                                                    <td
                                                        class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                                        {{ $i }}
                                                    </td>
                                                    <td class="text-sm text-gray-900 font-light px-6 py-4">
                                                        <p class="max-w-sm">{{ $planNames[$i - 1] }}</p>
                                                    </td>
                                                    <td class="text-center">
                                                        <select class="border-gray-200 bg-gray-50"
                                                            name="plan{{ $i }}">
                                                            <option value="cumple">Cumple</option>
                                                            <option value="parcialmente">Parcialmente</option>
                                                            <option value="na">No cumple</option>
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
        <textarea name="planC" class="resize-none border-gray-200 w-full" placeholder="Observaciones"></textarea>
    </div>

    <div class="my-3">
        <label for="planFormat" class="block font-bold mb-3 text-lg">Formato de programa de estudios</label>
        <input class="w-1/2 border border-gray-200" name="planFormat" type="file" id="planFormat">
    </div>

    <div class="flex justify-end mt-10">
        <button class="text-white py-2 px-4 bg-green-900 hover:bg-green-700" type="submit">
            Guardar
        </button>
    </div>
    </form>
    </div>
@endsection
