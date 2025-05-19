<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Acceso de Usuarios</title>
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />

    <!-- Fonts and icons -->
    <script src="{{ asset('js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["{{ asset('css/fonts.min.css') }}"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

  <style>
    body {
      position: relative;
      font-family: 'Segoe UI', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    /* Imagen de fondo */
    .background-image {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('{{ asset('img/fondo.jpg') }}') no-repeat center center fixed;
      background-size: cover;
      opacity: 1; /* Marca de agua */
      z-index: 1;
    }

    /* Capa semitransparente opcional */
    .overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 255, 255, 0.1);
      z-index: 2;
    }

    .login-card {
      position: relative;
      z-index: 3;
      background: rgba(255, 255, 255, 0.85);
      padding: 2.5rem;
      border-radius: 1rem;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 400px;
      backdrop-filter: blur(5px);
    }
    
    .form-control:focus {
      box-shadow: 0 0 0 0.2rem rgba(116, 235, 213, 0.5);
      border-color: #74ebd5;
    }

.btn-custom {
  background-color: #495057; /* Gris oscuro con un toque azul */
  color: #fff;
  border: none;
}

.btn-custom:hover {
  background-color: #343a40; /* Un tono m�s oscuro al hacer hover */
}
  </style>
</head>
<body>

  <!-- Imagen tipo marca de agua -->
  <div class="background-image"></div>
  <div class="overlay"></div>
  
  <div class="login-card">
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif
@if(session('message'))
    <div class="alert alert-warning">
        {{ session('message') }}
    </div>
@endif   
    <h3 class="text-center mb-4">Iniciar Sesi&oacute;n</h3>
    <form method="POST" action="{{ route('usuario.login') }}" autocomplete="off">
    @csrf
    
      <div class="mb-3">
        <label for="usuario" class="form-label">Usuario</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-user"></i></span>
          <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Usuario" required>
        </div>      
      </div>
      <div class="mb-4">
        <label for="password" class="form-label">Contrase&ntilde;a</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" class="form-control" name="password" id="password" placeholder="********" required>
        </div>      </div>
      <div class="d-grid mb-3">
        <button type="submit" class="btn btn-custom btn-lg">Ingresar</button>
      </div>
    </form>
  </div>

</body>
</html>
