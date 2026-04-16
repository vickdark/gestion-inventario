@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('eventos.cotizaciones.index') }}" class="btn btn-light rounded-3 shadow-sm border-0">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h3 class="fw-bold mb-0">Cotización: {{ $cotizacion->numero_cotizacion }}</h3>
            @php
                $badgeClass = 'bg-secondary';
                if($cotizacion->estado === 'pendiente') $badgeClass = 'bg-warning';
                if($cotizacion->estado === 'aprobada') $badgeClass = 'bg-success';
                if($cotizacion->estado === 'convertida') $badgeClass = 'bg-info';
                if($cotizacion->estado === 'rechazada') $badgeClass = 'bg-danger';
            @endphp
            <span class="badge {{ $badgeClass }} bg-opacity-10 text-{{ str_replace('bg-', '', $badgeClass) }} rounded-pill px-3 py-2 text-capitalize">
                {{ $cotizacion->estado }}
            </span>
        </div>
        <div class="d-flex gap-2">
            @if($cotizacion->estado === 'pendiente')
                <a href="{{ route('eventos.cotizaciones.edit', $cotizacion->id) }}" class="btn btn-light rounded-3 px-4 shadow-sm fw-bold border-0">
                    <i class="fa-solid fa-pen me-2"></i> Editar
                </a>
                <form action="{{ route('eventos.cotizaciones.convertir', $cotizacion->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success rounded-3 px-4 shadow-sm fw-bold">
                        <i class="fa-solid fa-check-to-slot me-2"></i> Convertir a Venta
                    </button>
                </form>
                <form action="{{ route('eventos.cotizaciones.rechazar', $cotizacion->id) }}" method="POST" id="form-rechazar">
                    @csrf
                    <button type="button" class="btn btn-outline-danger rounded-3 px-4 shadow-sm fw-bold" onclick="confirmarRechazo()">
                        <i class="fa-solid fa-xmark me-2"></i> Rechazar
                    </button>
                </form>
            @elseif($cotizacion->estado === 'rechazada')
                <form action="{{ route('eventos.cotizaciones.reabrir', $cotizacion->id) }}" method="POST" id="form-reabrir">
                    @csrf
                    <button type="button" class="btn btn-warning rounded-3 px-4 shadow-sm fw-bold" onclick="confirmarReabrir()">
                        <i class="fa-solid fa-arrow-rotate-left me-2"></i> Volver a Pendiente
                    </button>
                </form>
            @endif
            <a href="{{ route('eventos.cotizaciones.comprobante', $cotizacion) }}" target="_blank" class="btn btn-light rounded-3 px-4 shadow-sm fw-bold border-0">
                <i class="fa-solid fa-print me-2"></i> Imprimir
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Detalle de la Cotización -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-bold mb-1">Cliente</label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <i class="fa-solid fa-{{ $cotizacion->cliente->tipo === 'Empresa' ? 'building' : 'user' }} fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $cotizacion->cliente->nombre }}</h6>
                                    <p class="text-muted small mb-0">{{ $cotizacion->cliente->email }} • {{ $cotizacion->cliente->telefono }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small text-uppercase fw-bold mb-1">Emisión</label>
                            <div class="fw-bold">{{ $cotizacion->fecha_emision->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small text-uppercase fw-bold mb-1">Vencimiento</label>
                            <div class="fw-bold text-{{ $cotizacion->fecha_vencimiento->isPast() ? 'danger' : 'success' }}">
                                {{ $cotizacion->fecha_vencimiento->format('d/m/Y') }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-bold mb-1">Período del Evento</label>
                            <div class="fw-bold">
                                @if($cotizacion->fecha_evento_inicio)
                                    {{ $cotizacion->fecha_evento_inicio->format('d/m/Y H:i') }} - {{ $cotizacion->fecha_evento_fin->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-muted italic">No definido</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small text-uppercase fw-bold mb-1">Tipo Cobro</label>
                            <div class="fw-bold text-capitalize">Por {{ $cotizacion->tipo_alquiler }}</div>
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
                                @foreach($cotizacion->items as $item)
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

                    @if($cotizacion->notas)
                        <div class="mt-4 p-3 bg-light rounded-3">
                            <label class="text-muted small text-uppercase fw-bold mb-2 d-block">Notas / Observaciones</label>
                            <p class="mb-0 small text-muted italic">{{ $cotizacion->notas }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Resumen Financiero -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 opacity-75">Resumen Financiero</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span class="fw-bold">${{ number_format($cotizacion->subtotal) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Impuestos (0%)</span>
                        <span class="fw-bold">$0</span>
                    </div>
                    <hr class="my-3 border-white opacity-25">
                    <div class="d-flex justify-content-between">
                        <span class="h4 fw-bold mb-0">Total</span>
                        <span class="h4 fw-bold mb-0">${{ number_format($cotizacion->total) }}</span>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Historial / Auditoría</h6>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="fa-solid fa-user-pen small text-muted"></i>
                        </div>
                        <div>
                            <p class="mb-0 small fw-bold">Creado por</p>
                            <p class="mb-0 extra-small text-muted">{{ $cotizacion->creator->name ?? 'Sistema' }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="fa-solid fa-clock small text-muted"></i>
                        </div>
                        <div>
                            <p class="mb-0 small fw-bold">Fecha de Registro</p>
                            <p class="mb-0 extra-small text-muted">{{ $cotizacion->created_at->format('d/m/Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmarRechazo() {
        Swal.fire({
            title: '¿Rechazar Cotización?',
            text: "La cotización pasará a estado rechazada y no se podrá convertir a venta.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, rechazar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-rechazar').submit();
            }
        });
    }

    function confirmarReabrir() {
        Swal.fire({
            title: '¿Reabrir Cotización?',
            text: "El estado volverá a pendiente y la cotización podrá volver a editarse.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, reabrir',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-reabrir').submit();
            }
        });
    }
</script>
@endpush
