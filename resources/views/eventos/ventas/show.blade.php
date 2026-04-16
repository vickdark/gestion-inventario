@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('eventos.ventas.index') }}" class="btn btn-light rounded-3 shadow-sm border-0">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h3 class="fw-bold mb-0">Venta: {{ $venta->numero_factura }}</h3>
            @php
                $badgeClass = 'bg-secondary';
                if($venta->estado_pago === 'pendiente') $badgeClass = 'bg-warning';
                if($venta->estado_pago === 'pagado') $badgeClass = 'bg-success';
                if($venta->estado_pago === 'parcial') $badgeClass = 'bg-info';
                if($venta->estado_pago === 'anulado') $badgeClass = 'bg-danger';
            @endphp
            <span class="badge {{ $badgeClass }} bg-opacity-10 text-{{ str_replace('bg-', '', $badgeClass) }} rounded-pill px-3 py-2 text-capitalize">
                {{ $venta->estado_pago }}
            </span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('eventos.ventas.factura', $venta->id) }}" target="_blank" class="btn btn-success rounded-3 px-4 shadow-sm fw-bold">
                <i class="fa-solid fa-file-invoice me-2"></i> Ver Factura
            </a>
            <button class="btn btn-light rounded-3 px-4 shadow-sm fw-bold border-0" onclick="window.print()">
                <i class="fa-solid fa-print me-2"></i> Imprimir
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Detalle de la Venta -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-bold mb-1">Cliente</label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <i class="fa-solid fa-{{ $venta->cliente->tipo === 'Empresa' ? 'building' : 'user' }} fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $venta->cliente->nombre }}</h6>
                                    <p class="text-muted small mb-0">{{ $venta->cliente->email }} • {{ $venta->cliente->telefono }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small text-uppercase fw-bold mb-1">Fecha Venta</label>
                            <div class="fw-bold">{{ $venta->fecha_venta->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small text-uppercase fw-bold mb-1">Cotización Ref.</label>
                            <div class="fw-bold">
                                @if($venta->cotizacion)
                                    <a href="{{ route('eventos.cotizaciones.show', $venta->cotizacion_id) }}" class="text-decoration-none">
                                        {{ $venta->cotizacion->numero_cotizacion }}
                                    </a>
                                @else
                                    <span class="text-muted italic">Venta Directa</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-bold mb-1">Período del Evento</label>
                            <div class="fw-bold">
                                @if($venta->fecha_evento_inicio)
                                    {{ $venta->fecha_evento_inicio->format('d/m/Y H:i') }} - {{ $venta->fecha_evento_fin->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-muted italic">No definido</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small text-uppercase fw-bold mb-1">Tipo Cobro</label>
                            <div class="fw-bold text-capitalize">Por {{ $venta->tipo_alquiler }}</div>
                        </div>
                    </div>

                    <hr class="my-4 opacity-25">

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead>
                                <tr class="bg-light border-bottom">
                                    <th class="text-muted small text-uppercase ps-3 py-3">Item</th>
                                    <th class="text-muted small text-uppercase py-3 text-center">Cantidad</th>
                                    <th class="text-muted small text-uppercase py-3 text-end">Precio Unit.</th>
                                    <th class="text-muted small text-uppercase py-3 text-end pe-3">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($venta->items as $item)
                                    <tr class="border-bottom">
                                        <td class="ps-3 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="bg-light rounded p-1" style="width: 40px; height: 40px;">
                                                    <img src="{{ $item->itemable->imagen ?? '/img/placeholder.png' }}" class="w-100 h-100 object-fit-cover rounded">
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $item->nombre }}</div>
                                                    <div class="extra-small text-muted">{{ class_basename($item->itemable_type) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $item->cantidad }}</td>
                                        <td class="text-end">${{ number_format($item->precio_unitario) }}</td>
                                        <td class="text-end fw-bold pe-3">${{ number_format($item->subtotal) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($venta->notas)
                        <div class="mt-4 p-3 bg-light rounded-3">
                            <label class="text-muted small text-uppercase fw-bold mb-2 d-block">Notas / Observaciones</label>
                            <p class="mb-0 small text-muted italic">{{ $venta->notas }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Resumen Financiero -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 bg-success text-white mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 opacity-75">Resumen de Venta</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span class="fw-bold">${{ number_format($venta->subtotal) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Impuestos (0%)</span>
                        <span class="fw-bold">$0</span>
                    </div>
                    <hr class="my-3 border-white opacity-25">
                    <div class="d-flex justify-content-between">
                        <span class="h4 fw-bold mb-0">Total</span>
                        <span class="h4 fw-bold mb-0">${{ number_format($venta->total) }}</span>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Información de Pago</h6>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="fa-solid fa-credit-card small text-muted"></i>
                        </div>
                        <div>
                            <p class="mb-0 small fw-bold">Método de Pago</p>
                            <p class="mb-0 extra-small text-muted">{{ $venta->metodo_pago ?? 'No especificado' }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="fa-solid fa-user-check small text-muted"></i>
                        </div>
                        <div>
                            <p class="mb-0 small fw-bold">Registrado por</p>
                            <p class="mb-0 extra-small text-muted">{{ $venta->creator->name ?? 'Sistema' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
