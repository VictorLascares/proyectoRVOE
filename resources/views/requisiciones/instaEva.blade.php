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
                <thead class="border-b bg-[#13322B]">
                    <tr>
                    <th class="text-sm font-bold text-white px-6 py-4 text-left">#</th>
                    <th class="text-sm font-bold text-white px-6 py-4 text-left">Elemento</th>
                    <th class="text-sm font-bold text-white px-6 py-4 text-left">Existencia</th>
                    <th class="text-sm font-bold text-white px-6 py-4 text-left">Observacion</th>
                    </tr>
                </thead>
                <input type="hidden" name="requisition_id" value="{{ $requisition->id }}">
                <tbody>
                    @foreach (range(1, 52) as $i)
                    @foreach ($elements as $element)
                        @if ($element->elemento == $i)
                        <tr class="border-b">
                            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            {{ $i }}
                            </td>
                            <td class="text-sm text-gray-900 font-light px-6 py-4">
                                <p class="max-w-sm">{{ $elementName[$i - 1] }}</p>
                            </td>
                            <td>
                            <div class="flex justify-start items-center gap-4" role="group">
                                <div class="flex justify-center items-center gap-2">
                                    <input type="radio" class="btn-check" name="elemento{{ $i }}"
                                    value="{{ $element->existente }}" id="btnYes-{{ $element->id }}" autocomplete="off"
                                    @if ($element->existente) checked @endif>
                                    <label class="uppercase" for="btnYes-{{ $element->id }}">Si</label>
                                </div>
                                <div class="flex justify-center items-center gap-2">
                                    <input type="radio" class="btn-check btn-No" name="elemento{{ $i }}"
                                    id="btnNo-{{ $element->id }}" value="{{ $element->existente }}" autocomplete="off"
                                    @if (!$element->existente) checked @endif>
                                    <label class="uppercase" for="btnNo-{{ $element->id }}">No</label>
                                </div>
                            </div>
                            </td>
                            <td class="p-2">
                            <div class="w-full">
                                <textarea name="elemento{{ $i }}o" class="resize-none border w-full"
                                placeholder="Observación"
                                id="inputSighting-{{ $element->id }}">{{ $element->observacion }}</textarea>
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
      <div class="my-3">
        <label for="building-format" class="form-label fw-bold">Formato de Instalaciones</label>
        <input class="form-control" name="formatoInstalaciones" type="file" id="building-format" required>
      </div>
      <div class="d-flex justify-content-end">
        <input class="btn boton-green text-light" type="submit" value="Guardar">
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
      const id = checkBox.id.split('-')[1]
      const inputSighting = document.getElementById(`inputSighting-${id}`)
      if (checkBox.classList.contains('btn-No')) {
        checkBox.value = !this.checked
      } else {
        checkBox.value = this.checked
      }

      if (checkBox.value == 'true') {
        inputSighting.required = false
      } else {
        inputSighting.required = true
      }

      $(document).ready(function() {
        $('#elementsTable').DataTable({
          "scrollY": "50vh",
          "scrollCollapse": true,
        });
        $('.dataTables_length').addClass('bs-select');
      });
    }
  </script>
@endsection
