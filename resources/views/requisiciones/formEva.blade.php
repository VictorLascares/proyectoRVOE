@extends('layouts.app')
@section('titulo')
    @if ($noEvaluation == 1)
        Revisión de existencia de formatos 1
    @elseif ($noEvaluation == 2)
        Revisión del contenido de los formatos 2
    @else
        Revisión del contenido de los formatos 3
    @endif
@endsection
@section('contenido')
    <div class="my-20">
        <form method="POST" action="{{ url('/update/formats') }}">
            @csrf

            <input type="hidden" name="requisition_id" value="{{ $requisition->id }}">
            <input type="hidden" name="noEvaluation" value="{{ $noEvaluation }}">
            {{-- Revision 1 --}}
            @if ($noEvaluation == 1)
                <table class="min-w-full">
                    <thead class="border-b bg-green-900">
                        <tr>
                            <th class="text-sm font-bold text-white px-6 py-4 text-left">#</th>
                            <th class="text-sm font-bold text-white px-6 py-4 text-center">Existe</th>
                            <th class="text-sm font-bold text-white px-6 py-4 text-center">Nombre del formato</th>
                            <th class="text-sm font-bold text-white px-6 py-4 text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (range(1, 5) as $i)
                            @foreach ($formats as $format)
                                @if ($format->format == $i)
                                    <tr class="border-b">
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $i }}</td>
                                        <td class="text-center ">
                                            <input name="anexo{{ $i }}" value="{{ $format->valid }}"
                                                class="reviewCheckbox form-check-input" type="checkbox"
                                                id="check-review-{{ $format->id }}">
                                        </td>
                                        <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap text-center">
                                            <label class="form-check-label" for="check-review2-{{ $format->id }}">
                                                {{ $formatNames[$i - 1] }}
                                            </label>
                                        </td>
                                        <td class="text-sm px-6 py-4 whitespace-nowrap text-center">
                                            @if ($format->valid)
                                                <p class="text-green-500">Registrado</p>
                                            @elseif ($format->justification)
                                                <p class="text-red-500">{{ $format->justification }}</p>
                                            @else
                                                <p class="text-red-500">No registrado</p>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            @elseif($noEvaluation == 2 || $noEvaluation == 3)
                {{-- Revision 2 y 3 --}}
                <div class="grid grid-cols-2 gap-4 w-full">
                    @foreach (range(1, 5) as $i)
                        @foreach ($formats as $format)
                            @if ($format->format == $i)
                                <div class="flex flex-col gap-2">
                                    <div class="flex gap-2 items-center">
                                        <input name="anexo{{ $i }}" value="{{ $format->valid }}"
                                            class="reviewCheckbox form-check-input @if ($noEvaluation ==2 && $format->justification == "No se encuentra el formato") disabled @endif" type="checkbox"
                                            id="check-review-{{ $format->id }}">
                                        <label class="form-check-label" for="check-review2-{{ $format->id }}">
                                            {{ $formatNames[$i - 1] }}
                                        </label>

                                        @if ($format->valid)
                                            <p class="text-green-500">&#10003; {{ $format->justification}}</p>
                                        @else
                                            <p class="text-red-500">({{ $format->justification }})</p>
                                        @endif
                                    </div>
                                    <textarea id="just-review-{{$format->id}}" name="anexo{{ $i }}j" rows="3"
                                        class="w-full resize-none focus:ring-0 border-2 border-gray-200 rounded-xl @if ($noEvaluation ==2 && $format->justification == "No se encuentra el formato") disabled @endif" placeholder="Observacion"></textarea>
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                </div>
            @endif

            <div class="@if ($requisition->evaNum > 2 && Auth()->user()->typeOfUser == 'planeacion') disabled @endif flex justify-end items-center mt-4">
                <button class="text-white bg-green-900 hover:bg-green-700 px-10 py-3" type="submit">Guardar</button>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script>
        const checkboxes = document.querySelectorAll('.reviewCheckbox')

        window.onload = function() {
            //   $('input[type=checkbox]').prop('checked', false);

            setRequired()
        }

        checkboxes.forEach(element => {
            element.addEventListener('change', e => {
                e.target.value = e.target.checked
                setRequired()
            })
        });


        function setRequired() {
            checkboxes.forEach(element => {
                const id = element.id.split('-')[2]
                const justificacion = document.querySelector(`#just-review-${id}`)
                if (justificacion !== null) {
                    if (element.value == 'true') {
                        justificacion.required = false
                    } else {
                        justificacion.required = true
                    }
                }
            })
        }
    </script>
@endsection
