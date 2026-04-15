@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('eventos.equipos.index') }}" class="text-decoration-none text-muted">Inventario</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Editar Equipo</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0">Editar Equipo: {{ $equipo->nombre }}</h3>
            </div>
            <a href="{{ route('eventos.equipos.index') }}" class="btn btn-light rounded-3 px-4 shadow-sm fw-bold">
                <i class="fa-solid fa-arrow-left me-2"></i> Volver al inventario
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('eventos.equipos.update', $equipo->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <!-- Imagen del Equipo -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted small text-uppercase mb-3">Imagen del Equipo</label>
                                <div class="image-upload-wrapper bg-light rounded-4 d-flex align-items-center justify-content-center position-relative overflow-hidden border-dashed" style="height: 250px; border: 2px dashed #dee2e6;">
                                    @php
                                        $imageSrc = $equipo->imagen ? (str_starts_with($equipo->imagen, 'http') ? $equipo->imagen : asset('storage/' . $equipo->imagen)) : null;
                                    @endphp
                                    <div class="text-center p-3 {{ $imageSrc ? 'd-none' : '' }}" id="preview-placeholder">
                                        <i class="fa-solid fa-cloud-arrow-up fs-1 text-muted mb-2"></i>
                                        <p class="small text-muted mb-0">Haz clic para cambiar la imagen</p>
                                    </div>
                                    <img id="image-preview" src="{{ $imageSrc }}" class="w-100 h-100 object-fit-cover {{ $imageSrc ? '' : 'd-none' }}">
                                    <input type="file" name="imagen" id="imagen" class="position-absolute w-100 h-100 opacity-0" style="cursor: pointer;" accept="image/*">
                                </div>
                                <div class="mt-2 small text-muted text-center">Deja en blanco para mantener la imagen actual</div>
                                @error('imagen') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                            </div>

                            <!-- Información General -->
                            <div class="col-md-8">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <label for="nombre" class="form-label fw-bold text-muted small text-uppercase">Nombre del Equipo</label>
                                        <input type="text" class="form-control rounded-3 @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $equipo->nombre) }}" required>
                                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="categoria_id" class="form-label fw-bold text-muted small text-uppercase">Categoría</label>
                                        <select class="form-select rounded-3 @error('categoria_id') is-invalid @enderror" id="categoria_id" name="categoria_id" required>
                                            @foreach($categorias as $cat)
                                                <option value="{{ $cat->id }}" {{ old('categoria_id', $equipo->categoria_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                                            @endforeach
                                        </select>
                                        @error('categoria_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="estado" class="form-label fw-bold text-muted small text-uppercase">Estado</label>
                                        <select class="form-select rounded-3 @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                                            <option value="Disponible" {{ old('estado', $equipo->estado) == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                                            <option value="Mantenimiento" {{ old('estado', $equipo->estado) == 'Mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
                                            <option value="Baja" {{ old('estado', $equipo->estado) == 'Baja' ? 'selected' : '' }}>Fuera de Servicio (Baja)</option>
                                        </select>
                                        @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="precio_dia" class="form-label fw-bold text-muted small text-uppercase">Precio Día</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 rounded-start-3">$</span>
                                            <input type="number" step="0.01" class="form-control border-start-0 rounded-end-3 @error('precio_dia') is-invalid @enderror" id="precio_dia" name="precio_dia" value="{{ old('precio_dia', $equipo->precio_dia) }}" required>
                                            @error('precio_dia') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="precio_hora" class="form-label fw-bold text-muted small text-uppercase">Precio Hora</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 rounded-start-3">$</span>
                                            <input type="number" step="0.01" class="form-control border-start-0 rounded-end-3 @error('precio_hora') is-invalid @enderror" id="precio_hora" name="precio_hora" value="{{ old('precio_hora', $equipo->precio_hora) }}" required>
                                            @error('precio_hora') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="cantidad_total" class="form-label fw-bold text-muted small text-uppercase">Stock Total</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="fa-solid fa-boxes-stacked text-muted"></i></span>
                                            <input type="number" class="form-control border-start-0 rounded-end-3 @error('cantidad_total') is-invalid @enderror" id="cantidad_total" name="cantidad_total" value="{{ old('cantidad_total', $equipo->cantidad_total) }}" required>
                                            @error('cantidad_total') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="descripcion" class="form-label fw-bold text-muted small text-uppercase">Descripción Detallada</label>
                                <textarea class="form-control rounded-3 @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="4">{{ old('descripcion', $equipo->descripcion) }}</textarea>
                                @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12 mt-5">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">
                                        <i class="fa-solid fa-floppy-disk me-2"></i> Actualizar Equipo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('imagen').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('image-preview');
        const placeholder = document.getElementById('preview-placeholder');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                placeholder.classList.add('d-none');
            }
            reader.readAsDataURL(file);
        }
    });
</script>

<style>
    .form-control:focus, .form-select:focus {
        border-color: #5b6df0;
        box-shadow: none;
    }
    .input-group:focus-within .input-group-text {
        border-color: #5b6df0;
    }
    .image-upload-wrapper:hover {
        border-color: #5b6df0 !important;
        background-color: #f8f9fa !important;
    }
</style>
@endsection
