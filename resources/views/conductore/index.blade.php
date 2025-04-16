@extends('layouts.admin_aside')
@extends('layouts.app-masterAdmin')

@section('template_title')
    Conductores
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Conductores') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('conductores.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
									<th >Rutas Id</th>
									<th >Nombre</th>
									<th >Apellido</th>
									<th >Email</th>
									<th >Dui</th>
									<th >Telefono</th>
									<th >Licencia</th>
									<th >Tipovehiculo</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($conductores as $conductore)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $conductore->rutas_id }}</td>
										<td >{{ $conductore->nombre }}</td>
										<td >{{ $conductore->apellido }}</td>
										<td >{{ $conductore->email }}</td>
										<td >{{ $conductore->dui }}</td>
										<td >{{ $conductore->telefono }}</td>
										<td >{{ $conductore->licencia }}</td>
										<td >{{ $conductore->TipoVehiculo }}</td>

                                            <td>
                                                <form action="{{ route('conductores.destroy', $conductore->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('conductores.show', $conductore->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('conductores.edit', $conductore->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $conductores->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
