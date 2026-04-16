<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura_{{ $venta->numero_factura }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 14px; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); background: white; }
        .invoice-header { border-bottom: 2px solid #0d6efd; padding-bottom: 20px; margin-bottom: 30px; }
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
        <button onclick="window.print()" class="btn btn-primary px-4 fw-bold rounded-pill">
            <i class="fa-solid fa-print"></i> Imprimir Factura
        </button>
        <button onclick="window.close()" class="btn btn-outline-secondary px-4 fw-bold rounded-pill ms-2">
            Cerrar
        </button>
    </div>

    <div class="invoice-box">
        <div class="invoice-header">
            <div class="row align-items-center">
                <div class="col-6">
                    <h2 class="text-primary fw-bold mb-0">{{ $config['nombre_app'] ?? 'Gestión Inventario' }}</h2>
                    <div class="small text-muted">
                        <p class="mb-0"><strong>{{ $config['empresa_nombre'] ?? 'Mi Empresa S.A.S' }}</strong></p>
                        <p class="mb-0">NIT: {{ $config['empresa_nit'] ?? '900.000.000-1' }}</p>
                        <p class="mb-0">{{ $config['empresa_direccion'] ?? 'Calle Falsa 123' }}</p>
                        <p class="mb-0">Tel: {{ $config['empresa_telefono'] ?? '300 000 0000' }}</p>
                        <p class="mb-0">{{ $config['empresa_email'] ?? 'contacto@empresa.com' }}</p>
                    </div>
                </div>
                <div class="col-6 text-end">
                    <h1 class="text-uppercase text-muted h4 mb-1">Factura de Venta</h1>
                    <h2 class="text-primary fw-bold mb-3">{{ $venta->numero_factura }}</h2>
                    <div class="small text-muted">
                        <p class="mb-0">Fecha: <strong>{{ $venta->fecha_venta->format('d/m/Y') }}</strong></p>
                        @if($venta->cotizacion)
                            <p class="mb-0">Ref. Cotización: <strong>{{ $venta->cotizacion->numero_cotizacion }}</strong></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-6">
                <h6 class="text-uppercase text-muted small fw-bold mb-3">Facturado a:</h6>
                <div class="fw-bold fs-5">{{ $venta->cliente->nombre }}</div>
                <div class="text-muted small">
                    <p class="mb-0">ID: {{ $venta->cliente->identificacion }}</p>
                    <p class="mb-0">{{ $venta->cliente->direccion }}</p>
                    <p class="mb-0">Tel: {{ $venta->cliente->telefono }}</p>
                    <p class="mb-0">{{ $venta->cliente->email }}</p>
                </div>
            </div>
            <div class="col-6 text-end">
                <h6 class="text-uppercase text-muted small fw-bold mb-3">Detalles del Evento:</h6>
                <div class="text-muted small">
                    @if($venta->fecha_evento_inicio)
                        <p class="mb-0">Inicio: <strong>{{ $venta->fecha_evento_inicio->format('d/m/Y H:i') }}</strong></p>
                        <p class="mb-0">Fin: <strong>{{ $venta->fecha_evento_fin->format('d/m/Y H:i') }}</strong></p>
                    @endif
                    <p class="mb-0">Tipo de Alquiler: <strong>Por {{ $venta->tipo_alquiler }}</strong></p>
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
                @foreach($venta->items as $item)
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
                        @if($venta->notas)
                            <h6 class="text-uppercase text-muted small fw-bold mb-2">Notas:</h6>
                            <p class="small text-muted">{{ $venta->notas }}</p>
                        @endif
                    </td>
                    <td class="text-end pt-4 text-muted">Subtotal:</td>
                    <td class="text-end pt-4 pe-0 fw-bold">${{ number_format($venta->subtotal) }}</td>
                </tr>
                <tr>
                    <td class="text-end text-muted">IVA (0%):</td>
                    <td class="text-end pe-0 fw-bold">$0</td>
                </tr>
                <tr>
                    <td class="text-end h5 fw-bold text-primary">Total:</td>
                    <td class="text-end pe-0 h5 fw-bold text-primary">${{ number_format($venta->total) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="mt-5 pt-5 text-center text-muted small">
            <p class="mb-1">Gracias por confiar en <strong>{{ $config['empresa_nombre'] }}</strong> para su evento.</p>
            <p class="mb-0">Esta factura es un documento oficial de venta.</p>
        </div>
    </div>

    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>
