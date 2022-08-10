@extends('layouts.app')
@section('titulo')
    @if ($requisition->noEvaluacion == 1)
        Revisión de existencia de formatos
    @elseif ($requisition->noEvaluacion == 2)
        Revisión del contenido de los formatos 1
    @else
        Revisión del contenido de los formatos 2
    @endif
@endsection
@section('contenido')
  <form class="mb-2 flex flex-col justify-center items-center" method="POST" action="{{ url('/update/formats') }}">
    @csrf
    <input type="hidden" name="requisition_id" value="{{ $requisition->id }}">
    <div class="@if ($requisition->noEvaluacion != 1) grid grid-cols-2 @endif gap-4">
    @foreach (range(1, 5) as $i)
      @foreach ($formats as $format)
        @if ($format->formato == $i)
        <div class="@if ($i == 5) col-span-2 @endif">
            <div class="mb-4  flex justify-start items-center gap-4">
              <input name="anexo{{ $i }}" value="{{ $format->valido }}"
                class="review2Checkbox form-check-input" type="checkbox" id="check-review2-{{ $format->id }}"
                @if ($format->valido) checked @endif>
              <label class="form-check-label" for="check-review2-{{ $format->id }}">
                {{ $formatNames[$i - 1] }}
              </label>
            </div>
            @if ($requisition->noEvaluacion != 1)
                <div>
                <textarea name="anexo{{ $i }}j" class="w-full resize-none" id="just-review2-{{ $format->id }}"
                    placeholder="Justificación">{{ $format->justificacion }}</textarea>
                </div>
            @endif
        </div>
            @endif
            @endforeach
            @endforeach
        </div>
    <div class="flex justify-center items-center mt-4">
      <button class="text-white bg-[#13322B] hover:bg-[#0C231E] px-10 py-3" type="submit">Guardar</button>
    </div>
  </form>
@endsection
@section('script')
    <script>
        const checkboxes = document.querySelectorAll('.review2Checkbox')

        window.onload = function() {
            $('input[type=checkbox]').prop('checked',false);
        }

        checkboxes.forEach(element => {
            element.addEventListener('change', e => {
                e.target.value = e.target.checked
            })
        });

    </script>
@endsection
