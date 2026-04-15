@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('eventos.equipos.index') }}" class="text-decoration-none text-muted">Inventario</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detalles del Equipo</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0">{{ $equipo->nombre }}</h3>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('eventos.equipos.edit', $equipo->id) }}" class="btn btn-primary rounded-3 px-4 shadow-sm fw-bold">
                    <i class="fa-solid fa-pen me-2"></i> Editar Equipo
                </a>
                <a href="{{ route('eventos.equipos.index') }}" class="btn btn-light rounded-3 px-4 shadow-sm fw-bold border">
                    <i class="fa-solid fa-arrow-left me-2"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Columna Izquierda: Imagen y Estado -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                @php
                    $imageSrc = $equipo->imagen ? (str_starts_with($equipo->imagen, 'http') ? $equipo->imagen : asset('storage/' . $equipo->imagen)) : null;
                @endphp
                <div class="bg-light d-flex align-items-center justify-content-center overflow-hidden" style="min-height: 300px;">
                    @if($imageSrc)
                        <img src="{{ $imageSrc }}" alt="{{ $equipo->nombre }}" class="w-100 h-100 object-fit-cover shadow-sm">
                    @else
                        <div class="text-center py-5">
                            <i class="fa-solid fa-image text-muted fs-1 opacity-25 mb-3 d-block"></i>
                            <span class="text-muted">Sin imagen disponible</span>
                        </div>
                    @endif
                </div>
                <div class="card-body p-4 text-center">
                    @php
                        $statusClass = match($equipo->estado) {
                            'Disponible' => 'bg-success',
                            'Mantenimiento' => 'bg-warning text-dark',
                            'Baja' => 'bg-danger',
                            default => 'bg-secondary'
                        };
                    @endphp
                    <div class="badge {{ $statusClass }} rounded-pill px-4 py-2 fs-6 shadow-sm mb-3">
                        {{ $equipo->estado }}
                    </div>
                    <div class="text-muted small">Estado actual en inventario</div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h6 class="fw-bold text-muted small text-uppercase mb-4">Disponibilidad de Stock</h6>
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted">Cantidad Total:</span>
                    <span class="fw-bold fs-5">{{ $equipo->cantidad_total }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <span class="text-muted">Disponible para Alquiler:</span>
                    <span class="fw-bold fs-5 {{ $equipo->cantidad_disponible > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $equipo->cantidad_disponible }}
                    </span>
                </div>
                <div class="progress rounded-pill" style="height: 10px;">
                    @php $percent = ($equipo->cantidad_total > 0) ? ($equipo->cantidad_disponible / $equipo->cantidad_total) * 100 : 0; @endphp
                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percent }}%"></div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Información y Precios -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 h-100">
                <div class="mb-5">
                    <h6 class="fw-bold text-primary small text-uppercase mb-2">{{ $equipo->categoria->nombre }}</h6>
                    <h2 class="fw-bold text-dark mb-4">Especificaciones del Equipo</h2>
                    <p class="text-muted fs-5 lh-base">
                        {{ $equipo->descripcion ?: 'Este equipo no cuenta con una descripción detallada registrada actualmente.' }}
                    </p>
                </div>

                <hr class="my-5 opacity-10">

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="bg-light p-4 rounded-4 border">
                            <h6 class="fw-bold text-muted small text-uppercase mb-2">Tarifa por Día</h6>
                            <div class="d-flex align-items-baseline">
                                <span class="fs-2 fw-bold text-dark me-2">${{ number_format($equipo->precio_dia) }}</span>
                                <span class="text-muted">/ jornada</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-light p-4 rounded-4 border">
                            <h6 class="fw-bold text-muted small text-uppercase mb-2">Tarifa por Hora</h6>
                            <div class="d-flex align-items-baseline">
                                <span class="fs-2 fw-bold text-dark me-2">${{ number_format($equipo->precio_hora) }}</span>
                                <span class="text-muted">/ hora fraccionada</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-auto pt-5">
                    <div class="alert bg-primary bg-opacity-10 border-0 rounded-4 p-4 d-flex align-items-center">
                        <div class="bg-primary text-white p-3 rounded-3 me-4">
                            <i class="fa-solid fa-circle-info fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-primary mb-1">Nota de Logística</h6>
                            <p class="text-primary small mb-0 opacity-75">
                                Los precios mostrados no incluyen transporte ni operarios técnicos. Estos valores deben ser calculados adicionalmente en la cotización.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
