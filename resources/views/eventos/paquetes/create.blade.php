@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('eventos.paquetes.index') }}" class="text-decoration-none text-muted">Paquetes</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Nuevo Paquete</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0">Crear Nuevo Paquete</h3>
            </div>
            <a href="{{ route('eventos.paquetes.index') }}" class="btn btn-light rounded-3 px-4 shadow-sm fw-bold border">
                <i class="fa-solid fa-arrow-left me-2"></i> Volver
            </a>
        </div>
    </div>

    <form action="{{ route('eventos.paquetes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <!-- Información Básica -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-4">Detalles del Paquete</h5>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Imagen de Portada</label>
                        <div class="image-upload-wrapper bg-light rounded-4 d-flex align-items-center justify-content-center position-relative overflow-hidden border-dashed" style="height: 200px; border: 2px dashed #dee2e6;">
                            <div class="text-center p-3" id="preview-placeholder">
                                <i class="fa-solid fa-cloud-arrow-up fs-1 text-muted mb-2"></i>
                                <p class="small text-muted mb-0">Subir imagen</p>
                            </div>
                            <img id="image-preview" class="w-100 h-100 object-fit-cover d-none">
                            <input type="file" name="imagen" id="imagen" class="position-absolute w-100 h-100 opacity-0" style="cursor: pointer;" accept="image/*">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold text-muted small text-uppercase">Nombre del Paquete</label>
                        <input type="text" class="form-control rounded-3 @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Ej: Combo Fiesta VIP" required>
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="precio_dia" class="form-label fw-bold text-muted small text-uppercase">Precio Sugerido (Día)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3">$</span>
                            <input type="number" step="0.01" class="form-control border-start-0 rounded-end-3 @error('precio_dia') is-invalid @enderror" id="precio_dia" name="precio_dia" value="{{ old('precio_dia', 0) }}" required>
                        </div>
                        <div class="form-text small text-primary" id="price-day-helper">Sugerencia: $0</div>
                    </div>

                    <div class="mb-3">
                        <label for="precio_hora" class="form-label fw-bold text-muted small text-uppercase">Precio Sugerido (Hora)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3">$</span>
                            <input type="number" step="0.01" class="form-control border-start-0 rounded-end-3 @error('precio_hora') is-invalid @enderror" id="precio_hora" name="precio_hora" value="{{ old('precio_hora', 0) }}" required>
                        </div>
                        <div class="form-text small text-primary" id="price-hour-helper">Sugerencia: $0</div>
                    </div>

                    <div class="mb-0">
                        <label for="descripcion" class="form-label fw-bold text-muted small text-uppercase">Descripción</label>
                        <textarea class="form-control rounded-3" id="descripcion" name="descripcion" rows="4" placeholder="¿Qué incluye este paquete?">{{ old('descripcion') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Selección de Equipos -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-4">Equipos Incluidos</h5>
                    
                    <div class="mb-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" id="search-equipos" class="form-control border-start-0 rounded-end-3" placeholder="Buscar equipo para agregar...">
                        </div>
                    </div>

                    <div class="table-responsive mb-4" style="max-height: 400px;">
                        <table class="table table-hover align-middle" id="equipos-table">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th style="width: 50px;"></th>
                                    <th>Equipo</th>
                                    <th class="text-center" style="width: 150px;">Cantidad</th>
                                    <th class="text-end" style="width: 150px;">Día Ref.</th>
                                    <th class="text-end" style="width: 150px;">Hora Ref.</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($equipos as $equipo)
                                <tr class="equipo-row" data-id="{{ $equipo->id }}" data-nombre="{{ strtolower($equipo->nombre) }}" data-precio-dia="{{ $equipo->precio_dia }}" data-precio-hora="{{ $equipo->precio_hora }}">
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input equipo-checkbox" type="checkbox" name="equipos_selected[]" value="{{ $equipo->id }}" id="chk_{{ $equipo->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <label class="form-check-label d-block fw-semibold" for="chk_{{ $equipo->id }}">
                                            {{ $equipo->nombre }}
                                            <span class="d-block small text-muted fw-normal">{{ $equipo->categoria->nombre }}</span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <button class="btn btn-outline-secondary btn-qty" type="button" data-action="minus">-</button>
                                            <input type="number" name="equipos[{{ $loop->index }}][cantidad]" class="form-control text-center input-qty" value="1" min="1" disabled>
                                            <input type="hidden" name="equipos[{{ $loop->index }}][id]" value="{{ $equipo->id }}" disabled class="input-id">
                                            <button class="btn btn-outline-secondary btn-qty" type="button" data-action="plus">+</button>
                                        </div>
                                    </td>
                                    <td class="text-end fw-bold text-muted">
                                        ${{ number_format($equipo->precio_dia) }}
                                    </td>
                                    <td class="text-end fw-bold text-muted">
                                        ${{ number_format($equipo->precio_hora) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Crear Paquete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-equipos');
        const rows = document.querySelectorAll('.equipo-row');
        const checkboxes = document.querySelectorAll('.equipo-checkbox');
        const priceDayHelper = document.getElementById('price-day-helper');
        const priceHourHelper = document.getElementById('price-hour-helper');
        const precioDiaInput = document.getElementById('precio_dia');
        const precioHoraInput = document.getElementById('precio_hora');

        // Buscador
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            rows.forEach(row => {
                const nombre = row.dataset.nombre;
                row.style.display = nombre.includes(query) ? '' : 'none';
            });
        });

        // Habilitar/Deshabilitar inputs según checkbox
        checkboxes.forEach(chk => {
            chk.addEventListener('change', function() {
                const row = this.closest('.equipo-row');
                const inputs = row.querySelectorAll('.input-qty, .input-id');
                inputs.forEach(input => input.disabled = !this.checked);
                calculateSuggestedPrice();
            });
        });

        // Botones de cantidad
        document.querySelectorAll('.btn-qty').forEach(btn => {
            btn.addEventListener('click', function() {
                const action = this.dataset.action;
                const input = this.parentElement.querySelector('.input-qty');
                let val = parseInt(input.value);
                if (action === 'plus') val++;
                else if (action === 'minus' && val > 1) val--;
                input.value = val;
                calculateSuggestedPrice();
            });
        });

        function calculateSuggestedPrice() {
            let totalDay = 0;
            let totalHour = 0;
            checkboxes.forEach(chk => {
                if (chk.checked) {
                    const row = chk.closest('.equipo-row');
                    const qty = parseInt(row.querySelector('.input-qty').value);
                    const priceDay = parseFloat(row.dataset.precioDia);
                    const priceHour = parseFloat(row.dataset.precioHora);
                    totalDay += (qty * priceDay);
                    totalHour += (qty * priceHour);
                }
            });
            priceDayHelper.innerText = `Sugerencia: $${new Intl.NumberFormat().format(totalDay)}`;
            priceHourHelper.innerText = `Sugerencia: $${new Intl.NumberFormat().format(totalHour)}`;
            
            if (precioDiaInput.value == 0) precioDiaInput.value = totalDay;
            if (precioHoraInput.value == 0) precioHoraInput.value = totalHour;
        }

        // Preview imagen
        document.getElementById('imagen').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('preview-placeholder');
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    placeholder.classList.add('d-none');
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>

<style>
    .border-dashed { border: 2px dashed #dee2e6; }
    .image-upload-wrapper:hover { border-color: #5b6df0 !important; background-color: #f8f9fa !important; }
</style>
@endsection
