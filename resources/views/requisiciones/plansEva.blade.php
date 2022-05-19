@extends('layouts.layout')
@section('header')
  <x-bar />
  <x-navbar />
@endsection
@section('main-content')
  <div class="container-lg py-4">
    <form method="POST" action="{{url('/update/plans')}}">
      @csrf
      <input type="hidden" value="{{$requisition->id}}" name="requisition_id">
      @foreach($plans as $plan)
        <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
          <p class="m-0">{{$planNames[$loop->iteration-1]}}</p>
          <div class="form-floating">
            <input type="number" class="form-control" id="weighingInput" placeholder="Ponderacion" name="plan{{$loop->iteration}}">
            <label for="weighingInput">Ponderacion</label>
          </div>
          <div class="form-floating">
            <input type="text" class="form-control" id="commentaryInput" placeholder="Comentario" name="plan{{$loop->iteration}}c">
            <label for="commentaryInput">Comentario</label>
          </div>
        </div>
      @endforeach
      <button type="submit">Guardar</button>
    </form>
  </div>  
@endsection
@section('footer')
  <x-footer />
@endsection
