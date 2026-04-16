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
        <!-- Equipos en uso -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning bg-opacity-10 p-3 rounded-4 me-3">
                            <i class="fa-solid fa-truck-ramp-box text-warning fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0 small text-uppercase fw-bold">Equipos en uso</h6>
                            <h2 class="mb-0 fw-bold">{{ $equipos_alquilados }}</h2>
                        </div>
                    </div>
                    @php $porcentaje_uso = ($total_equipos > 0) ? ($equipos_alquilados / $total_equipos) * 100 : 0; @endphp
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $porcentaje_uso }}%"></div>
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
                            <h6 class="text-muted mb-0 small text-uppercase fw-bold">Logística Pendiente</h6>
                            <h2 class="mb-0 fw-bold">{{ $alertas }}</h2>
                        </div>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $alertas > 0 ? 100 : 0 }}%"></div>
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
                    <h5 class="mb-0 fw-bold">Agenda Próxima</h5>
                    <a href="{{ route('eventos.agenda.index') }}" class="btn btn-sm btn-outline-primary rounded-3 px-3">Ver todo</a>
                </div>
                <div class="card-body p-0">
                    <!-- Vista Desktop: Tabla -->
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 text-muted small text-uppercase border-0 text-nowrap">Evento / Ubicación</th>
                                    <th class="py-3 text-muted small text-uppercase border-0 text-nowrap">Cliente</th>
                                    <th class="py-3 text-muted small text-uppercase border-0 text-nowrap">Fecha / Hora</th>
                                    <th class="py-3 text-muted small text-uppercase border-0 text-center text-nowrap">Logística</th>
                                    <th class="pe-4 py-3 text-muted small text-uppercase border-0 text-end"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($proximos_eventos as $evento)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-truncate" style="max-width: 200px;">{{ $evento->numero_factura }}</div>
                                        <div class="extra-small text-muted text-truncate" style="max-width: 200px;">
                                            <i class="fa-solid fa-location-dot me-1"></i> {{ $evento->direccion_evento ?: 'Sin dirección' }}
                                        </div>
                                    </td>
                                    <td class="text-nowrap">
                                        <div class="small fw-bold">{{ $evento->cliente->nombre }}</div>
                                    </td>
                                    <td class="text-nowrap">
                                        <div class="small fw-bold">{{ $evento->fecha_evento_inicio->format('d/m/Y') }}</div>
                                        <div class="extra-small text-muted">{{ $evento->fecha_evento_inicio->format('h:i A') }}</div>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $badgeClass = 'bg-secondary';
                                            if($evento->estado_logistica == 'pendiente') $badgeClass = 'bg-danger';
                                            if($evento->estado_logistica == 'en_montaje') $badgeClass = 'bg-warning';
                                            if($evento->estado_logistica == 'montado') $badgeClass = 'bg-info';
                                            if($evento->estado_logistica == 'recogiendo') $badgeClass = 'bg-primary';
                                        @endphp
                                        <span class="badge {{ $badgeClass }} bg-opacity-10 text-{{ str_replace('bg-', '', $badgeClass) }} rounded-pill px-3 py-2">
                                            {{ ucfirst(str_replace('_', ' ', $evento->estado_logistica)) }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <a href="{{ route('eventos.ventas.show', $evento->id) }}" class="btn btn-sm btn-light rounded-3 shadow-sm border px-3">
                                            <i class="fa-solid fa-eye small me-1"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted small">No hay eventos próximos registrados</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Vista Mobile: Listado de Cards -->
                    <div class="d-md-none p-3">
                        @forelse($proximos_eventos as $evento)
                        <div class="card border rounded-4 mb-3 shadow-none bg-light bg-opacity-50">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $evento->numero_factura }}</div>
                                        <div class="small text-muted mb-2">{{ $evento->cliente->nombre }}</div>
                                    </div>
                                    @php
                                        $badgeClass = 'bg-secondary';
                                        if($evento->estado_logistica == 'pendiente') $badgeClass = 'bg-danger';
                                        if($evento->estado_logistica == 'en_montaje') $badgeClass = 'bg-warning';
                                        if($evento->estado_logistica == 'montado') $badgeClass = 'bg-info';
                                        if($evento->estado_logistica == 'recogiendo') $badgeClass = 'bg-primary';
                                    @endphp
                                    <span class="badge {{ $badgeClass }} bg-opacity-10 text-{{ str_replace('bg-', '', $badgeClass) }} rounded-pill px-2 py-1 small">
                                        {{ ucfirst(str_replace('_', ' ', $evento->estado_logistica)) }}
                                    </span>
                                </div>
                                <div class="small text-muted mb-3">
                                    <div class="mb-1"><i class="fa-solid fa-calendar me-2"></i> {{ $evento->fecha_evento_inicio->format('d/m/Y') }}</div>
                                    <div class="mb-1"><i class="fa-solid fa-clock me-2"></i> {{ $evento->fecha_evento_inicio->format('h:i A') }}</div>
                                    <div class="text-truncate"><i class="fa-solid fa-location-dot me-2"></i> {{ $evento->direccion_evento ?: 'Sin dirección' }}</div>
                                </div>
                                <a href="{{ route('eventos.ventas.show', $evento->id) }}" class="btn btn-outline-primary btn-sm rounded-3 w-100 fw-bold">
                                    Ver Detalles del Evento
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted small">No hay eventos próximos registrados</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Disponibilidad de Equipos -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-0 fw-bold">Disponibilidad Crítica</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <p class="small text-muted mb-4">Equipos con mayor demanda actual.</p>
                    
                    @foreach($equipos_criticos as $equipo)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small fw-bold text-truncate" style="max-width: 180px;">{{ $equipo->nombre }}</span>
                            <span class="small text-muted">{{ $equipo->disponibles }} / {{ $equipo->cantidad_total }} Disponibles</span>
                        </div>
                        <div class="progress" style="height: 8px; border-radius: 10px;">
                            @php
                                $barClass = 'bg-primary';
                                if($equipo->porcentaje_uso > 70) $barClass = 'bg-warning';
                                if($equipo->porcentaje_uso > 90) $barClass = 'bg-danger';
                            @endphp
                            <div class="progress-bar {{ $barClass }}" role="progressbar" style="width: {{ $equipo->porcentaje_uso }}%"></div>
                        </div>
                    </div>
                    @endforeach

                    @if($equipos_criticos->isEmpty())
                        <div class="text-center py-4 text-muted border rounded-4 border-dashed">
                            <i class="fa-solid fa-boxes-stacked mb-2 opacity-25"></i>
                            <p class="small mb-0">Sin datos de equipos</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
