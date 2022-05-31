@extends('layouts.layout')
@section('header')
  <x-bar />
  <x-navbar />
@endsection
@section('main-content')
  <div class="container-sm py-4">
    <h2 class="text-center mb-4">Evaluación de Planes</h2>
    <form method="POST" class="px-5" action="{{ url('/update/plans') }}">
      @csrf
      <input type="hidden" value="{{ $requisition->id }}" name="requisition_id">
      @foreach ($plans as $plan)
        <p class="mb-1 mt-2 h6">{{ $planNames[$loop->iteration - 1] }}</p>
        <div class="row g-2">
          <div class="col-md-6">
            <div class="form-floating">
              <input type="number" class="form-control" id="weighingInput" value={{ $plan->ponderacion }}
                placeholder="Ponderacion" name="plan{{ $loop->iteration }}" required>
              <label for="weighingInput">Ponderacion</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating">
              <textarea type="text" class="form-control resize-none" id="commentaryInput" placeholder="Comentario"
                name="plan{{ $loop->iteration }}c" required>{{ $plan->comentario }}</textarea>
              <label for="commentaryInput">Comentario</label>
            </div>
          </div>
        </div>
      @endforeach
      <div class="d-flex justify-content-end align-items-center mt-3">
        <button class="btn boton-green text-light" type="submit">Guardar</button>
      </div>
    </form>
  </div>
@endsection
@section('footer')
  <x-footer />
@endsection
