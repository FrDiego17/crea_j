

@section('content')

    @auth
    @extends('layouts.app-master')

    <div class="container mx-auto max-w-4xl py-8 px-6 bg-white shadow rounded-lg" >

        <h2 class="text-2xl font-bold mb-6">Perfil del Usuario</h2>

        <div class="mb-6">
            <p><strong>Nombre:</strong> {{ Auth::user()->username }}</p>
            <p><strong>Correo:</strong> {{ Auth::user()->email }}</p>
            <p><strong>Fecha de Registro:</strong> {{ Auth::user()->created_at->format('d/m/Y') }}</p>
        </div>

        <div class="mb-6">
            <h3 class="text-xl font-semibold mb-3">Tarjeta Virtual</h3>
            <div id="card-wrapper" class="mb-4"></div>

            <input type="text" name="name" id="card-name" value="{{ Auth::user()->name }}" style="visibility:hidden; position:absolute;">
            <input type="text" name="number" id="card-number" value="{{ Auth::user()->card_number }}" style="visibility:hidden; position:absolute;">
            <input type="text" name="expiry" id="card-expiry" value="{{ Auth::user()->card_expiry }}" style="visibility:hidden; position:absolute;">
            <input type="text" name="cvc" id="card-cvc" value="{{ Auth::user()->card_cvc }}" style="visibility:hidden; position:absolute;">

            <p><strong>Saldo:</strong> $<span id="saldo">{{ number_format(Auth::user()->card_balance, 2) }}</span></p>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Recargar Tarjeta</h3>
            <form id="form-recargar" action="{{ route('home.recargar') }}" method="POST">
                @csrf
                <input type="number" name="monto" placeholder="Monto a recargar" class="form-input w-full p-2 border rounded mb-2" required>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Recargar</button>
            </form>
        </div>

        <div>
            <h3 class="text-lg font-semibold mb-2">Compartir Saldo</h3>
            <form id="form-compartir" action="{{ route('home.compartir') }}" method="POST">
                @csrf
                <input type="email" name="email" placeholder="Correo del destinatario" class="form-input w-full p-2 border rounded mb-2" required>
                <input type="number" name="monto" placeholder="Monto a enviar" class="form-input w-full p-2 border rounded mb-2" required>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Enviar</button>
            </form>
        </div>
    </div>

    <style>
        body{
            background-color: white;
            color: black;
        }

        h2{
            padding-top: 100px;
        }
    </style>

    @if(session('toast_success') || session('toast_error') || session('nuevo_saldo'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @if(session('toast_success'))
                mostrarToast("{{ session('toast_success') }}");
            @endif
            @if(session('toast_error'))
                mostrarToast("{{ session('toast_error') }}", "error");
            @endif
            @if(session('nuevo_saldo'))
                const nuevoSaldo = parseFloat("{{ session('nuevo_saldo') }}").toFixed(2);
                document.getElementById('saldo').textContent = nuevoSaldo;
            @endif
        });
    </script>
@endif

    @endauth
@endsection
