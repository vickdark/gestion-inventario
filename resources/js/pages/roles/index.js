export function initRolesIndex(config) {
    const { routes, tokens } = config;

    const grid = new DataGrid("roles-wrapper", {
        url: routes.index,
        columns: [
            { id: 'nombre', name: "Nombre" },
            { id: 'slug', name: "Slug" },
            { id: 'users_count', name: "Usuarios" },
            { id: 'descripcion', name: "Descripción" },
            { 
                id: 'actions',
                name: "Acciones",
                formatter: (cell, row) => {
                    const id = row.cells[1].data; // El slug o id para las rutas
                    const nombre = row.cells[0].data;
                    const editUrl = routes.edit.replace(':id', id);
                    const deleteUrl = routes.destroy.replace(':id', id);
                    const permsUrl = routes.permissions.replace(':id', id);
                    
                    const isAdmin = id === 'admin';
                    
                    return DataGrid.html(`
                        <div class="d-flex justify-content-end gap-2">
                            <a href="${permsUrl}" class="btn btn-sm btn-outline-info rounded-pill px-3" title="Gestionar Permisos">
                                <i class="fas fa-key me-1"></i> Permisos
                            </a>
                            <a href="${editUrl}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="fas fa-edit me-1"></i> Editar
                            </a>
                            <button type="button" 
                                class="btn btn-sm btn-outline-danger rounded-pill px-3" 
                                onclick="window.deleteRole('${deleteUrl}', '${nombre}')"
                                ${isAdmin ? 'disabled' : ''}>
                                <i class="fas fa-trash-alt me-1"></i> Eliminar
                            </button>
                        </div>
                    `);
                }
            }
        ],
        mapData: (r) => [
            r.nombre, 
            r.slug, 
            r.users_count,
            r.descripcion || 'Sin descripción',
            null
        ]
    }).render();

    window.deleteRole = async function(url, nombre) {
        const confirmed = await Notify.confirm({
            title: '¿Eliminar rol?',
            text: `¿Estás seguro de eliminar el rol "${nombre}"? Esta acción no se puede deshacer.`,
            confirmButtonText: 'Sí, eliminar',
            confirmButtonColor: '#e74a3b'
        });

        if (confirmed) {
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': tokens.csrf,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                });

                if (response.ok) {
                    Notify.success('Eliminado', 'El rol ha sido eliminado correctamente.');
                    window.location.reload(); 
                } else {
                    const result = await response.json();
                    Notify.error('Error', result.message || 'No se pudo eliminar el rol.');
                }
            } catch (error) {
                Notify.error('Error', 'Ocurrió un error inesperado.');
                console.error(error);
            }
        }
    };
}
