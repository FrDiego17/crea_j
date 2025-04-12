@extends('layouts.admin_aside')
@extends('layouts.app-master')

@section('template_title')
    {{ $conductore->name ?? __('Show') . " " . __('Conductore') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Conductore</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('conductores.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Rutas Id:</strong>
                                    {{ $conductore->rutas_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre:</strong>
                                    {{ $conductore->nombre }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Apellido:</strong>
                                    {{ $conductore->apellido }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Email:</strong>
                                    {{ $conductore->email }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Dui:</strong>
                                    {{ $conductore->dui }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Telefono:</strong>
                                    {{ $conductore->telefono }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Licencia:</strong>
                                    {{ $conductore->licencia }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tipovehiculo:</strong>
                                    {{ $conductore->TipoVehiculo }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
