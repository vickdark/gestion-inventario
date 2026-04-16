<template id="item-form-template">
    <div class="row g-3 align-items-end item-row mb-3 border-bottom pb-3">
        <div class="col-md-3">
            <label for="items___INDEX___type" class="form-label">Tipo</label>
            <select class="form-select item-type" name="items[__INDEX__][type]" id="items___INDEX___type" required>
                <option value="">Seleccione</option>
                <option value="equipo">Equipo</option>
                <option value="paquete">Paquete</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="items___INDEX___id" class="form-label">Item</label>
            <select class="form-select item-id" name="items[__INDEX__][id]" id="items___INDEX___id" required>
                <option value="">Seleccione</option>
            </select>
            <input type="hidden" class="item-nombre" name="items[__INDEX__][nombre]">
        </div>
        <div class="col-md-2">
            <label for="items___INDEX___cantidad" class="form-label">Cantidad</label>
            <input type="number" class="form-control item-cantidad" name="items[__INDEX__][cantidad]" id="items___INDEX___cantidad" value="1" min="1" required>
        </div>
        <div class="col-md-2">
            <label for="items___INDEX___precio" class="form-label">Precio Unitario</label>
            <input type="number" class="form-control item-precio" name="items[__INDEX__][precio]" id="items___INDEX___precio" step="0.01" min="0" required>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger remove-item-btn">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    </div>
</template>