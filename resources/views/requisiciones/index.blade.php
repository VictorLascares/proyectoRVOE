@extends('layouts.app')
@section('titulo')
  Solicitudes
@endsection
@section('contenido')
  <div class="container py-4">
    <form class="mb-4 flex gap-4">
        <button 
            type="reset" 
            id="limpiar-filtro" 
            class="text-white p-2 bg-[#13322B] hover:bg-[#0C231E]"
        >
            Limpiar Filtros
        </button>
        <select id="filtro-anio">
            <option selected disabled>Filtrar por Año</option>
        </select>
        <select id="filtro-estado">
            <option selected disabled>Filtrar por estado</option>
            <option value="activo">Activo</option>
            <option value="latencia">Latencia</option>
            <option value="revocado">Revocado</option>
            <option value="inactivo">Inactivo</option>
            <option value="pendiente">Pendiente</option>
            <option value="rechazado">Rechazado</option>
        </select>
    </form>

    <div class="requisiciones">
      @foreach ($requisitions as $requisition)
          <a 
            data-fecha="{{ $requisition->created_at->format('m-d-y') }}"
            data-estado="{{ $requisition->estado }}"
            href="{{ route('requisitions.show', $requisition->id) }}"
            class="w-100 rounded requisicion requisition text-center text-decoration-none py-3 @switch($requisition->estado) @case('pendiente')
            @case('latencia')
              bg-light-yellow
              text-dark-yellow
              @break
            @case('activo')
              bg-light-green
              text-dark-green
              @break
            @case('revocado')
            @case('inactivo')
            @case('rechazado')
              bg-light-red 
              text-dark-red
            @endswitch"
            style="width: 13rem">
            <p class="text-uppercase fw-bold m-0">{{ $requisition->estado }}</p>
            <p class="m-0">{{ $requisition->created_at->format('m-d-Y') }}</p>
          </a>
      @endforeach
    </div>
  </div>
  @if (Auth::user()->tipoUsuario == 'planeacion')
    <button 
    data-modal-toggle="authentication-modal" 
      type="button"
      class="p-2 fixed bottom-5 right-5 boton bg-[#13322B] hover:bg-[#0C231E]">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
      </svg>
    </button>
  @endif

  
 
  <div id="authentication-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full max-w-md h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>  
            </button>
            <div class="py-6 px-6 lg:px-8">
                <h3 class="text-center mb-4 text-xl font-medium text-gray-900 dark:text-white">Nueva Solicitud</h3>
                <form class="mb-2" method="POST" action="{{ route('requisitions.store') }}">
                    @csrf
                    <div class="mb-5">
                      <select id="requisitionGoal" class="w-full border p-3" name="meta" required>
                        <option selected disabled>-- Seleccione la Meta --</option>
                        <option value="solicitud">Solicitud</option>
                        <option value="domicilio">Domicilio</option>
                        <option value="planEstudios">Plan de Estudios</option>
                      </select>
                    </div>
                    <div class="mb-5">
                      <select id="institutions" class="w-full border p-3">
                        <option selected disabled>-- Seleccione la Institución --</option>
                        @foreach ($institutions as $institution)
                          <option value="{{ $institution->id }}">{{ $institution->nombre }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="mb-5">
                      <select id="careers" class="w-full border p-3" name="career_id">
                        <option selected disabled>-- Seleccione la Carrera --</option>
                      </select>
                    </div>
                    <button class="bg-[#13322B] hover:bg-[#0C231E] text-white uppercase w-full p-3" type="submit">Agregar</button>
                  </form>
            </div>
        </div>
    </div>
</div> 
@endsection
@section('script')
  <script>
    const filtroEstado = document.querySelector('#filtro-estado')
    const requisiciones = document.querySelectorAll('.requisicion')
    const btnLimpiarFiltros = document.querySelector('#limpiar-filtro')
    const filtroAnio = document.querySelector('#filtro-anio')

    cargarEventListeners()


    function cargarEventListeners() {
      document.addEventListener("DOMContentLoaded", llenarSelectAnios)
      filtroEstado.addEventListener('change', filtrarPorEstado)
      btnLimpiarFiltros.addEventListener('click', limpiarFiltros)
      filtroAnio.addEventListener('change', filtrarPorAnio)
    }



    function filtrarPorEstado(e) {
      const option = e.target.value
      requisiciones.forEach(req => {
        if (req.getAttribute('data-estado') != option) {
          req.classList.add('hide')
        }
      })
    }

    function filtrarPorAnio(e) {
      const anio = e.target.value
      requisiciones.forEach(req => {
        if (req.getAttribute('data-fecha').split('-')[2] != anio) {
          req.classList.add('hide')
        }
      })
    }


    function llenarSelectAnios() {
      const anios = []
      requisiciones.forEach(req => {
        const anio = req.getAttribute('data-fecha').split('-')[2]
        if (!anios.includes(anio)) {
          anios.push(anio)
        }
      });
      for (let i = 0; i < anios.length; i++) {
        const option = document.createElement('option')
        option.value = anios[i]
        option.textContent = anios[i]
        filtroAnio.append(option)
      }
    }


    function limpiarFiltros() {
      requisiciones.forEach(req => {
        req.classList.remove('hide')
      })
    }


    $(document).ready(function() {
      $('#institutions').on('change', function() {
        let institutionId = $(this).val()
        $.get('careers', {
          institutionId: institutionId
        }, function(careers) {
          $('#careers').empty()
          $.each(careers, function(index, value) {
            $('#careers').append(`<option value='${index}'>${value}</option>`)
          });
        })
      })
    })
  </script>
@endsection
