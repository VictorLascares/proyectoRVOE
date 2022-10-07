@extends('layouts.app')
@section('titulo')
   Nueva Institución 
@endsection
@section('contenido')
    <div class="mb-20 mt-10">
        <form class="mb-2" method="POST" action="{{ route('institutions.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
            <input type="text" class="w-full rounded-lg border p-3" id="institutionName" name="nombre"
                placeholder="Nombre de la Institución">
            @error('nombre')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
            </div>
            <div class="col-span-2">
            <input type="text" class="w-full rounded-lg border p-3" name="titular" id="titular"
                placeholder="Titular">
            @error('titular')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
            </div>
            <div class="col-span-2">
            <input type="text" class="w-full rounded-lg border p-3" name="repLegal" id="repLegal"
                placeholder="Representante legal o asociacion civil">
            @error('repLegal')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
            </div>
            <div>
            <select name="municipalitie_id" class="w-full rounded-lg border p-3" id="municipality"
                aria-label="Floating label select example">
                <option selected disabled>Selecciona un municipio</option>
                @foreach ($municipalities as $municipality)
                <option value="{{ $municipality->id }}">{{ $municipality->nombre }}</option>
                @endforeach
            </select>
            @error('municipalitie_id')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
            </div>
            <div>
            <input type="email" class="w-full rounded-lg border p-3" name="email" id="email"
                placeholder="ejemplo@correo.com">
            @error('email')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
            </div>
            <div class="col-span-2">
            <textarea name="direccion" class="w-full rounded-lg border p-3 resize-none" id="address" placeholder="Dirección"></textarea>
            @error('direccion')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
            </div>
            <div class="col-span-2">
            <div>
                <label for="institutionLogo" class="mb-2 block text-gray-500 font-bold">Logo de la
                Institución</label> 
                <input type="file" class="rounded-lg block h-full" id="institutionLogo" name="logotipo">
                @error('logotipo')
                <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            </div>
        </div>
        <div class="flex justify-end">
            <button class="bg-[#13322B] hover:bg-[#0C231E] rounded-lg mt-4 px-10 py-2 text-white uppercase"
            type="submit">Agregar</button>   
        </div>
        </form>
    </div>
@endsection