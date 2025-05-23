@extends('layouts.admin_aside')
@extends('layouts.app-masterAdmin')

@section('template_title')
    {{ __('Update') }} Ruta
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Ruta</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('rutas.update', $ruta->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('ruta.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
