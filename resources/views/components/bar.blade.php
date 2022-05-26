<div class="bar navbar-dark container-fluid px-3 py-3">
  <div class="navbar-nav justify-content-between flex-row align-items-center">
    <a href="{{ url('/') }}">
      <img src="{{ asset('img/Logo_gob_mx.svg') }}" alt="">
    </a>
    @guest
      <div class="nav-item">
        <button type="button" data-bs-target="#loginModal" class="nav-link border-0" data-bs-toggle="modal"
          style="background-color:transparent">Iniciar Sesion</button>
      </div>
    @endguest
    @auth
      <div class="d-flex justify-content-between align-items-center gap-2">
        <a href="{{ url('logout') }}" class="nav-link">
          Cerrar Sesión
        </a>
        <div title="{{Auth::user()->tipoUsuario}}" style="height: 2rem; width: 2rem" class="bg-light-green text-uppercase rounded-circle text-center py-1">
          {{substr(Auth::user()->nombres,0,1)}}{{substr(Auth::user()->apellidos,0,1)}}
        </div>
      </div>
    @endauth
  </div>
</div>
