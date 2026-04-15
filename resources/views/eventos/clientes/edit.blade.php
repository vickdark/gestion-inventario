@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('eventos.clientes.index') }}" class="text-decoration-none text-muted">Clientes</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Editar Cliente</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0">Editar Cliente: {{ $cliente->nombre }}</h3>
            </div>
            <a href="{{ route('eventos.clientes.index') }}" class="btn btn-light rounded-3 px-4 shadow-sm fw-bold">
                <i class="fa-solid fa-arrow-left me-2"></i> Volver al listado
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('eventos.clientes.update', $cliente->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <!-- Tipo de Cliente -->
                            <div class="col-12">
                                <label class="form-label fw-bold text-muted small text-uppercase mb-3">Tipo de Cliente</label>
                                <div class="d-flex gap-4">
                                    <div class="form-check custom-radio">
                                        <input class="form-check-input" type="radio" name="tipo" id="tipo_persona" value="Persona" {{ $cliente->tipo === 'Persona' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="tipo_persona">
                                            Persona Natural
                                        </label>
                                    </div>
                                    <div class="form-check custom-radio">
                                        <input class="form-check-input" type="radio" name="tipo" id="tipo_empresa" value="Empresa" {{ $cliente->tipo === 'Empresa' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="tipo_empresa">
                                            Empresa / Jurídico
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Información Básica -->
                            <div class="col-md-8">
                                <label for="nombre" class="form-label fw-bold text-muted small text-uppercase">Nombre Completo o Razón Social</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="fa-solid fa-user text-muted"></i></span>
                                    <input type="text" class="form-control border-start-0 rounded-end-3 @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" required>
                                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="documento" class="form-label fw-bold text-muted small text-uppercase">NIT / CC / RUC</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="fa-solid fa-id-card text-muted"></i></span>
                                    <input type="text" class="form-control border-start-0 rounded-end-3 @error('documento') is-invalid @enderror" id="documento" name="documento" value="{{ old('documento', $cliente->documento) }}">
                                    @error('documento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Contacto -->
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-bold text-muted small text-uppercase">Correo Electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="fa-solid fa-envelope text-muted"></i></span>
                                    <input type="email" class="form-control border-start-0 rounded-end-3 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $cliente->email) }}">
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="telefono" class="form-label fw-bold text-muted small text-uppercase">Teléfono / WhatsApp</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="fa-solid fa-phone text-muted"></i></span>
                                    <input type="text" class="form-control border-start-0 rounded-end-3 @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono', $cliente->telefono) }}">
                                    @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="direccion" class="form-label fw-bold text-muted small text-uppercase">Dirección</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="fa-solid fa-location-dot text-muted"></i></span>
                                    <input type="text" class="form-control border-start-0 rounded-end-3 @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion', $cliente->direccion) }}">
                                    @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="notas" class="form-label fw-bold text-muted small text-uppercase">Notas Adicionales</label>
                                <textarea class="form-control rounded-3 @error('notas') is-invalid @enderror" id="notas" name="notas" rows="4">{{ old('notas', $cliente->notas) }}</textarea>
                                @error('notas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12 mt-5">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">
                                        <i class="fa-solid fa-floppy-disk me-2"></i> Actualizar Cliente
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

<style>
    .input-group-text {
        border-color: #dee2e6;
    }
    .form-control:focus {
        border-color: #5b6df0;
        box-shadow: none;
    }
    .form-control:focus + .input-group-text {
        border-color: #5b6df0;
    }
    .custom-radio .form-check-input:checked {
        background-color: #5b6df0;
        border-color: #5b6df0;
    }
</style>
@endsection
