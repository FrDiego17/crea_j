<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="{{ url('Assets/CSS/admin-index.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

</head>
<body>
    <nav class="navbar bg-primary " data-bs-theme="dark">
      <div class="container-fluid p-0 mt-0">
        <img src="{{ url('logo.png')}}" id="logo" alt="" width="80" height="80">
        <a class="navbar-brand fs-2 fw-bolder" id="titulo" href="#">U Go!</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav">
            <a class="nav-link active" aria-current="page" href="#">Inicio</a>
            <a class="nav-link" href="#">Usuarios</a>
            <a class="nav-link" href="#">Microbuses</a>
          </div>
        </div>
      </div>
    </nav>

    <main class="container text-center">
      <h1 class="my-4">Welcome!</h1>
      <div class="row row-cols-2 g-4">

        <a href="{{route('rutas.index')}}">
          <div class="col">
            <div class="card shadow-sm animate-card">
                <div class="card-body">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><g fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="#1666a6" d="M4.5 4.5H12A1.5 1.5 0 0 1 13.5 6v.5m-7.5 7H2A1.5 1.5 0 0 1 .5 12V3.5a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1v1" stroke-width="1"/><path stroke="#080808" d="M11.5 11.5v4m0-7.5v1.5m-1.5 6h3m-4.5-6v2h5.75l1.25-1l-1.25-1z" stroke-width="1"/></g></svg>
                    <h5 class="card-title">Administar Rutas</h5>
                </div>
            </div>
          </div>
        </a>

        <a href="/conductores">
          <div class="col">
            <div class="card shadow-sm animate-card">
                <div class="card-body">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#1666a6" d="M2 3h20c1.05 0 2 .95 2 2v14c0 1.05-.95 2-2 2H2c-1.05 0-2-.95-2-2V5c0-1.05.95-2 2-2m12 3v1h8V6zm0 2v1h8V8zm0 2v1h7v-1zm-6 3.91C6 13.91 2 15 2 17v1h12v-1c0-2-4-3.09-6-3.09M8 6a3 3 0 0 0-3 3a3 3 0 0 0 3 3a3 3 0 0 0 3-3a3 3 0 0 0-3-3"/></svg>
                    <h5 class="card-title">Administrar Conductores</h5>
                </div>
            </div>
          </div>
        </a>

        <a href="{{route('users.index')}}">
          <div class="col">
            <div class="card shadow-sm animate-card">
                <div class="card-body">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#1666a6" fill-rule="evenodd" d="M8 4a4 4 0 1 0 0 8a4 4 0 0 0 0-8m-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4zm7.25-2.095c.478-.86.75-1.85.75-2.905a6 6 0 0 0-.75-2.906a4 4 0 1 1 0 5.811M15.466 20c.34-.588.535-1.271.535-2v-1a5.98 5.98 0 0 0-1.528-4H18a4 4 0 0 1 4 4v1a2 2 0 0 1-2 2z" clip-rule="evenodd"/></svg>
                  <h5 class="card-title">Administar Usuarios</h5>
                </div>
            </div>
        </div>
        </a>

      </div>
  </main>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

</html>
