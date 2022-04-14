<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title text-uppercase w-100" id="exampleModalLabel">Iniciar Sesión</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="mb-2" method="POST" action="{{ url('/validar') }}">
          @csrf
          <div class="form-floating mb-3">
            <input type="email" class="form-control" id="floatingInput" name="correo" placeholder="name@example.com">
            <label for="floatingInput">Correo Electrónico</label>
          </div>
          <div class="form-floating">
            <input type="password" class="form-control" name="contrasenia" id="floatingPassword"
              placeholder="Contraseña">
            <label for="floatingPassword">Contraseña</label>
          </div>
          <div class="d-grid mt-4">
            <button class="btn btn-success text-uppercase" type="submit">Iniciar Sesión</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
