<nav class="nav navbar navbar-expand-md navbar-dark">
    <div class="container-fluid justify-content-center">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link text-center text-uppercase" href="{{ url('/')}}">Inicio</a>
          </li>
          <li class="nav-item text-center">
            <a class="nav-link text-uppercase" href="{{ url('consult')}}">Consultar rvoe</a>
          </li>
          @auth
            @if (Auth::user()->tipoUsuario == 'administrador')
              <li class="nav-item text-center">
                <a class="nav-link text-uppercase" href="{{ url('users')}}">Usuarios</a>
              </li>
            @else
              <li class="nav-item text-center">
                <a class="nav-link text-uppercase" href="{{ url('dashboard')}}">Solicitudes</span></a>
              </li>
            @endif
          @endauth
        </ul>
    </div>
  </nav>