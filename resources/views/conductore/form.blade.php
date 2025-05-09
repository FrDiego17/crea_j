<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="rutas_id" class="form-label">{{ __('Ruta ID') }}</label>
            <input type="text" name="rutas_id" class="form-control @error('rutas_id') is-invalid @enderror" value="{{ old('rutas_id', $conductore?->rutas_id) }}" id="rutas_id" placeholder="Ruta id">
            {!! $errors->first('rutas_origen', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $conductore?->nombre) }}" id="nombre" placeholder="Nombre">
            {!! $errors->first('nombre', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="apellido" class="form-label">{{ __('Apellido') }}</label>
            <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror" value="{{ old('apellido', $conductore?->apellido) }}" id="apellido" placeholder="Apellido">
            {!! $errors->first('apellido', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>  
        <div class="form-group mb-2 mb20">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $conductore?->email) }}" id="email" placeholder="Email">
            {!! $errors->first('email', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="dui" class="form-label">{{ __('Dui') }}</label>
            <input type="text" name="dui" class="form-control @error('dui') is-invalid @enderror" value="{{ old('dui', $conductore?->dui) }}" id="dui" placeholder="Dui">
            {!! $errors->first('dui', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="telefono" class="form-label">{{ __('Telefono') }}</label>
            <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono', $conductore?->telefono) }}" id="telefono" placeholder="Telefono">
            {!! $errors->first('telefono', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="licencia" class="form-label">{{ __('Licencia') }}</label>
            <input type="text" name="licencia" class="form-control @error('licencia') is-invalid @enderror" value="{{ old('licencia', $conductore?->licencia) }}" id="licencia" placeholder="Licencia">
            {!! $errors->first('licencia', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="TipoVehiculo" class="form-label">{{ __('Tipovehiculo') }}</label>
            <input type="text" name="TipoVehiculo" class="form-control @error('TipoVehiculo') is-invalid @enderror" value="{{ old('TipoVehiculo', $conductore?->TipoVehiculo) }}" id="tipo_vehiculo" placeholder="Tipovehiculo">
            {!! $errors->first('TipoVehiculo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>