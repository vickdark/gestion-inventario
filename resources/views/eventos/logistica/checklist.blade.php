@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('eventos.logistica.index') }}">Logística</a></li>
                    <li class="breadcrumb-item active">Checklist General</li>
                </ol>
            </nav>
            <h3 class="fw-bold mb-0">Checklist General de Equipos</h3>
            <p class="text-muted mb-0">Consolidado de equipos necesarios para todos los eventos activos.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-light rounded-3 px-4 shadow-sm fw-bold border" onclick="window.print()">
                <i class="fa-solid fa-print me-2"></i> Imprimir Checklist
            </button>
            <a href="{{ route('eventos.logistica.index') }}" class="btn btn-primary rounded-3 px-4 shadow-sm fw-bold">
                Regresar
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Resumen Consolidado -->
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-0 fw-bold"><i class="fa-solid fa-boxes-stacked text-primary me-2"></i> Equipos a Alistar</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 text-muted small text-uppercase border-0">Equipo / Item</th>
                                    <th class="py-3 text-muted small text-uppercase border-0 text-center">Cantidad Total</th>
                                    <th class="pe-4 py-3 text-muted small text-uppercase border-0 text-end">Estado Cargo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($checklist as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="form-check me-3">
                                                <input class="form-check-input border-2" type="checkbox" style="width: 1.2rem; height: 1.2rem;">
                                            </div>
                                            <span class="fw-bold">{{ $item->nombre }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">
                                            {{ $item->total }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <span class="text-muted small">Pendiente de carga</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <i class="fa-solid fa-clipboard-check fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No hay equipos pendientes por alistar.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Eventos Incluidos -->
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-0 fw-bold"><i class="fa-solid fa-calendar-alt text-info me-2"></i> Eventos Cubiertos</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="list-group list-group-flush">
                        @foreach($eventos_pendientes as $evento)
                        <div class="list-group-item bg-transparent px-0 py-3">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="fw-bold mb-0">{{ $evento->cliente->nombre }}</h6>
                                <span class="badge {{ 
                                    match($evento->estado_logistica) {
                                        'pendiente' => 'bg-secondary',
                                        'en_montaje' => 'bg-info',
                                        'montado' => 'bg-success',
                                        'recogiendo' => 'bg-warning',
                                        default => 'bg-secondary'
                                    }
                                }} bg-opacity-10 {{ 
                                    match($evento->estado_logistica) {
                                        'pendiente' => 'text-secondary',
                                        'en_montaje' => 'text-info',
                                        'montado' => 'text-success',
                                        'recogiendo' => 'text-warning',
                                        default => 'text-secondary'
                                    }
                                }} rounded-pill small">{{ ucfirst($evento->estado_logistica) }}</span>
                            </div>
                            <p class="small text-muted mb-0">
                                <i class="fa-solid fa-map-marker-alt me-1"></i> {{ $evento->direccion_evento ?? 'Sin dirección' }}
                            </p>
                            <div class="mt-2">
                                @foreach($evento->items as $item)
                                <span class="badge bg-light text-dark fw-normal border me-1 mb-1">{{ $item->cantidad }}x {{ $item->nombre }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Notas de Preparación -->
            <div class="card border-0 shadow-sm rounded-4 mt-4 bg-primary bg-opacity-10 border-start border-primary border-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-primary mb-2"><i class="fa-solid fa-circle-info me-2"></i> Instrucciones</h6>
                    <ul class="small text-dark mb-0 ps-3">
                        <li>Verifique el estado de carga de las baterías.</li>
                        <li>Asegúrese de llevar cables de repuesto para los equipos de sonido.</li>
                        <li>Confirme la disponibilidad del vehículo asignado.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, nav, .card-header .fa-solid, .instrucciones {
        display: none !important;
    }
    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
    body {
        background: white !important;
    }
}
</style>
@endsection
