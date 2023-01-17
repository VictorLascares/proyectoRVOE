@extends('layouts.app')
@section('titulo')
  Evaluación de Planes
@endsection
@section('contenido')
  <div class="container-sm py-4">
    <form method="POST" class="px-5" action="{{ url('/update/plans') }}">
      @csrf
      <input type="hidden" value="{{ $requisition->id }}" name="requisition_id">
      <div class="md:grid md:grid-cols-2 md:gap-10">
        @foreach ($plans as $plan)
            <div class="@if (($loop->iteration - 1) == 2) col-span-2 @endif">
                <div class="lg:flex lg:justify-between lg:items-center mb-4">
                    <p class="text-2xl mb-4 lg:mb-0">{{ $planNames[$loop->iteration - 1] }}</p>
                    <input type="number" class="hide-arrows w-full sm:w-auto" id="weighingInput" value="{{ $plan->ponderacion }}" placeholder="Calificación" name="plan{{ $loop->iteration }}" required>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <textarea class="ckeditor resize-none" id="commentaryInput" name="plan{{ $loop->iteration }}c" required>{{ $plan->comentario }}</textarea>
                    </div>
                </div>
            </div>
        @endforeach
      </div>
      <div class="flex justify-end items-center mt-3">
        <button class="bg-green-900 hover:bg-green-700 py-2 px-10 text-white w-full sm:w-auto" type="submit">Guardar</button>
      </div>
    </form>
  </div>
@endsection
@section('script')
<script type="text/javascript">
    window.onload = function() {
        CKEDITOR.config.toolbar = [
            ['Styles','Format','Font','FontSize'],
            '/',
            ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Paste','Find','Replace','-','Outdent','Indent','-','Print'],
            '/',
            ['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']
        ] ;
        CKEDITOR.config.removePlugins = 'resize';
    }
</script>
@endsection