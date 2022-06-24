@extends('layouts.app')
@section('titulo')
  Revisión de Formatos
@endsection
@section('contenido')
  <form class="mb-2" method="POST" action="{{ url('/update/formats') }}">
    @csrf
    <input type="hidden" name="requisition_id" value="{{ $requisition->id }}">
    <div class="grid grid-cols-2 gap-4">
    @foreach (range(1, 5) as $i)
      @foreach ($formats as $format)
        @if ($format->formato == $i)
        <div>
            <div class="mb-2 flex justify-start items-center gap-4">
              <input name="anexo{{ $i }}" value="{{ $format->valido }}"
                class="review2Checkbox form-check-input" type="checkbox" id="check-review2-{{ $format->id }}"
                @if ($format->valido) checked @endif>
              <label class="form-check-label" for="check-review2-{{ $format->id }}">
                {{ $formatNames[$i - 1] }}
              </label>
            </div>
            <div class="form-floating">
              <textarea name="anexo{{ $i }}j" class="w-full resize-none form-control" id="just-review2-{{ $format->id }}"
                placeholder="Justificación">{{ $format->justificacion }}</textarea>
            </div>
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
