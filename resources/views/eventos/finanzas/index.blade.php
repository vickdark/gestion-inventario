@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold mb-0">Gestión de Finanzas</h3>
            <p class="text-muted mb-0">Balance de ventas, recaudos y gastos operativos.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary rounded-3 px-4 shadow-sm fw-bold" onclick="window.print()">
                <i class="fa-solid fa-print me-2"></i> Informe para Impresión
            </button>
        </div>
    </div>

    <!-- Totales Cards -->
    <div class="row g-4 mb-4" id="totales-finance">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body p-4 text-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mb-3 mx-auto" style="width: 50px; height: 50px;">
                        <i class="fa-solid fa-file-invoice-dollar fs-4"></i>
                    </div>
                    <div class="small fw-bold text-muted text-uppercase mb-1">Ventas Generadas</div>
                    <h3 class="fw-bold text-dark mb-0">${{ number_format($total_ventas, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white border-start border-4 border-danger">
                <div class="card-body p-4 text-center">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center mb-3 mx-auto" style="width: 50px; height: 50px;">
                        <i class="fa-solid fa-truck-fast fs-4"></i>
                    </div>
                    <div class="small fw-bold text-muted text-uppercase mb-1">Gastos Operativos</div>
                    <h3 class="fw-bold text-danger mb-0">${{ number_format($total_gastos, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white border-start border-4 border-success">
                <div class="card-body p-4 text-center">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center mb-3 mx-auto" style="width: 50px; height: 50px;">
                        <i class="fa-solid fa-hand-holding-dollar fs-4"></i>
                    </div>
                    <div class="small fw-bold text-muted text-uppercase mb-1">Efectivo Recaudado</div>
                    <h3 class="fw-bold text-success mb-0">${{ number_format($total_cobrado, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-dark text-white shadow-lg">
                <div class="card-body p-4 text-center">
                    <div class="bg-white bg-opacity-10 text-white rounded-circle d-flex align-items-center justify-content-center mb-3 mx-auto" style="width: 50px; height: 50px;">
                        <i class="fa-solid fa-chart-line fs-4 text-warning"></i>
                    </div>
                    <div class="small fw-bold text-white-50 text-uppercase mb-1">Ganancia Neta (Recaudado - Gastos)</div>
                    <h3 class="fw-bold mb-0 text-white">${{ number_format($utilidad_neta, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card border-0 shadow-sm rounded-4 mb-4" id="filtros-finance">
        <div class="card-body p-4">
            <form action="{{ route('eventos.finanzas.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="small fw-bold text-muted mb-2"><i class="fa-solid fa-search me-1"></i> Buscar</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-3">
                            <i class="fa-solid fa-magnifying-glass text-muted small"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 rounded-end-3" placeholder="Cliente o factura..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <label class="small fw-bold text-muted mb-2"><i class="fa-solid fa-calendar-alt me-1"></i> Desde</label>
                    <input type="date" name="fecha_inicio" class="form-control rounded-3" value="{{ request('fecha_inicio') }}">
                </div>
                <div class="col-6 col-md-3">
                    <label class="small fw-bold text-muted mb-2"><i class="fa-solid fa-calendar-check me-1"></i> Hasta</label>
                    <input type="date" name="fecha_fin" class="form-control rounded-3" value="{{ request('fecha_fin') }}">
                </div>
                <div class="col-12 col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-3 w-100 fw-bold shadow-sm">
                            Actualizar Informe
                        </button>
                        @if(request()->anyFilled(['search', 'fecha_inicio', 'fecha_fin']))
                            <a href="{{ route('eventos.finanzas.index') }}" class="btn btn-light rounded-3 px-3 fw-bold border">
                                <i class="fa-solid fa-rotate-left"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla Detallada -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-muted small text-uppercase border-0">Factura / Cliente</th>
                            <th class="py-3 text-muted small text-uppercase border-0">Venta Total</th>
                            <th class="py-3 text-muted small text-uppercase border-0">Cobrado</th>
                            <th class="py-3 text-muted small text-uppercase border-0">Pendiente</th>
                            <th class="py-3 text-muted small text-uppercase border-0">Gastos Op.</th>
                            <th class="py-3 text-muted small text-uppercase border-0">Margen Real</th>
                            <th class="pe-4 py-3 text-muted small text-uppercase border-0 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ventas as $venta)
                        @php
                            $recaudado = $venta->pagos->sum('monto');
                            $gastos = $venta->gastos->sum('monto');
                            $pendiente = $venta->total - $recaudado;
                            $utilidad = $recaudado - $gastos;
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold">{{ $venta->numero_factura }}</div>
                                <div class="text-muted small">{{ $venta->cliente->nombre }}</div>
                            </td>
                            <td><span class="fw-bold">${{ number_format($venta->total, 0, ',', '.') }}</span></td>
                            <td><span class="text-success fw-bold">${{ number_format($recaudado, 0, ',', '.') }}</span></td>
                            <td>
                                <span class="badge {{ $pendiente > 0 ? 'bg-danger bg-opacity-10 text-danger' : 'bg-success bg-opacity-10 text-success' }} rounded-pill">
                                    ${{ number_format($pendiente, 0, ',', '.') }}
                                </span>
                            </td>
                            <td><span class="text-danger">${{ number_format($gastos, 0, ',', '.') }}</span></td>
                            <td>
                                <span class="fw-bold {{ $utilidad >= 0 ? 'text-dark' : 'text-danger' }}">
                                    ${{ number_format($utilidad, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-3 px-3 border shadow-sm fw-bold" type="button" data-bs-toggle="dropdown">
                                        Gestionar
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end rounded-3 shadow-lg border-0">
                                        <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal" data-bs-target="#modalNuevoPago{{ $venta->id }}">
                                            <i class="fa-solid fa-money-check-dollar me-2 text-success"></i> Registrar Pago</a>
                                        </li>
                                        <li><a class="dropdown-item py-2" href="{{ route('eventos.ventas.show', $venta->id) }}">
                                            <i class="fa-solid fa-eye me-2 text-primary"></i> Ver Venta</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Registrar Pago -->
                        <div class="modal fade" id="modalNuevoPago{{ $venta->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg rounded-4">
                                    <div class="modal-header border-0 p-4 pb-0">
                                        <div>
                                            <h5 class="modal-title fw-bold text-success">Registrar Pago / Abono</h5>
                                            <p class="text-muted small mb-0">{{ $venta->cliente->nombre }} - {{ $venta->numero_factura }}</p>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('eventos.ventas.pagos.store', $venta->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body p-4">
                                            <div class="row g-3">
                                                <div class="col-12 bg-light rounded-4 p-3 mb-2">
                                                    <div class="row text-center">
                                                        <div class="col-6 border-end">
                                                            <div class="small text-muted mb-1 text-uppercase fw-bold">Total Venta</div>
                                                            <div class="h5 fw-bold mb-0">${{ number_format($venta->total, 0, ',', '.') }}</div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="small text-muted mb-1 text-uppercase fw-bold">Saldo Pendiente</div>
                                                            <div class="h5 fw-bold mb-0 text-danger">${{ number_format($pendiente, 0, ',', '.') }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-bold text-muted">Monto a Pagar ($)</label>
                                                    <input type="number" name="monto" class="form-control rounded-3" required min="1" max="{{ $pendiente }}" step="0.01" value="{{ $pendiente }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-bold text-muted">Método</label>
                                                    <select name="metodo_pago" class="form-select rounded-3" required>
                                                        <option value="Efectivo">Efectivo</option>
                                                        <option value="Transferencia">Transferencia</option>
                                                        <option value="Tarjeta">Tarjeta</option>
                                                        <option value="Cheque">Cheque</option>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label small fw-bold text-muted">Fecha del Pago</label>
                                                    <input type="date" name="fecha" class="form-control rounded-3" value="{{ date('Y-m-d') }}" required>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label small fw-bold text-muted">Referencia / Observaciones</label>
                                                    <input type="text" name="referencia" class="form-control rounded-3" placeholder="Ej: No. de transferencia o comprobante">
                                                </div>
                                            </div>

                                            @if($venta->pagos->count() > 0)
                                            <hr class="my-4">
                                            <h6 class="fw-bold mb-3 small text-uppercase">Recibos de Pago</h6>
                                            <div class="list-group list-group-flush small">
                                                @foreach($venta->pagos->sortByDesc('fecha') as $pago)
                                                <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0">
                                                    <div>
                                                        <div class="fw-bold">{{ $pago->metodo_pago }}</div>
                                                        <div class="text-muted">{{ $pago->fecha->format('d/m/Y') }} - {{ $pago->referencia }}</div>
                                                    </div>
                                                    <div class="fw-bold text-success">
                                                        ${{ number_format($pago->monto, 0, ',', '.') }}
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer border-0 p-4 pt-0">
                                            <button type="button" class="btn btn-light rounded-3 fw-bold px-4" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-success rounded-3 fw-bold px-4 shadow-sm text-white">Registrar Abono</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fa-solid fa-coins fa-3x text-muted mb-3 opacity-25"></i>
                                <h5 class="text-muted">No se encontraron ventas para este reporte</h5>
                                <p class="text-muted small">Intente ajustando los filtros de fecha o búsqueda.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    #filtros-finance, .dropdown, .btn, .breadcrumb {
        display: none !important;
    }
    .card {
        border: 2px solid #eee !important;
        box-shadow: none !important;
    }
    .container-fluid {
        padding: 0 !important;
    }
    #totales-finance .col-xl-3 {
        width: 50% !important;
        float: left;
        margin-bottom: 20px;
    }
    .table-responsive {
        overflow: visible !important;
    }
}
</style>
@endsection
