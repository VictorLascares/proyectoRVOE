@extends('layouts.app')
@section('contenido')
    <div class="mb-20 mt-10 md:grid md:grid-cols-2 gap-10 p-5 items-center">
        <h1 class="text-green-700 font-black text-6xl">
            Ingresa los datos de la
            <span class="text-gray-500">Institución</span>
        </h1>
        <div class="mt-20 md:mt-5 shadow-lg px-5 py-10 rounded-xl bg-white">
            <form class="mb-2" method="POST" action="{{ route('institutions.store') }}" enctype="multipart/form-data"
                novalidate>
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <input type="text" class="w-full rounded-lg border-gray-200 bg-gray-50 p-3" id="institutionName"
                            name="name" placeholder="Nombre de la Institución">
                        @error('name')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-2">
                        <input type="text" class="w-full rounded-lg border-gray-200 bg-gray-50 p-3" name="owner"
                            id="owner" placeholder="Titular">
                        @error('owner')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-2">
                        <input type="text" class="w-full rounded-lg border-gray-200 bg-gray-50 p-3" name="legalRep"
                            id="legalRep" placeholder="Representante legal o asociacion civil">
                        @error('legalRep')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <select name="municipalitie_id" class="w-full rounded-lg border-gray-200 bg-gray-50 p-3"
                            id="municipality" aria-label="Floating label select example">
                            <option selected disabled>Selecciona un municipio</option>
                            @foreach ($municipalities as $municipality)
                                <option value="{{ $municipality->id }}">{{ $municipality->name }}</option>
                            @endforeach
                        </select>
                        @error('municipalitie_id')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <input type="email" class="w-full rounded-lg border-gray-200 bg-gray-50 p-3" name="email"
                            id="email" placeholder="ejemplo@correo.com">
                        @error('email')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-2">
                        <textarea name="address" class="w-full rounded-lg border-gray-200 bg-gray-50 p-3 resize-none" id="address"
                            placeholder="Dirección"></textarea>

                        @error('address')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-2">
                        <label for="InstitutionLogo" class="mb-2 block text-gray-500 font-bold">Logo de la Institución</label>
                        <input type="file" class="rounded-lg bg-gray-50 block w-full" id="institutionLogo" name="logo">
                        @error('logo')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="flex justify-end">
                    <button class="bg-green-900 hover:bg-green-700 rounded-lg mt-4 px-10 py-2 text-white uppercase"
                        type="submit">Agregar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
