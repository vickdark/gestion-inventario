@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <h3 class="fw-bold mb-0">Gestión de Paquetes</h3>
        <button class="btn btn-primary rounded-3 px-4 shadow-sm fw-bold">
            <i class="fa-solid fa-plus me-2"></i> Crear Nuevo Paquete
        </button>
    </div>

    <div class="row g-4">
        @foreach($paquetes as $paquete)
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden shadow-soft">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-4">
                            <i class="fa-solid fa-box-open text-primary fs-4"></i>
                        </div>
                        <div class="text-end">
                            <div class="small text-muted">Precio Total</div>
                            <h4 class="fw-bold text-primary mb-0">${{ number_format($paquete['precio']) }}</h4>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">{{ $paquete['nombre'] }}</h5>
                    <p class="text-muted small mb-4">
                        <i class="fa-solid fa-list-check me-2"></i>
                        {{ $paquete['equipos'] }}
                    </p>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary rounded-3 flex-grow-1 fw-bold shadow-sm">
                            <i class="fa-solid fa-cart-plus me-2"></i> Usar en Cotización
                        </button>
                        <button class="btn btn-light rounded-3 shadow-sm">
                            <i class="fa-solid fa-pen text-primary"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
