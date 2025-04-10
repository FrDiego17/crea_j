<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
        });
    @endif

    @if($errors->any())
        let errorMessages = `
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        `;
        Swal.fire({
            icon: 'error',
            title: 'Error de validación',
            html: `<ul style="text-align: left;">${errorMessages}</ul>`
        });
    @endif
</script>
