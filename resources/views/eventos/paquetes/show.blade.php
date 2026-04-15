@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('eventos.paquetes.index') }}" class="text-decoration-none text-muted">Paquetes</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detalles del Paquete</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0">{{ $paquete->nombre }}</h3>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('eventos.paquetes.edit', $paquete->id) }}" class="btn btn-primary rounded-3 px-4 shadow-sm fw-bold">
                    <i class="fa-solid fa-pen me-2"></i> Editar Paquete
                </a>
                <a href="{{ route('eventos.paquetes.index') }}" class="btn btn-light rounded-3 px-4 shadow-sm fw-bold border">
                    <i class="fa-solid fa-arrow-left me-2"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Columna Izquierda: Resumen y Precio -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                @php
                    $imageSrc = $paquete->imagen ? (str_starts_with($paquete->imagen, 'http') ? $paquete->imagen : asset('storage/' . $paquete->imagen)) : null;
                @endphp
                @if($imageSrc)
                    <div style="height: 250px;">
                        <img src="{{ $imageSrc }}" alt="{{ $paquete->nombre }}" class="w-100 h-100 object-fit-cover">
                    </div>
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fa-solid fa-box-open text-muted fs-1 opacity-25"></i>
                    </div>
                @endif
                <div class="card-body p-4 text-center">
                    <div class="row g-2 mb-4">
                        <div class="col-6">
                            <h6 class="fw-bold text-muted small text-uppercase mb-1">Precio Día</h6>
                            <h4 class="fw-bold text-primary mb-0">${{ number_format($paquete->precio_dia) }}</h4>
                        </div>
                        <div class="col-6 border-start">
                            <h6 class="fw-bold text-muted small text-uppercase mb-1">Precio Hora</h6>
                            <h4 class="fw-bold text-primary mb-0">${{ number_format($paquete->precio_hora) }}</h4>
                        </div>
                    </div>
                    <hr class="my-4 opacity-10">
                    <p class="text-muted small mb-0">
                        {{ $paquete->descripcion ?: 'Este paquete no cuenta con una descripción detallada.' }}
                    </p>
                </div>
            </div>

            <div class="alert bg-primary bg-opacity-10 border-0 rounded-4 p-4">
                <div class="d-flex">
                    <i class="fa-solid fa-circle-info text-primary fs-4 me-3"></i>
                    <div>
                        <h6 class="fw-bold text-primary mb-1">Información de Paquetes</h6>
                        <p class="text-primary small mb-0 opacity-75">
                            Los paquetes permiten agrupar equipos comunes para agilizar el proceso de cotización. Al usar un paquete, el stock de todos los equipos incluidos se reservará automáticamente.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Equipos Incluidos -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <h5 class="fw-bold mb-4">Lista de Equipos Incluidos</h5>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Equipo</th>
                                <th>Categoría</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Precio Unit. (Ref)</th>
                                <th class="text-end">Subtotal (Ref)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $totalRefDia = 0; 
                                $totalRefHora = 0;
                            @endphp
                            @foreach($paquete->equipos as $equipo)
                                @php 
                                    $subtotalDia = $equipo->precio_dia * $equipo->pivot->cantidad;
                                    $subtotalHora = $equipo->precio_hora * $equipo->pivot->cantidad;
                                    $totalRefDia += $subtotalDia;
                                    $totalRefHora += $subtotalHora;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $equipo->nombre }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border rounded-pill">{{ $equipo->categoria->nombre }}</span>
                                    </td>
                                    <td class="text-center fw-bold">
                                        {{ $equipo->pivot->cantidad }}
                                    </td>
                                    <td class="text-end text-muted small">
                                        Día: ${{ number_format($equipo->precio_dia) }}<br>
                                        Hora: ${{ number_format($equipo->precio_hora) }}
                                    </td>
                                    <td class="text-end fw-bold">
                                        Día: ${{ number_format($subtotalDia) }}<br>
                                        Hora: ${{ number_format($subtotalHora) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Total Referencial (Suma de equipos):</td>
                                <td class="text-end fw-bold text-dark">
                                    Día: ${{ number_format($totalRefDia) }}<br>
                                    Hora: ${{ number_format($totalRefHora) }}
                                </td>
                            </tr>
                            <tr class="table-primary bg-opacity-10">
                                <td colspan="4" class="text-end fw-bold text-primary">Precio Final del Paquete:</td>
                                <td class="text-end fw-bold text-primary fs-5">
                                    Día: ${{ number_format($paquete->precio_dia) }}<br>
                                    Hora: ${{ number_format($paquete->precio_hora) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($paquete->precio_dia < $totalRefDia)
                    <div class="mt-3 text-end">
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                            <i class="fa-solid fa-tag me-1"></i> Ahorro del {{ round((1 - ($paquete->precio_dia / $totalRefDia)) * 100) }}% en tarifa diaria
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
