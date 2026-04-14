@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <h3 class="fw-bold mb-0">Inventario de Equipos</h3>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary rounded-3 px-3 shadow-sm fw-bold">
                <i class="fa-solid fa-layer-group me-2"></i> Categorías
            </button>
            <button class="btn btn-primary rounded-3 px-4 shadow-sm fw-bold">
                <i class="fa-solid fa-plus me-2"></i> Nuevo Equipo
            </button>
        </div>
    </div>

    <!-- Filtros por Categoría -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap gap-2">
                <button class="btn btn-sm btn-primary rounded-pill px-4 fw-bold">Todos</button>
                @foreach($categorias as $categoria)
                    <button class="btn btn-sm btn-light border rounded-pill px-4 fw-bold">{{ $categoria }}</button>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row g-4">
        @foreach($equipos as $equipo)
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hvr-float shadow-soft">
                <div class="position-relative">
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                        <i class="fa-solid fa-image text-muted fs-1 opacity-25"></i>
                    </div>
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge {{ $equipo['estado'] === 'Disponible' ? 'bg-success' : 'bg-danger' }} rounded-pill px-3 py-2 shadow-sm">
                            {{ $equipo['estado'] }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="small text-primary fw-bold text-uppercase mb-1" style="font-size: 0.65rem; letter-spacing: 0.05em;">
                        {{ $equipo['categoria'] }}
                    </div>
                    <h5 class="fw-bold text-dark mb-3">{{ $equipo['nombre'] }}</h5>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="small text-muted">Precio / Día</div>
                            <div class="fw-bold text-dark fs-5">${{ number_format($equipo['precio']) }}</div>
                        </div>
                        <div class="text-end">
                            <div class="small text-muted">Stock</div>
                            <div class="fw-bold text-dark fs-5">{{ $equipo['cantidad'] }}</div>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary btn-sm rounded-3 fw-bold py-2">
                            <i class="fa-solid fa-eye me-1"></i> Detalles
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
