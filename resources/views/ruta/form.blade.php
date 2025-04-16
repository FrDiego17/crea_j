@extends('layouts.app-masterAdmin')

<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="origen" class="form-label">{{ __('Origen') }}</label>
            <input type="text" name="origen" class="form-control @error('origen') is-invalid @enderror" value="{{ old('origen', $ruta?->origen) }}" id="origen" placeholder="Origen">
            {!! $errors->first('origen', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>