@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <h3 class="fw-bold mb-0">Control de Logística</h3>
        <button class="btn btn-outline-primary rounded-3 px-4 shadow-sm fw-bold">
            <i class="fa-solid fa-calendar-check me-2"></i> Ver Checklist General
        </button>
    </div>

    <div class="row g-4">
        <!-- Evento en Montaje -->
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-info">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-1 mb-2">En Montaje</span>
                            <h5 class="fw-bold text-dark mb-0">Boda Civil - Hotel Plaza</h5>
                            <p class="text-muted small mb-0"><i class="fa-solid fa-clock me-1"></i> Inicio: 08:00 AM | Fin Montaje: 12:00 PM</p>
                        </div>
                        <div class="text-end">
                            <div class="small text-muted">Vehículo</div>
                            <div class="fw-bold small text-dark">CAM-001 (NPR)</div>
                        </div>
                    </div>
                    
                    <div class="bg-light rounded-4 p-3 mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small fw-bold text-muted">Progreso Montaje</span>
                            <span class="small fw-bold text-primary">65%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 65%"></div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-grow-1">
                            <div class="small text-muted mb-1">Personal Asignado</div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center small fw-bold text-primary" style="width: 32px; height: 32px;">RP</div>
                                <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center small fw-bold text-info" style="width: 32px; height: 32px;">ML</div>
                                <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center small fw-bold text-warning" style="width: 32px; height: 32px;">+2</div>
                            </div>
                        </div>
                        <button class="btn btn-primary rounded-3 fw-bold px-4 shadow-sm">
                            Gestionar Checklist
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Evento por Recoger -->
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-warning">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-1 mb-2">Por Recoger</span>
                            <h5 class="fw-bold text-dark mb-0">Cumpleaños Infantil - Casa Campestre</h5>
                            <p class="text-muted small mb-0"><i class="fa-solid fa-clock me-1"></i> Recogida: 06:00 PM</p>
                        </div>
                        <div class="text-end">
                            <div class="small text-muted">Vehículo</div>
                            <div class="fw-bold small text-dark">CAM-002 (Van)</div>
                        </div>
                    </div>
                    
                    <div class="bg-light rounded-4 p-3 mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small fw-bold text-muted">Checklist Salida</span>
                            <span class="small fw-bold text-success">Completado <i class="fa-solid fa-check"></i></span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-grow-1">
                            <div class="small text-muted mb-1">Responsable</div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center small fw-bold text-primary" style="width: 32px; height: 32px;">JC</div>
                                <span class="small fw-bold">Juan Castro</span>
                            </div>
                        </div>
                        <button class="btn btn-warning rounded-3 fw-bold px-4 shadow-sm text-white">
                            Iniciar Recogida
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
