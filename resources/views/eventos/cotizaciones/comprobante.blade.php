<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotizacion_{{ $cotizacion->numero_cotizacion }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 14px; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); background: white; }
        .invoice-header { border-bottom: 2px solid #ffc107; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { max-height: 80px; }
        .table thead th { background-color: #f8f9fa; border-bottom: 2px solid #dee2e6; color: #666; font-size: 12px; text-transform: uppercase; }
        .total-row { background-color: #f8f9fa; font-weight: bold; }
        @media print {
            body { background: white; }
            .invoice-box { border: none; box-shadow: none; width: 100%; max-width: 100%; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body class="bg-light py-5">
    <div class="no-print text-center mb-4">
        <button onclick="window.print()" class="btn btn-warning px-4 fw-bold rounded-pill text-white">
            <i class="fa-solid fa-print"></i> Imprimir Cotización
        </button>
        <button onclick="window.close()" class="btn btn-outline-secondary px-4 fw-bold rounded-pill ms-2">
            Cerrar
        </button>
    </div>

    <div class="invoice-box">
        <div class="invoice-header">
            <div class="row align-items-center">
                <div class="col-6">
                    <h2 class="text-warning fw-bold mb-0">{{ $config['app_name'] ?? 'Gestión Inventario' }}</h2>
                    <div class="small text-muted">
                        <p class="mb-0"><strong>{{ $config['company_name'] ?? 'Mi Empresa S.A.S' }}</strong></p>
                        <p class="mb-0">NIT: {{ $config['company_id'] ?? '900.000.000-1' }}</p>
                        <p class="mb-0">{{ $config['company_address'] ?? 'Calle Falsa 123' }}</p>
                        <p class="mb-0">Tel: {{ $config['company_phone'] ?? '300 000 0000' }}</p>
                        <p class="mb-0">{{ $config['company_email'] ?? 'contacto@empresa.com' }}</p>
                    </div>
                </div>
                <div class="col-6 text-end">
                    <h1 class="text-uppercase text-muted h4 mb-1">Cotización</h1>
                    <h2 class="text-warning fw-bold mb-3">{{ $cotizacion->numero_cotizacion }}</h2>
                    <div class="small text-muted">
                        <p class="mb-0">Fecha Emisión: <strong>{{ $cotizacion->fecha_emision->format('d/m/Y') }}</strong></p>
                        <p class="mb-0">Válida hasta: <strong>{{ $cotizacion->fecha_vencimiento->format('d/m/Y') }}</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-6">
                <h6 class="text-uppercase text-muted small fw-bold mb-3">Cliente:</h6>
                <div class="fw-bold fs-5">{{ $cotizacion->cliente->nombre }}</div>
                <div class="text-muted small">
                    <p class="mb-0">ID: {{ $cotizacion->cliente->identificacion }}</p>
                    <p class="mb-0">{{ $cotizacion->cliente->direccion }}</p>
                    <p class="mb-0">Tel: {{ $cotizacion->cliente->telefono }}</p>
                    <p class="mb-0">{{ $cotizacion->cliente->email }}</p>
                </div>
            </div>
            <div class="col-6 text-end">
                <h6 class="text-uppercase text-muted small fw-bold mb-3">Detalles del Evento (Tentativo):</h6>
                <div class="text-muted small">
                    @if($cotizacion->fecha_evento_inicio)
                        <p class="mb-0">Inicio: <strong>{{ $cotizacion->fecha_evento_inicio->format('d/m/Y H:i') }}</strong></p>
                        <p class="mb-0">Fin: <strong>{{ $cotizacion->fecha_evento_fin->format('d/m/Y H:i') }}</strong></p>
                    @endif
                    <p class="mb-0">Tipo de Alquiler: <strong>Por {{ $cotizacion->tipo_alquiler }}</strong></p>
                </div>
            </div>
        </div>

        <table class="table table-borderless mb-4">
            <thead>
                <tr>
                    <th class="ps-0">Descripción del Item</th>
                    <th class="text-center">Cant.</th>
                    <th class="text-end">Precio Unit.</th>
                    <th class="text-end pe-0">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cotizacion->items as $item)
                    <tr class="border-bottom">
                        <td class="ps-0 py-3">
                            <div class="fw-bold text-dark">{{ $item->nombre }}</div>
                            <div class="extra-small text-muted">{{ class_basename($item->itemable_type) }}</div>
                        </td>
                        <td class="text-center py-3">{{ $item->cantidad }}</td>
                        <td class="text-end py-3">${{ number_format($item->precio_unitario) }}</td>
                        <td class="text-end py-3 pe-0 fw-bold text-dark">${{ number_format($item->subtotal) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" rowspan="3" class="ps-0 pt-4 align-top">
                        @if($cotizacion->notas)
                            <h6 class="text-uppercase text-muted small fw-bold mb-2">Notas / Condiciones:</h6>
                            <p class="small text-muted italic">{{ $cotizacion->notas }}</p>
                        @endif
                        <div class="mt-4 extra-small text-muted">
                            <p class="mb-0">* Precios sujetos a cambios sin previo aviso.</p>
                            <p class="mb-0">* La disponibilidad de los equipos solo se garantiza con el pago de la reserva.</p>
                        </div>
                    </td>
                    <td class="text-end pt-4 text-muted">Subtotal:</td>
                    <td class="text-end pt-4 pe-0 fw-bold">${{ number_format($cotizacion->subtotal) }}</td>
                </tr>
                <tr>
                    <td class="text-end text-muted">Impuestos (0%):</td>
                    <td class="text-end pe-0 fw-bold">$0</td>
                </tr>
                <tr>
                    <td class="text-end h5 fw-bold text-warning">Total Estimado:</td>
                    <td class="text-end pe-0 h5 fw-bold text-warning">${{ number_format($cotizacion->total) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="mt-5 pt-5 text-center text-muted small border-top">
            <p class="mb-1">{{ $config['invoice_footer'] }}</p>
            <p class="mb-0">Generado por <strong>{{ $config['company_name'] }}</strong></p>
        </div>
    </div>

    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>
