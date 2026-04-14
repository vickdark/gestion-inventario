@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4 mb-4">
        <!-- Eventos del día -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-4 me-3">
                            <i class="fa-solid fa-calendar-day text-primary fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0 small text-uppercase fw-bold">Eventos hoy</h6>
                            <h2 class="mb-0 fw-bold">{{ $eventos_dia }}</h2>
                        </div>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 75%"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Eventos próximos -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 p-3 rounded-4 me-3">
                            <i class="fa-solid fa-calendar-week text-success fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0 small text-uppercase fw-bold">Próximos eventos</h6>
                            <h2 class="mb-0 fw-bold">{{ $eventos_proximos }}</h2>
                        </div>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 45%"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Equipos ocupados -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning bg-opacity-10 p-3 rounded-4 me-3">
                            <i class="fa-solid fa-truck-ramp-box text-warning fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0 small text-uppercase fw-bold">Equipos en uso</h6>
                            <h2 class="mb-0 fw-bold">{{ $equipos_ocupados }}</h2>
                        </div>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Alertas -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-danger bg-opacity-10 p-3 rounded-4 me-3">
                            <i class="fa-solid fa-triangle-exclamation text-danger fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0 small text-uppercase fw-bold">Alertas activas</h6>
                            <h2 class="mb-0 fw-bold">{{ $alertas }}</h2>
                        </div>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Próximos Eventos Detallados -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 p-4 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fw-bold">Agenda de Hoy</h5>
                    <a href="{{ route('eventos.agenda.index') }}" class="btn btn-sm btn-outline-primary rounded-3">Ver todo</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 text-muted small text-uppercase border-0">Evento</th>
                                    <th class="py-3 text-muted small text-uppercase border-0">Cliente</th>
                                    <th class="py-3 text-muted small text-uppercase border-0">Hora</th>
                                    <th class="py-3 text-muted small text-uppercase border-0 text-center">Estado</th>
                                    <th class="pe-4 py-3 text-muted small text-uppercase border-0 text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">Cumpleaños Infantil - Inflable</div>
                                        <div class="extra-small text-muted">Calle 10 #20-30</div>
                                    </td>
                                    <td>Ana Martínez</td>
                                    <td>02:00 PM</td>
                                    <td class="text-center">
                                        <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2">En montaje</span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <button class="btn btn-sm btn-light rounded-3"><i class="fa-solid fa-eye"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">Evento Corporativo ABC</div>
                                        <div class="extra-small text-muted">Centro Convenciones</div>
                                    </td>
                                    <td>Carlos Rodríguez</td>
                                    <td>06:00 PM</td>
                                    <td class="text-center">
                                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2">Confirmado</span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <button class="btn btn-sm btn-light rounded-3"><i class="fa-solid fa-eye"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Disponibilidad de Equipos -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-0 fw-bold">Equipos Críticos</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small fw-bold">Sonido Profesional</span>
                            <span class="small text-muted">2/5 disponibles</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 40%"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small fw-bold">Inflables</span>
                            <span class="small text-muted">0/3 disponibles</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 100%"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small fw-bold">Sillas Tiffany</span>
                            <span class="small text-muted">150/500 disponibles</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 70%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
