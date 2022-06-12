@extends('layouts.app')
@section('titulo')
  Consultar RVOE
@endsection
@section('contenido')
  <div class="container mx-auto">
    <form class="grid md:grid-cols-2 gap-4 px-4">
      <select id="institutionsSelect" class="border p-3 w-full">
        <option selected disabled>-- Seleccione una Institución --</option>
        @foreach ($institutions as $institution)
          <option value="{{ $institution->id }}">{{ $institution->nombre }}</option>
        @endforeach
      </select>
      <select id="inputState" class="border p-3 w-full">
        <option selected disabled>-- Seleccione el área de estudios --</option>
        @foreach ($areas as $area)
          <option value="{{ $area->id }}">{{ $area->nombre }}</option>
        @endforeach
      </select>
      <select id="municipalitySelect" class="border p-3 w-full">
        <option selected disabled>-- Seleccione un Municipio --</option>
        @foreach ($municipalities as $municipality)
          <option value="{{ $municipality->id }}">{{ $municipality->nombre }}</option>
        @endforeach
      </select>
      <select id="inputState" class="border p-3 w-full">
        <option selected disabled>-- Seleccione la modalidad --</option>
        <option value="Presencial">Presencial</option>
        <option value="Distancia">Distancia</option>
        <option value="Hibrida">Hibrida</option>
      </select>
      <select id="inputState" class="border p-3 w-full">
        <option selected disabled>-- Seleccione el status --</option>
        <option>Activo</option>
        <option>Inactivo</option>
        <option>Latencia</option>
        <option>Revocado</option>
        <option>Pendiente</option>
        <option>Revocado</option>
      </select>
      <input type="email" class="border p-3 w-full" id="floatingInput" placeholder="RVOE o acuerdo">
      <button type="reset" class="bg-[#13322B] hover:bg-[#0C231E] uppercase p-3 text-white w-full">
        Limpiar
      </button>
      <button type="submit" class="bg-[#13322B] hover:bg-[#0C231E] uppercase p-3 text-white w-full">
        Buscar
      </button>
    </form>
  </div>
@endsection
