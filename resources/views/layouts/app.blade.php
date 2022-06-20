<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>ChRvoe - @yield('titulo')</title>
  <link rel="stylesheet" href="https://necolas.github.io/normalize.css/8.0.1/normalize.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,700;1,300&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="shortcut icon" href="#" type="image/x-icon">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js" defer></script>
  <script src="{{ asset('js/app.js') }}" defer></script>
</head>

<body>
  <header>
    <div class="bg-[#0C231E] px-3 py-3">
      <div class="container mx-auto flex justify-between items-center">
        <a href="{{ url('/') }}">
          <img src="{{ asset('img/Logo_gob_mx.svg') }}" alt="">
        </a>
        @guest
          <a href="{{ route('login') }}"  class="text-gray-400 hover:text-white" >Iniciar Sesion</button>
        @endguest
        @auth
          <div class="flex justify-between items-center">
            <a href="{{ url('logout') }}" class="text-gray-400 hover:text-white">
              Cerrar Sesión
            </a>
            <div title="{{ Auth::user()->tipoUsuario }}" style="height: 2rem; width: 2rem"
              class="bg-[#13322B] uppercase rounded-lg text-center">
              {{ substr(Auth::user()->nombres, 0, 1) }}{{ substr(Auth::user()->apellidos, 0, 1) }}
            </div>
          </div>
        @endauth
      </div>
    </div>
    <nav class="bg-[#13322B]">
      <div class="container mx-auto flex justify-center items-center" id="navbarSupportedContent">
        <ul class="flex justify-between gap-2 py-3">
          <li class="text-gray-400 hover:text-white text-center">
            <a class="uppercase" href="{{ url('/') }}">Inicio</a>
          </li>
          <li class="text-gray-400 hover:text-white text-center">
            <a class="uppercase" href="{{ url('consult') }}">Consultar rvoe</a>
          </li>
          @auth
            @if (Auth::user()->tipoUsuario == 'administrador')
              <li class="text-gray-400 hover:text-white text-center">
                <a class="uppercase" href="{{ url('users') }}">Usuarios</a>
              </li>
            @endif
            <li class="text-gray-400 hover:text-white text-center">
              <a class="uppercase" href="{{ url('requisitions') }}">Solicitudes</span></a>
            </li>
            <li class="text-gray-400 hover:text-white text-center">
              <a class="uppercase" href="{{ url('institutions') }}">Instituciones</span></a>
            </li>
          @endauth
        </ul>
      </div>
    </nav>
  </header>
  <main class="container mx-auto mt-10">
    <h1 class="font-black text-center text-3xl mb-10">
      @yield('titulo')
    </h1>
    @yield('contenido')
  </main>
  <footer class="mt-10 text-center p-5 text-gray-500 font-bold">
    Todos los Derechos Reservados &copy; {{ now()->year }}
  </footer>
  @yield('script')
</body>

</html>
