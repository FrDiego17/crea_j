@extends('layouts.app-masterAdmin')

<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="origen" class="form-label">{{ __('Origen') }}</label>
            <input type="text" name="origen" class="form-control @error('origen') is-invalid @enderror" value="{{ old('origen', $ruta?->origen) }}" id="origen" placeholder="Origen">
            {!! $errors->first('origen', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="descripcion" class="form-label">{{ __('descripcion') }}</label>
            <input type="text" name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" value="{{ old('descripcion', $ruta?->descripcion) }}" id="descripcion" placeholder="descripcion">
            {!! $errors->first('descripcion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="horario" class="form-label">{{ __('horario') }}</label>
            <input type="text" name="horario" class="form-control @error('horario') is-invalid @enderror" value="{{ old('horario', $ruta?->horario) }}" id="horario" placeholder="horario">
            {!! $errors->first('horario', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>


    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>