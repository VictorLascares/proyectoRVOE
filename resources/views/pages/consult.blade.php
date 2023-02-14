@extends('layouts.app')
@section('titulo')
    Ingresa los datos para Consultar
    <span class="text-gray-500">el estado del RVOE</span>
@endsection
@section('contenido')
    <div class="container mx-auto mt-10">
        <div class="md:grid md:grid-cols-2 gap-4 bg-white rounded-xl shadow-lg px-5 py-10">
            <div class="my-5">
                <label for="municipalitySelect" class="block uppercase text-gray-500 font-bold">Municipio*</label>
                <select id="municipalitySelect" class="mt-3 p-3 w-full bg-gray-50 rounded-xl border-gray-200"
                    name="municipio">
                    <option selected disabled>-- Seleccione un Municipio --</option>
                    @foreach ($municipalities as $municipality)
                        <option value="{{ $municipality->id }}">{{ $municipality->name }}</option>
                    @endforeach
                </select>
                @error('municipio')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div class="my-5">
                <label for="institutions" class="block uppercase text-gray-500 font-bold">Institución</label>
                <select id="institutions" class="mt-3 p-3 w-full bg-gray-50 rounded-xl border-gray-200" name="institucion">
                    <option selected disabled>-- Seleccione una Institución --</option>
                </select>
            </div>
            <div class="my-5">
                <label for="careers" class="block uppercase text-gray-500 font-bold">Carrera</label>
                <select id="careers" class="mt-3 w-full p-3 bg-gray-50 rounded-xl border-gray-200" name="career_id">
                    <option selected disabled>-- Seleccione la Carrera --</option>
                </select>
            </div>
            <div class="my-5">
                <label for="rvoe" class="block uppercase text-gray-500 font-bold">RVOE o Acuerdo</label>
                <input type="text" class="mt-3 p-3 w-full bg-gray-50 rounded-xl border-gray-200" id="rvoe" name="rvoe" placeholder="RVOE o acuerdo">
            </div>
            <div class="flex justify-end gap-4 my-5 col-span-2">
                <button id="search-rvoe" type="button"
                    class="bg-green-900 hover:bg-green-700 uppercase py-3 px-10 text-white rounded-xl">
                    Buscar RVOE
                </button>
                <button id="resetButton" type="button"
                    class="bg-green-900 hover:bg-green-700 uppercase py-3 px-10 text-white rounded-xl">
                    Limpiar
                </button>
            </div>
        </div>

        <p class="mt-10 text-xl text-gray-400 border-b-4 py-3">* Campos obligatorios</p>
        @if ($requisition)
            <p>{{ $requisition->status }}</p>
        @endif

        <section class="mt-10" id="resultados">
            <h2 class="text-2xl py-4 uppercase text-center">Resultado de la Consulta</h2>
            <div class="flex flex-col">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="overflow-hidden">
                            <table class="min-w-full">
                                <thead class="border-b bg-green-900">
                                    <tr>
                                        <th class="text-sm font-bold text-white px-6 py-4 text-left">Estado</th>
                                        <th class="text-sm font-bold text-white px-6 py-4 text-left">Fecha de Vencimiento
                                        </th>
                                        <th class="text-sm font-bold text-white px-6 py-4 text-left">Carrera</th>
                                    </tr>
                                </thead>
                                <tbody id="consultas">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
    <script>
        const resetButton = document.querySelector('#resetButton');
        const form = document.querySelector('#consultForm');
        const searchRvoe = document.querySelector('#search-rvoe')

        resetButton.addEventListener('click', () => {
            const carrers = document.querySelector('#careers');
            const institutions = document.querySelector('#institutions');
            while (carrers.firstChild) {
                carrers.removeChild(carrers.firstChild)
            }
            carrers.innerHTML = '<option selected disabled>-- Seleccione una carrera --</option>'
            while (institutions.firstChild) {
                institutions.removeChild(institutions.firstChild)
            }
            institutions.innerHTML = '<option selected disabled>-- Seleccione una Institución --</option>'
            form.reset();
            $('#institutions').empty()
            document.querySelector("#resultados").style.display = "none";
        })
        $(document).ready(function() {
            document.querySelector("#resultados").style.display = "none";
            $('#municipalitySelect').on('change', function() {
                document.querySelector("#resultados").style.display = "block";
                let municipalityId = $(this).val()
                $.get('getinstitutions', {
                    municipalityId: municipalityId
                }, function(institutions) {
                    $('#institutions').empty()
                    $('#institutions').append(
                        '<option selected disabled>-- Seleccione una Institución --</option>')
                    $.each(institutions, function(index, value) {
                        $('#institutions').append(
                            `<option value='${index}'>${value}</option>`)
                    });
                })

                // Generar resultados por municipio
                $.get('consultByMunicipality', {
                    municipalityId: municipalityId
                }, function(data) {
                    $('#consultas').empty()
                    $.each(data.requisitions, function($i, requisition) {
                        const {
                            status,
                            career_id,
                            dueDate
                        } = requisition
                        let carrera
                        $.each(data.careers, function($index, career) {
                            if (career_id == career.id) {
                                carrera = career.name
                            }
                        })
                        $('#consultas').append(`
                            <tr class="border-b">
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">${status}</td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">${dueDate == null ? 'No disponible' : dueDate }</td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">${carrera}</td>
                            </tr>
                        `)
                    });
                })
            })

            $('#institutions').on('change', function() {
                let institutionId = $(this).val()
                $.get('careers', {
                    institutionId: institutionId
                }, function(careers) {
                    $('#careers').empty()
                    $('#careers').append(
                        '<option selected disabled>-- Seleccione una carrera --</option>')
                    $.each(careers, function(index, value) {
                        $('#careers').append(`<option value='${index}'>${value}</option>`)
                    });
                })

                // Generar resultados por institucion
                $.get('consultByInstitution', {
                    institutionId: institutionId
                }, function(data) {
                    $('#consultas').empty()
                    $.each(data.requisitions, function($i, requisition) {
                        const {
                            status,
                            career_id,
                            dueDate
                        } = requisition
                        let carrera
                        $.each(data.careers, function($index, career) {
                            if (career_id == career.id) {
                                carrera = career.name
                            }
                        })
                        $('#consultas').append(`
                            <tr class="border-b">
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">${status}</td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">${dueDate == null ? 'No disponible' : dueDate }</td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">${carrera}</td>
                            </tr>
                        `)
                    });
                })
            })

            $('#careers').on('change', function() {
                // Generar resultados por carrera
                let careerId = $(this).val()
                $.get('consultByCareer', {
                    careerId: careerId
                }, function(data) {
                    console.log(data);
                    if (data.requisition.length > 0) {
                        $('#consultas').empty()
                        const {
                            status,
                            dueDate
                        } = data.requisition[0]
                        $('#consultas').append(`
                            <tr class="border-b">
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">${status}</td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">${dueDate == null ? 'No disponible' : dueDate }</td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">${data.career[0].name}</td>
                            </tr>
                        `)
                    } else {
                        $('#consultas').empty()
                        $('#consultas').append(`
                            <tr class="border-b">
                                No se encontraron resultados
                            </tr>
                        `)
                    }
                })
            })

            $('#search-rvoe').on('click', function() {
                const rvoeInput = document.querySelector('#rvoe')
                if (rvoeInput.value !== '') {
                    document.querySelector("#resultados").style.display = "block";
                    // Generar resultados por rvoe
                    let rvoe = rvoeInput.value
                    $.get('consultByRvoe', {
                        rvoe: rvoe
                    }, function(data) {
                        $('#consultas').empty()
                        const {
                            status,
                            dueDate
                        } = data.requisition[0]
                        $('#consultas').append(`
                            <tr class="border-b">
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">${status}</td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">${dueDate == null ? 'No disponible' : dueDate }</td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">${data.career[0].nombre}</td>
                            </tr>
                        `)
                    })
                }
            })
        })
    </script>
@endsection
