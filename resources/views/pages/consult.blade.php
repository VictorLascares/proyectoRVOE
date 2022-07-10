@extends('layouts.app')
@section('titulo')
  Consultar RVOE
@endsection
@section('contenido')
  <div class="container mx-auto">
    <form action="" method="POST" id="consultForm">
        @csrf
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label for="municipalitySelect" class="mb-2 block uppercase text-gray-500 font-bold">Municipio*</label>
                <select id="municipalitySelect" class="border p-3 w-full @error('municipio') border-red-600 @enderror" name="municipio">
                    <option selected disabled>-- Seleccione un Municipio --</option>
                    @foreach ($municipalities as $municipality)
                    <option value="{{ $municipality->id }}">{{ $municipality->nombre }}</option>
                    @endforeach
                </select>
                @error('municipio')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="institutions" class="mb-2 block uppercase text-gray-500 font-bold">Institución</label>
                <select id="institutions" class="border p-3 w-full" name="institucion">
                    <option selected disabled>-- Seleccione una Institución --</option>
                </select>
            </div>
            <div>
                <label for="careers" class="mb-2 block uppercase text-gray-500 font-bold">Carrera</label>
                <select id="careers" class="w-full border p-3" name="career_id">
                    <option selected disabled>-- Seleccione la Carrera --</option>
                  </select>
            </div>
            <div>
                <label for="rvoe" class="mb-2 block uppercase text-gray-500 font-bold">RVOE o Acuerdo</label>
                <input type="text" class="border p-3 w-full" id="rvoe" name="rvoe" placeholder="RVOE o acuerdo">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4 mt-4">
            <button id="resetButton" type="button" class="bg-[#13322B] hover:bg-[#0C231E] uppercase p-3 text-white w-full">
                Limpiar
            </button>
            <button type="submit" class="bg-[#13322B] hover:bg-[#0C231E] uppercase p-3 text-white w-full">
                Buscar
            </button>
        </div>
    </form>
    <p class="mt-10 text-xl text-gray-400 border-b-4 py-3">* Campos obligatorios</p>
    @if ($requisition)
        <p>{{$requisition->estado}}</p>
    @endif
  </div>
@endsection
@section('script')
    <script>
        const resetButton = document.querySelector('#resetButton');
        const form = document.querySelector('#consultForm');


        resetButton.addEventListener('click', () => {
            const carrers = document.querySelector('#careers');
            const institutions =  document.querySelector('#institutions');
            while (carrers.firstChild) {
                carrers.removeChild(carrers.firstChild)
            }
            carrers.innerHTML = '<option selected disabled>-- Seleccione una carrera --</option>'
            while (institutions.firstChild) {
                institutions.removeChild(institutions.firstChild)
            }
            institutions.innerHTML = '<option selected disabled>-- Seleccione una Institución --</option>'
            form.reset();
        })
        $(document).ready(function() {
            $('#municipalitySelect').on('change', function() {
                let municipalityId = $(this).val()
                $.get('institutions', {
                    municipalityId: municipalityId
                }, function(institutions) {
                $('#institutions').empty()
                    $('#institutions').append('<option selected disabled>-- Seleccione una Institución --</option>')
                    $.each(institutions, function(index, value) {
                        $('#institutions').append(`<option value='${index}'>${value}</option>`)
                    });
                })
            })

            $('#institutions').on('change', function() {
                let institutionId = $(this).val()
                $.get('careers', {
                    institutionId: institutionId
                }, function(careers) {
                    $('#careers').empty()
                    $('#careers').append('<option selected disabled>-- Seleccione una carrera --</option>')
                    $.each(careers, function(index, value) {
                        $('#careers').append(`<option value='${index}'>${value}</option>`)
                    });
                })
            })
        })
    </script>
@endsection
