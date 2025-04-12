<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Aplicacion de Index</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>
<body>
    

    <main class="conteiner">
        @yield('content')
    </main>
</body>
<style>
    .container-fluid{
        margin-left: 250px; /* igual al ancho del sidebar */
        padding: 20px;
        width: calc(100% - 250px);
        box-sizing: border-box;
    }
</style>
</html>
