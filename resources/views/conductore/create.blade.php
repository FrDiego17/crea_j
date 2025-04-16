@extends('layouts.admin_aside')
@extends('layouts.app-masterAdmin')

@section('template_title')
    {{ __('Create') }} Conductore
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Conductore</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('conductores.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('conductore.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
