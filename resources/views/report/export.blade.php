@extends('layouts.app')
@section('content')

@if (!auth()->check())
    <?php header("Location: /login"); exit; ?>
@endif

<div class="border rounded shadow m-3 p-2 bg-white">
    <h1 class="fs-2 fw-bold m-0"><a href="{{ route('crm', ['status' => 1, 'page' => 1 ]) }}"><i class="bi bi-arrow-left m-3"></i></a>Crea un reporte de los servicios</h1>
    <form class="form m-3" method="POST" action="{{ route('reportServs.create',['va'=>$va]) }}" enctype="multipart/form-data">
        @csrf
        <div style="color:red;" class="form-text" id="basic-addon4">Llena los campos requeridos</div>
        <div class="row">
            <div class="col-sm">
                <label class="form-label is-required" for="fechaI">Fecha Inicio:</label>
                <input class="form-control border-secondary border-opacity-25 "  type="date" name="fechaI" required>
            </div>
            <div class="col-sm">
                <label class="form-label is-required" for="fechaF">Fecha Fin:</label>
                <input class="form-control border-secondary border-opacity-25 " type="date" name="fechaF" required>
            </div>
            <div class="col-sm">
                <label class="form-label is-required" for="branch">Sucursal:</label>
                <select class="form-control border-secondary border-opacity-25 " name="branch" required>
                    @foreach($branchs as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm" style="padding-top: 30px;">
                <button class="btn btn-primary w-100" type="submit">Exportar a Excel</button>
            </div>
        </div>
        
    </form>
</div>
@endsection