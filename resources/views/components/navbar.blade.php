<nav class="sticky-top nav navbar navbar-expand-md navbar-dark">
  <div class="container-sm justify-content-end">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-center text-uppercase" href="{{ url('/') }}">Inicio</a>
        </li>
        <li class="nav-item text-center">
          <a class="nav-link text-uppercase" href="{{ url('consult') }}">Consultar rvoe</a>
        </li>
        @auth
          @if (Auth::user()->tipoUsuario == 'administrador')
            <li class="nav-item text-center">
              <a class="nav-link text-uppercase" href="{{ url('users') }}">Usuarios</a>
            </li>
          @endif
          <li class="nav-item text-center">
            <a class="nav-link text-uppercase" href="{{ url('requisitions') }}">Solicitudes</span></a>
          </li>
          <li class="nav-item text-center">
            <a class="nav-link text-uppercase" href="{{ url('institutions') }}">Instituciones</span></a>
          </li>
        @endauth
      </ul>
    </div>
  </div>
</nav>
