@extends('layouts.app')
@section('titulo')
    Evaluación de Instalaciones
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
                                        <th class="text-sm font-bold text-white px-6 py-4 text-left">#</th>
                                        <th class="text-sm font-bold text-white px-6 py-4 text-left">Indicador</th>
                                        <th class="text-sm font-bold text-white px-6 py-4 text-left">Existe</th>
                                    </tr>
                                </thead>
                                <input type="hidden" name="requisition_id" value="{{ $requisition->id }}">
                                <tbody>
                                    @foreach (range(1, 26) as $i)
                                        @foreach ($elements as $element)
                                            @if ($element->element == $i)
                                                <tr class="border-b">
                                                    <td
                                                        class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                                        {{ $i }}
                                                    </td>
                                                    <td class="text-sm text-gray-900 font-light px-6 py-4">
                                                        <p class="max-w-sm">{{ $elementName[$i - 1] }}</p>
                                                    </td>
                                                    <td>
                                                        <div class="flex justify-start items-center gap-4" role="group">
                                                            <div class="flex justify-center items-center gap-2">
                                                                <input type="radio"
                                                                    class="btn-check checked:bg-green-600 transition duration-200 focus:ring-green-900 cursor-pointer"
                                                                    name="elemento{{ $i }}"
                                                                    value="{{ $element->existing }}"
                                                                    id="btnYes-{{ $element->id }}" autocomplete="off"
                                                                    @if ($element->existing) checked @endif>
                                                                <label class="uppercase"
                                                                    for="btnYes-{{ $element->id }}">Si</label>
                                                            </div>
                                                            <div class="flex justify-center items-center gap-2">
                                                                <input type="radio"
                                                                    class="btn-check btn-No checked:bg-green-600 transition duration-200 focus:ring-green-900 cursor-pointer"
                                                                    name="elemento{{ $i }}"
                                                                    id="btnNo-{{ $element->id }}"
                                                                    value="{{ $element->existing }}" autocomplete="off"
                                                                    @if (!$element->existing) checked @endif>
                                                                <label class="uppercase"
                                                                    for="btnNo-{{ $element->id }}">No</label>
                                                            </div>
                                                        </div>
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
        <textarea name="elementoC" rows="5" class="resize-none border-gray-200 rounded-xl w-full"
            placeholder="Observaciones">{{ $elementComment->observation }}</textarea>
    </div>
    <div class="my-3">
        <label for="building-format" class="block font-bold mb-3 text-lg">Formato de Instalaciones</label>
        <input class="w-1/2 border-gray-200 border bg-white rounded-xl" name="facilitiesFormat" type="file"
            accept="application/pdf" id="building-format" @if ($requisition->facilitiesFormat == null) required @endif>
    </div>
    <div class="flex justify-end">
        <button class="text-white py-2 px-4 bg-green-900 hover:bg-green-700" type="submit">
            Guardar
        </button>
    </div>
    </form>
    </div>
@endsection
@section('script')
    <script>
        const botones = document.querySelectorAll('.btn-check')

        iniciarEventos()

        function iniciarEventos() {
            botones.forEach(elemento => {
                elemento.addEventListener('click', evaluarEstado)
            });
        }

        function evaluarEstado(e) {
            const checkBox = e.target
            if (checkBox.classList.contains('btn-No')) {
                checkBox.value = !this.checked
            } else {
                checkBox.value = this.checked
            }

            //   $(document).ready(function() {
            //     $('#elementsTable').DataTable({
            //       "scrollY": "50vh",
            //       "scrollCollapse": true,
            //     });
            //     $('.dataTables_length').addClass('bs-select');
            //   });
        }
    </script>
@endsection
