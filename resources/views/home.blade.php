@extends('admin.layout')

@section('title', '- Inicio')

@section('breadcrumb')
    <li class="breadcrumb-item">
        Inicio
    </li>
@endsection

@section('content')
    @can('ver_consultas')
        <div class="row mb-3">
            <div class="col-3"></div>
            <div class="col-sm-12 col-lg-6">
                {!! Form::open(['method' => 'get', 'route' => 'consultas.search']) !!}
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Buscar pieza</span>
                    </div>
                    <input type="text" name="keywords" class="form-control" placeholder="Palabras clave" required>
                    <div class="input-group-append">
                        <input type="submit" class="btn btn-primary" value="Ir"/>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="col-3"></div>
        </div>
    @endcan
    <div class="row justify-content-around align-items-center">
    @can ('ver_inventario')
        <div class="col-sm-6 col-md-4 col-xl-3 px-2">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="h1 text-muted text-right mb-4">
                        <i class="fa fa-boxes"></i>
                    </div>
                    <div class="text-value">{{number_format($pieces)}}</div>
                    <a href="{{ route('inventario.index') }}" class="text-muted text-uppercase font-weight-bold small stretched-link">Piezas</a>
                    <div class="progress progress-white progress-xs mt-3">
                        <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    @can ('ver_investigacion')
        <div class="col-sm-6 col-md-4 col-xl-3 px-2">
            <div class="card text-white bg-teal">
                <div class="card-body">
                    <div class="h1 text-muted text-right mb-4">
                        <i class="fa fa-microscope"></i>
                    </div>
                    <div class="text-value">{{number_format($researchs)}}</div>
                    <a href="{{ route('investigacion.index') }}" class="text-muted text-uppercase font-weight-bold small stretched-link">Registros de investigaciones</a>
                    <div class="progress progress-white progress-xs mt-3">
                        <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    @can ('ver_restauracion')
        <div class="col-sm-6 col-md-4 col-xl-3 px-2">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="h1 text-muted text-right mb-4">
                        <i class="fa fa-history"></i>
                    </div>
                    <div class="text-value">{{number_format($restorations)}}</div>
                    <a href="{{ route('restauracion.index') }}" class="text-muted text-uppercase font-weight-bold small stretched-link">Registros de restauraciones</a>
                    <div class="progress progress-white progress-xs mt-3">
                        <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    @can ('ver_movimientos')
        <div class="col-sm-6 col-md-4 col-xl-3 px-2">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <div class="h1 text-muted text-right mb-4">
                        <i class="fa fa-truck"></i>
                    </div>
                    <div class="text-value">{{number_format($movements)}}</div>
                    <a href="{{ route('movimientos.index') }}" class="text-muted text-uppercase font-weight-bold small stretched-link">Movimientos</a>
                    <div class="progress progress-white progress-xs mt-3">
                        <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    @can ('ver_reportes')
        <div class="col-sm-6 col-md-4 col-xl-3 px-2">
            <div class="card text-white bg-gray">
                <div class="card-body">
                    <div class="h1 text-muted text-right mb-4">
                        <i class="icon-chart"></i>
                    </div>
                    <div class="text-value">{{number_format($reports)}}</div>
                    <a href="{{ route('reportes.index') }}" class="text-muted text-uppercase font-weight-bold small stretched-link">Reportes</a>
                    <div class="progress progress-white progress-xs mt-3">
                        <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    @can ('ver_usuarios')
        <div class="col-sm-6 col-md-4 col-xl-3 px-2">
            <div class="card text-white bg-cyan">
                <div class="card-body">
                    <div class="h1 text-muted text-right mb-4">
                        <i class="icon-user"></i>
                    </div>
                    <div class="text-value">{{number_format($users)}}</div>
                    <a href="{{ route('usuarios.index') }}" class="text-muted text-uppercase font-weight-bold small stretched-link">Usuarios</a>
                    <div class="progress progress-white progress-xs mt-3">
                        <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    @can ('ver_roles')
        <div class="col-sm-6 col-md-4 col-xl-3 px-2">
            <div class="card text-white bg-pink">
                <div class="card-body">
                    <div class="h1 text-muted text-right mb-4">
                        <i class="icon-people"></i>
                    </div>
                    <div class="text-value">{{number_format($roles)}}</div>
                    <a href="{{ route('roles.index') }}" class="text-muted text-uppercase font-weight-bold small stretched-link">Roles de usuario</a>
                    <div class="progress progress-white progress-xs mt-3">
                        <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    @hasrole('Administrador')
        <div class="col-sm-12 col-md-6 col-xl-4 px-2">
            <div class="card text-white bg-indigo">
                <div class="card-body">
                    <div class="h1 text-muted text-right mb-4">
                        <i class="cui-dollar"></i>
                    </div>
                    <div class="text-value">{{money($appraisal)}} USD</div>
                    <small class="text-muted text-uppercase font-weight-bold">Valor de la colección</small>
                    <div class="progress progress-white progress-xs mt-3">
                        <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    @endhasrole
    </div>
    @include('flash-toastr::message')
@endsection
