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
      <div class="nav-item">
        <a href="{{ url('logout') }}" class="nav-link text-decoration-none" style="background-color:transparent">Cerrar
          Sesión</a>
      </div>
    @endauth
  </div>
</div>
