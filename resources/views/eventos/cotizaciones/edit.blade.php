@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <form action="{{ route('eventos.cotizaciones.update', $cotizacion->id) }}" method="POST" id="quote-form">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Columna Izquierda: Datos Generales -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-0 py-3 d-flex align-items-center gap-3">
                        <a href="{{ route('eventos.cotizaciones.index') }}" class="btn btn-light rounded-circle p-2 border-0 shadow-sm">
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                        <h5 class="fw-bold mb-0">Editar Cotización: {{ $cotizacion->numero_cotizacion }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Cliente</label>
                                <select name="cliente_id" id="cliente_id" class="form-select rounded-3 @error('cliente_id') is-invalid @enderror" required>
                                    <option value="">Seleccione un cliente...</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ (old('cliente_id', $cotizacion->cliente_id) == $cliente->id) ? 'selected' : '' }}>
                                            {{ $cliente->nombre }} ({{ $cliente->tipo }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('cliente_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-bold">Tipo de Cobro</label>
                                <select name="tipo_alquiler" id="tipo_alquiler" class="form-select rounded-3" required>
                                    <option value="dia" {{ old('tipo_alquiler', $cotizacion->tipo_alquiler) == 'dia' ? 'selected' : '' }}>Por Día</option>
                                    <option value="hora" {{ old('tipo_alquiler', $cotizacion->tipo_alquiler) == 'hora' ? 'selected' : '' }}>Por Hora</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-bold">Fecha Emisión</label>
                                <input type="date" name="fecha_emision" class="form-control rounded-3" value="{{ old('fecha_emision', $cotizacion->fecha_emision->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-bold">Fecha Vencimiento</label>
                                <input type="date" name="fecha_vencimiento" class="form-control rounded-3" value="{{ old('fecha_vencimiento', $cotizacion->fecha_vencimiento->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small fw-bold">Inicio del Evento</label>
                                <input type="datetime-local" name="fecha_evento_inicio" class="form-control rounded-3" value="{{ old('fecha_evento_inicio', $cotizacion->fecha_evento_inicio ? $cotizacion->fecha_evento_inicio->format('Y-m-d\TH:i') : '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small fw-bold">Fin del Evento</label>
                                <input type="datetime-local" name="fecha_evento_fin" class="form-control rounded-3" value="{{ old('fecha_evento_fin', $cotizacion->fecha_evento_fin ? $cotizacion->fecha_evento_fin->format('Y-m-d\TH:i') : '') }}">
                            </div>
                        </div>

                        <hr class="my-4 text-muted opacity-25">

                        <!-- Buscador de Items -->
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Agregar Equipos o Paquetes</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 rounded-start-3"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                <input type="text" id="item-search" class="form-control border-start-0 rounded-end-3" placeholder="Escriba el nombre del equipo o combo...">
                            </div>
                            <div id="search-results" class="list-group mt-2 shadow-sm d-none" style="position: absolute; z-index: 1000; width: 95%;"></div>
                        </div>

                        <!-- Tabla de Items -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="items-table">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-muted small text-uppercase border-0">Item</th>
                                        <th class="text-muted small text-uppercase border-0 text-center" style="width: 120px;">Cant.</th>
                                        <th class="text-muted small text-uppercase border-0 text-end" style="width: 150px;">Precio Unit.</th>
                                        <th class="text-muted small text-uppercase border-0 text-end" style="width: 150px;">Subtotal</th>
                                        <th class="text-muted small text-uppercase border-0 text-end" style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="items-body">
                                    @foreach($cotizacion->items as $index => $item)
                                        @php $itemKey = ($item->itemable_type === 'App\Models\Equipo' ? 'equipo' : 'paquete') . '-' . $item->itemable_id; @endphp
                                        <tr id="row-{{ $itemKey }}">
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->itemable_id }}">
                                                    <input type="hidden" name="items[{{ $index }}][type]" value="{{ $item->itemable_type === 'App\Models\Equipo' ? 'equipo' : 'paquete' }}">
                                                    <input type="hidden" name="items[{{ $index }}][nombre]" value="{{ $item->nombre }}">
                                                    <div class="bg-light rounded p-1" style="width: 32px; height: 32px;">
                                                        <img src="{{ $item->itemable->imagen ?? '/img/placeholder.png' }}" class="w-100 h-100 object-fit-cover rounded">
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold small">{{ $item->nombre }}</div>
                                                        <div class="extra-small text-muted">{{ $item->itemable_type === 'App\Models\Equipo' ? 'Equipo' : 'Combo' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" name="items[{{ $index }}][cantidad]" value="{{ $item->cantidad }}" min="1" 
                                                    class="form-control form-control-sm rounded-3 text-center qty-input" 
                                                    onchange="updateRow('{{ $itemKey }}')">
                                            </td>
                                            <td>
                                                <input type="number" name="items[{{ $index }}][precio]" value="{{ $item->precio_unitario }}" step="0.01" 
                                                    class="form-control form-control-sm rounded-3 text-end price-input" 
                                                    onchange="updateRow('{{ $itemKey }}')">
                                            </td>
                                            <td class="text-end fw-bold row-subtotal">${{ number_format($item->subtotal) }}</td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-link text-danger p-0" onclick="removeItem('{{ $itemKey }}')">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if($cotizacion->items->isEmpty())
                                        <tr id="empty-row">
                                            <td colspan="5" class="text-center py-4 text-muted">No hay items agregados</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <label class="form-label text-muted small fw-bold">Notas / Observaciones</label>
                        <textarea name="notas" class="form-control rounded-3" rows="3" placeholder="Términos de pago, condiciones especiales...">{{ old('notas', $cotizacion->notas) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Resumen -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 2rem;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Resumen</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold" id="resumen-subtotal">${{ number_format($cotizacion->subtotal) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Impuestos (0%)</span>
                            <span class="fw-bold" id="resumen-tax">$0</span>
                        </div>
                        <hr class="my-3">
                        <div class="d-flex justify-content-between mb-4">
                            <span class="h5 fw-bold mb-0">Total</span>
                            <span class="h5 fw-bold mb-0 text-primary" id="resumen-total">${{ number_format($cotizacion->total) }}</span>
                        </div>

                        <button type="submit" class="btn btn-info w-100 rounded-3 py-3 fw-bold shadow-sm text-white">
                            <i class="fa-solid fa-save me-2"></i> Actualizar Cotización
                        </button>
                        <a href="{{ route('eventos.cotizaciones.index') }}" class="btn btn-light w-100 rounded-3 py-3 fw-bold mt-2 border-0 shadow-sm">
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if(document.getElementById('cliente_id')) {
            new TomSelect('#cliente_id', {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
        }

        const itemsBody = document.getElementById('items-body');
        const emptyRow = document.getElementById('empty-row');
        const searchInput = document.getElementById('item-search');
        const resultsDiv = document.getElementById('search-results');
        const tipoAlquiler = document.getElementById('tipo_alquiler');
        
        const equipos = @json($equipos);
        const paquetes = @json($paquetes);
        
        // Cargar items existentes
        let addedItems = new Set();
        @foreach($cotizacion->items as $item)
            addedItems.add('{{ ($item->itemable_type === "App\Models\Equipo" ? "equipo" : "paquete") . "-" . $item->itemable_id }}');
        @endforeach

        const allItems = [
            ...equipos.map(e => ({...e, type: 'equipo'})),
            ...paquetes.map(p => ({...p, type: 'paquete'}))
        ];

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            if (query.length < 2) {
                resultsDiv.classList.add('d-none');
                return;
            }

            const filtered = allItems.filter(item => 
                item.nombre.toLowerCase().includes(query)
            );

            if (filtered.length > 0) {
                resultsDiv.innerHTML = filtered.map(item => `
                    <button type="button" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-2 border-0 border-bottom" 
                        onclick="addItem(${JSON.stringify(item).replace(/"/g, '&quot;')})">
                        <div class="bg-light rounded p-1" style="width: 40px; height: 40px;">
                            <img src="${item.imagen || '/img/placeholder.png'}" class="w-100 h-100 object-fit-cover rounded">
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold small">${item.nombre}</div>
                            <div class="extra-small text-muted">${item.type === 'equipo' ? 'Equipo' : 'Combo'} • ${item.categoria ? item.categoria.nombre : ''}</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-primary small">$${new Intl.NumberFormat().format(tipoAlquiler.value === 'dia' ? item.precio_dia : item.precio_hora)}</div>
                        </div>
                    </button>
                `).join('');
                resultsDiv.classList.remove('d-none');
            } else {
                resultsDiv.innerHTML = '<div class="list-group-item text-muted small">No se encontraron resultados</div>';
                resultsDiv.classList.remove('d-none');
            }
        });

        window.addItem = function(item) {
            const itemKey = `${item.type}-${item.id}`;
            if (addedItems.has(itemKey)) {
                Swal.fire('Atención', 'Este item ya ha sido agregado', 'info');
                return;
            }

            if (emptyRow) emptyRow.remove();

            const precio = tipoAlquiler.value === 'dia' ? item.precio_dia : item.precio_hora;
            const index = addedItems.size;
            
            const row = document.createElement('tr');
            row.id = `row-${itemKey}`;
            row.innerHTML = `
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <input type="hidden" name="items[${index}][id]" value="${item.id}">
                        <input type="hidden" name="items[${index}][type]" value="${item.type}">
                        <input type="hidden" name="items[${index}][nombre]" value="${item.nombre}">
                        <div class="bg-light rounded p-1" style="width: 32px; height: 32px;">
                            <img src="${item.imagen || '/img/placeholder.png'}" class="w-100 h-100 object-fit-cover rounded">
                        </div>
                        <div>
                            <div class="fw-bold small">${item.nombre}</div>
                            <div class="extra-small text-muted">${item.type === 'equipo' ? 'Equipo' : 'Combo'}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <input type="number" name="items[${index}][cantidad]" value="1" min="1" 
                        class="form-control form-control-sm rounded-3 text-center qty-input" 
                        onchange="updateRow('${itemKey}')">
                </td>
                <td>
                    <input type="number" name="items[${index}][precio]" value="${precio}" step="0.01" 
                        class="form-control form-control-sm rounded-3 text-end price-input" 
                        onchange="updateRow('${itemKey}')">
                </td>
                <td class="text-end fw-bold row-subtotal">$${new Intl.NumberFormat().format(precio)}</td>
                <td class="text-end">
                    <button type="button" class="btn btn-link text-danger p-0" onclick="removeItem('${itemKey}')">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </td>
            `;
            
            itemsBody.appendChild(row);
            addedItems.add(itemKey);
            
            searchInput.value = '';
            resultsDiv.classList.add('d-none');
            updateTotals();
        };

        window.removeItem = function(itemKey) {
            document.getElementById(`row-${itemKey}`).remove();
            addedItems.delete(itemKey);
            if (addedItems.size === 0) {
                if (!emptyRow) {
                    const tr = document.createElement('tr');
                    tr.id = 'empty-row';
                    tr.innerHTML = '<td colspan="5" class="text-center py-4 text-muted">No hay items agregados</td>';
                    itemsBody.appendChild(tr);
                } else {
                    itemsBody.appendChild(emptyRow);
                }
            }
            updateTotals();
        };

        function calcularDuracion() {
            const f_inicio = document.querySelector('input[name="fecha_evento_inicio"]').value;
            const f_fin = document.querySelector('input[name="fecha_evento_fin"]').value;
            if (!f_inicio || !f_fin) return 1;

            const inicio = new Date(f_inicio);
            const fin = new Date(f_fin);
            const diffMs = fin - inicio;
            
            if (diffMs <= 0) return 1;

            if (tipoAlquiler.value === 'dia') {
                return Math.ceil(diffMs / (1000 * 60 * 60 * 24)) || 1;
            } else {
                return Math.ceil(diffMs / (1000 * 60 * 60)) || 1;
            }
        }

        window.updateRow = function(itemKey) {
            const row = document.getElementById(`row-${itemKey}`);
            const qty = row.querySelector('.qty-input').value;
            const price = row.querySelector('.price-input').value;
            const duracion = calcularDuracion();
            const subtotal = qty * price * duracion;
            row.querySelector('.row-subtotal').textContent = `$${new Intl.NumberFormat().format(subtotal)}`;
            updateTotals();
        };

        window.updateTotals = function() {
            let subtotal = 0;
            document.querySelectorAll('.row-subtotal').forEach(el => {
                subtotal += parseFloat(el.textContent.replace('$', '').replace(/,/g, ''));
            });
            
            document.getElementById('resumen-subtotal').textContent = `$${new Intl.NumberFormat().format(subtotal)}`;
            document.getElementById('resumen-total').textContent = `$${new Intl.NumberFormat().format(subtotal)}`;
        };

        function reevaluarPreciosYTotales() {
            document.querySelectorAll('#items-body tr:not(#empty-row)').forEach(row => {
                const itemType = row.querySelector('input[name$="[type]"]').value;
                const itemId = row.querySelector('input[name$="[id]"]').value;
                
                // En edit.blade.php, allItems puede faltar items guardados anteriormente y no en 'equipos' (aunque carga todos de BD), 
                // pero la búsqueda es similar. En todo caso recalcula todos.
                const item = allItems.find(i => i.id == itemId && (i.type == itemType || i.type == itemType.toLowerCase()));
                if (item) {
                    const newPrice = tipoAlquiler.value === 'dia' ? item.precio_dia : item.precio_hora;
                    if(row.querySelector('.price-input')) {
                        row.querySelector('.price-input').value = newPrice;
                    }
                }
                updateRow(`${itemType.toLowerCase().includes('equipo') ? 'equipo' : 'paquete'}-${itemId}`);
            });
        }

        tipoAlquiler.addEventListener('change', reevaluarPreciosYTotales);
        document.querySelector('input[name="fecha_evento_inicio"]').addEventListener('change', reevaluarPreciosYTotales);
        document.querySelector('input[name="fecha_evento_fin"]').addEventListener('change', reevaluarPreciosYTotales);

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
                resultsDiv.classList.add('d-none');
            }
        });
    });
</script>
@endpush
