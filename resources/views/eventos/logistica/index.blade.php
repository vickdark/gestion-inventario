@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <h3 class="fw-bold mb-0">Control de Logística</h3>
        <a href="{{ route('eventos.logistica.checklist') }}" class="btn btn-outline-primary rounded-3 px-4 shadow-sm fw-bold">
            <i class="fa-solid fa-calendar-check me-2"></i> Ver Checklist General
        </a>
    </div>
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('eventos.logistica.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="small fw-bold text-muted mb-2"><i class="fa-solid fa-search me-1"></i> Buscar</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-3">
                            <i class="fa-solid fa-magnifying-glass text-muted small"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 rounded-end-3" placeholder="Cliente o factura..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <label class="small fw-bold text-muted mb-2"><i class="fa-solid fa-filter me-1"></i> Estado</label>
                    <select name="estado" class="form-select rounded-3" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="en_montaje" {{ request('estado') == 'en_montaje' ? 'selected' : '' }}>En Montaje</option>
                        <option value="montado" {{ request('estado') == 'montado' ? 'selected' : '' }}>Montado</option>
                        <option value="recogiendo" {{ request('estado') == 'recogiendo' ? 'selected' : '' }}>Recogiendo</option>
                        <option value="finalizado" {{ request('estado') == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="small fw-bold text-muted mb-2"><i class="fa-solid fa-calendar-alt me-1"></i> Desde</label>
                    <input type="date" name="fecha_inicio" class="form-control rounded-3" value="{{ request('fecha_inicio') }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="small fw-bold text-muted mb-2"><i class="fa-solid fa-calendar-check me-1"></i> Hasta</label>
                    <input type="date" name="fecha_fin" class="form-control rounded-3" value="{{ request('fecha_fin') }}">
                </div>
                <div class="col-12 col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-3 w-100 fw-bold shadow-sm">
                            Filtrar
                        </button>
                        @if(request()->anyFilled(['search', 'estado', 'fecha_inicio', 'fecha_fin']))
                            <a href="{{ route('eventos.logistica.index') }}" class="btn btn-light rounded-3 px-3 fw-bold border">
                                <i class="fa-solid fa-rotate-left"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row g-4">
        @forelse($ventas as $venta)
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 {{ 
                match($venta->estado_logistica) {
                    'pendiente' => 'border-secondary',
                    'en_montaje' => 'border-info',
                    'montado' => 'border-success',
                    'recogiendo' => 'border-warning',
                    'finalizado' => 'border-primary',
                    default => 'border-secondary'
                }
            }}">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge {{ 
                                match($venta->estado_logistica) {
                                    'pendiente' => 'bg-secondary',
                                    'en_montaje' => 'bg-info',
                                    'montado' => 'bg-success',
                                    'recogiendo' => 'bg-warning',
                                    'finalizado' => 'bg-primary',
                                    default => 'bg-secondary'
                                }
                            }} bg-opacity-10 {{ 
                                match($venta->estado_logistica) {
                                    'pendiente' => 'text-secondary',
                                    'en_montaje' => 'text-info',
                                    'montado' => 'text-success',
                                    'recogiendo' => 'text-warning',
                                    'finalizado' => 'text-primary',
                                    default => 'text-secondary'
                                }
                            }} rounded-pill px-3 py-1 mb-2">
                                {{ ucfirst(str_replace('_', ' ', $venta->estado_logistica)) }}
                            </span>
                            <h5 class="fw-bold text-dark mb-0">{{ $venta->cliente->nombre }} - {{ $venta->direccion_evento ?? 'Sin dirección' }}</h5>
                            <p class="text-muted small mb-0">
                                <i class="fa-solid fa-clock me-1"></i> 
                                Inicio: {{ $venta->fecha_evento_inicio ? $venta->fecha_evento_inicio->format('d/m/Y h:i A') : 'N/A' }} 
                                | Fin: {{ $venta->fecha_evento_fin ? $venta->fecha_evento_fin->format('d/m/Y h:i A') : 'N/A' }}
                            </p>
                        </div>
                        <div class="text-end">
                            <div class="small text-muted">Vehículo</div>
                            <div class="fw-bold small text-dark">{{ $venta->vehiculo ?? 'No asignado' }}</div>
                        </div>
                    </div>
                    
                    <div class="bg-light rounded-4 p-3 mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small fw-bold text-muted">Progreso Logística</span>
                            <span class="small fw-bold text-primary">{{ $venta->progreso_logistica }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $venta->progreso_logistica }}%"></div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <div class="flex-grow-1">
                            <div class="small text-muted mb-1">Personal Asignado</div>
                            <div class="small fw-bold text-dark">
                                {{ $venta->personal_asignado ?? 'Sin personal asignado' }}
                            </div>
                        </div>
                        <div class="text-end me-3">
                            <div class="small text-muted mb-1">Gastos Op.</div>
                            <div class="small fw-bold text-danger">
                                ${{ number_format($venta->gastos->sum('monto'), 0, ',', '.') }}
                            </div>
                        </div>
                        <button class="btn btn-outline-danger rounded-3 fw-bold px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalGastos{{ $venta->id }}">
                            <i class="fa-solid fa-receipt me-1"></i> Gastos
                        </button>
                        <button class="btn btn-primary rounded-3 fw-bold px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalLogistica{{ $venta->id }}">
                            Gestionar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Logística para cada Venta -->
        <div class="modal fade" id="modalLogistica{{ $venta->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header border-0 p-4 pb-0">
                        <div>
                            <h5 class="modal-title fw-bold">Gestionar Logística</h5>
                            <p class="text-muted small mb-0">{{ $venta->cliente->nombre }} - {{ $venta->numero_factura }}</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('eventos.ventas.logistica.update', $venta->id) }}" method="POST">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">Dirección del Evento</label>
                                    <input type="text" name="direccion_evento" class="form-control rounded-3" value="{{ $venta->direccion_evento }}" placeholder="Ej: Hotel Plaza, Calle 123">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">Link Ubicación (Google Maps)</label>
                                    <input type="url" name="ubicacion_link" class="form-control rounded-3" value="{{ $venta->ubicacion_link }}" placeholder="https://maps.google.com/...">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label small fw-bold text-muted">Vehículo</label>
                                    <input type="text" name="vehiculo" class="form-control rounded-3" value="{{ $venta->vehiculo }}" placeholder="Placas / Tipo">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">Estado</label>
                                    <select name="estado_logistica" class="form-select rounded-3">
                                        <option value="pendiente" {{ $venta->estado_logistica == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="en_montaje" {{ $venta->estado_logistica == 'en_montaje' ? 'selected' : '' }}>En Montaje</option>
                                        <option value="montado" {{ $venta->estado_logistica == 'montado' ? 'selected' : '' }}>Montado</option>
                                        <option value="recogiendo" {{ $venta->estado_logistica == 'recogiendo' ? 'selected' : '' }}>Recogiendo</option>
                                        <option value="finalizado" {{ $venta->estado_logistica == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">Personal Asignado</label>
                                    <textarea name="personal_asignado" class="form-control rounded-3" rows="2" placeholder="Nombres del personal">{{ $venta->personal_asignado }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">Notas de Logística</label>
                                    <textarea name="notas_logistica" class="form-control rounded-3" rows="2">{{ $venta->notas_logistica }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0">
                            <button type="button" class="btn btn-light rounded-3 fw-bold px-4" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary rounded-3 fw-bold px-4 shadow-sm">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Gastos -->
        <div class="modal fade" id="modalGastos{{ $venta->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header border-0 p-4 pb-0">
                        <div>
                            <h5 class="modal-title fw-bold">Registrar Gasto de Operación</h5>
                            <p class="text-muted small mb-0">{{ $venta->cliente->nombre }} - {{ $venta->numero_factura }}</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('eventos.ventas.gastos.store', $venta->id) }}" method="POST">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="row g-3">
                                <div class="col-12 text-center mb-3">
                                    <div class="display-6 fw-bold text-danger mb-1">
                                        ${{ number_format($venta->gastos->sum('monto'), 0, ',', '.') }}
                                    </div>
                                    <div class="small text-muted text-uppercase fw-bold">Total acumulado en gastos</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Tipo de Gasto</label>
                                    <select name="tipo_gasto" class="form-select rounded-3" required>
                                        <option value="Combustible">Combustible</option>
                                        <option value="Peajes">Peajes</option>
                                        <option value="Alimentación">Alimentación</option>
                                        <option value="Personal Extra">Personal Extra</option>
                                        <option value="Transporte">Transporte</option>
                                        <option value="Otros">Otros</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Monto ($)</label>
                                    <input type="number" name="monto" class="form-control rounded-3" required min="0" placeholder="0.00">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">Fecha del Gasto</label>
                                    <input type="date" name="fecha" class="form-control rounded-3" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">Descripción / Notas</label>
                                    <textarea name="descripcion" class="form-control rounded-3" rows="2" placeholder="Ej: Pago de peajes ruta norte"></textarea>
                                </div>
                            </div>

                            @if($venta->gastos->count() > 0)
                            <hr class="my-4">
                            <h6 class="fw-bold mb-3 small text-uppercase">Últimos Gastos</h6>
                            <div class="list-group list-group-flush small">
                                @foreach($venta->gastos->sortByDesc('created_at')->take(5) as $gasto)
                                <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <div class="fw-bold">{{ $gasto->tipo_gasto }}</div>
                                        <div class="text-muted">{{ $gasto->fecha->format('d/m/Y') }}</div>
                                    </div>
                                    <div class="fw-bold text-danger">
                                        ${{ number_format($gasto->monto, 0, ',', '.') }}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0">
                            <button type="button" class="btn btn-light rounded-3 fw-bold px-4" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-danger rounded-3 fw-bold px-4 shadow-sm">Registrar Gasto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                <i class="fa-solid fa-truck-loading fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay eventos registrados para logística</h5>
                <p class="text-muted small">Las ventas con fecha de evento aparecerán aquí.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
