<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class SyncPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:sync {--clean : Elimina permisos que ya no existen en las rutas}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza las rutas del sistema con la tabla de permisos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando sincronización de permisos...');

        $routes = Route::getRoutes();
        $permissionsCreated = 0;
        $activeSlugs = [];

        foreach ($routes as $route) {
            $name = $route->getName();
            
            // Solo procesamos rutas que tengan nombre y no sean de sistema/ignorar
            if ($name && $this->shouldSync($name)) {
                $activeSlugs[] = $name;
                
                $permission = \App\Models\Roles\Permission::updateOrCreate(
                    ['slug' => $name],
                    [
                        'nombre' => $this->generateName($name),
                        'descripcion' => $this->generateDescription($name, $route),
                        'is_menu' => $this->isMenu($name),
                        'icon' => $this->generateIcon($name),
                        'module' => $this->generateModuleName($name),
                        'order' => $this->generateOrder($name),
                    ]
                );

                if ($permission->wasRecentlyCreated) {
                    $permissionsCreated++;
                    $this->line(" <info>✔</info> Nuevo permiso detectado: {$name} -> {$permission->nombre}");
                }
            }
        }

        // Limpieza automática de permisos antiguos (siempre activo por petición del usuario)
        $deleted = \App\Models\Roles\Permission::whereNotIn('slug', $activeSlugs)->delete();
        if ($deleted > 0) {
            $this->warn("Se eliminaron {$deleted} permisos de rutas que ya no existen.");
        }

        $this->info("Sincronización completada. Total nuevos: {$permissionsCreated}.");
    }

    /**
     * Determina si una ruta debe ser sincronizada.
     */
    protected function shouldSync($name)
    {
        $excludedPrefixes = [
            'sanctum.', 'ignition.', 'livewire.', 'verification.', 
            'password.', 'login', 'logout', 'register',
            'profile.', 'storage.'
        ];
        
        foreach ($excludedPrefixes as $prefix) {
            if (str_starts_with($name, $prefix)) {
                return false;
            }
        }

        // Permitir dashboard y rutas con puntos
        if ($name === 'dashboard' || str_contains($name, '.')) {
            return true;
        }

        return false;
    }

    /**
     * Genera un nombre legible y en español a partir del slug.
     */
    protected function generateName($slug)
    {
        if ($slug === 'dashboard') return 'Dashboard General';
        if ($slug === 'settings.index') return 'Configuración';
        if ($slug === 'settings.update') return 'Actualizar Configuración';
        
        // Nombres específicos para el módulo de eventos
        if (str_starts_with($slug, 'eventos.')) {
            $name = str_replace('eventos.', '', $slug);
            $name = str_replace('.index', '', $name);
            
            $names = [
                'dashboard' => 'Panel Eventos',
                'clientes' => 'Clientes',
                'equipos' => 'Equipos',
                'paquetes' => 'Paquetes',
                'agenda' => 'Agenda',
                'cotizaciones' => 'Cotizaciones',
                'logistica' => 'Logística',
                'finanzas' => 'Finanzas',
                'ventas' => 'Ventas',
            ];
            
            return $names[$name] ?? ucfirst($name);
        }

        // Dashboard por rol
        if (str_starts_with($slug, 'dashboard.')) {
            $role = ucfirst(str_replace('dashboard.', '', $slug));
            return "Dashboard {$role}";
        }

        $parts = explode('.', $slug);
        $action = end($parts);
        $module = count($parts) > 1 ? $parts[count($parts) - 2] : 'General';

        // Traducciones personalizadas para los módulos principales
        $moduleTranslations = [
            'usuarios' => 'Usuarios',
            'roles' => 'Roles',
            'permissions' => 'Permisos',
        ];

        $moduleName = $moduleTranslations[strtolower($module)] ?? ucfirst($module);

        // Si es la acción principal (index), solo devolvemos el nombre del módulo
        if ($action === 'index') {
            return $moduleName;
        }

        $translations = $this->getTranslations();
        $actionName = $translations[$action] ?? ucfirst($action);

        return "{$actionName} {$moduleName}";
    }

    /**
     * Obtiene las traducciones de las acciones comunes.
     */
    protected function getTranslations()
    {
        return [
            'index'   => 'Ver lista de',
            'show'    => 'Ver detalle de',
            'create'  => 'Crear',
            'store'   => 'Guardar',
            'edit'    => 'Editar',
            'update'  => 'Actualizar',
            'destroy' => 'Eliminar',
            'sync'    => 'Sincronizar',
            'export'  => 'Exportar',
            'import'  => 'Importar',
            'edit_permissions'   => 'Gestionar permisos de',
            'update_permissions' => 'Actualizar permisos de',
        ];
    }

    /**
     * Genera una descripción clara basada en la ruta y el controlador.
     */
    protected function generateDescription($slug, $route)
    {
        if (str_starts_with($slug, 'dashboard.')) {
            $role = str_replace('dashboard.', '', $slug);
            return "Vista de panel principal personalizada para el rol {$role}";
        }

        $parts = explode('.', $slug);
        $action = end($parts);
        $module = count($parts) > 1 ? $parts[count($parts) - 2] : 'General';
        
        $translations = $this->getTranslations();
        $actionName = $translations[$action] ?? ucfirst($action);
        $moduleName = ucfirst($module);

        return "Permite {$actionName} {$moduleName} en el sistema";
    }

    /**
     * Determina si la ruta debe aparecer en el menú.
     * Solo permitimos la ruta principal (index) de cada módulo.
     */
    protected function isMenu($slug)
    {
        // El dashboard principal siempre va
        if ($slug === 'dashboard') return true;
        
        // Para el módulo de eventos, permitimos su dashboard propio
        if ($slug === 'eventos.dashboard') return true;

        // Para el resto, solo permitimos la acción 'index' (la lista principal)
        $parts = explode('.', $slug);
        $action = end($parts);

        return $action === 'index';
    }

    /**
     * Genera un icono sugerido según el nombre del módulo.
     */
    protected function generateIcon($slug)
    {
        if ($slug === 'dashboard') return 'fa-solid fa-gauge-high';
        if (str_starts_with($slug, 'settings.')) return 'fa-solid fa-gear';

        if (str_starts_with($slug, 'eventos.')) {
            $name = str_replace('eventos.', '', $slug);
            $name = str_replace('.index', '', $name);
            
            $icons = [
                'dashboard' => 'fa-solid fa-chart-line',
                'clientes' => 'fa-solid fa-users-viewfinder',
                'equipos' => 'fa-solid fa-boxes-stacked',
                'paquetes' => 'fa-solid fa-box-archive',
                'agenda' => 'fa-solid fa-calendar-days',
                'cotizaciones' => 'fa-solid fa-file-invoice',
                'logistica' => 'fa-solid fa-truck-fast',
                'finanzas' => 'fa-solid fa-coins',
                'ventas' => 'fa-solid fa-handshake-angle',
            ];
            
            return $icons[$name] ?? 'fa-solid fa-circle-dot';
        }

        $parts = explode('.', $slug);
        $module = count($parts) > 1 ? $parts[count($parts) - 2] : 'General';

        $icons = [
            'usuarios' => 'fa-solid fa-users',
            'roles'    => 'fa-solid fa-user-shield',
            'permissions' => 'fa-solid fa-key',
            'infraestructura' => 'fa-solid fa-server',
            'inventario' => 'fa-solid fa-laptop-code',
            'asignaciones' => 'fa-solid fa-hand-holding-hand',
            'mantenimientos' => 'fa-solid fa-tools',
            'bajas' => 'fa-solid fa-trash-can',
            'configuracion' => 'fa-solid fa-gears',
        ];

        return $icons[strtolower($module)] ?? 'fa-solid fa-circle-dot';
    }

    /**
     * Genera el nombre del módulo para agrupar en el menú.
     */
    protected function generateModuleName($slug)
    {
        if ($slug === 'dashboard') return 'Dashboard';
        if (str_starts_with($slug, 'settings.')) return 'Configuración';
        
        if (str_starts_with($slug, 'eventos.')) {
            return $this->generateName($slug);
        }

        $parts = explode('.', $slug);
        if (count($parts) <= 1) return 'General';

        $module = $parts[count($parts) - 2];
        
        return ucfirst($module);
    }

    /**
     * Genera el orden sugerido.
     */
    protected function generateOrder($slug)
    {
        if ($slug === 'dashboard') return 1;

        // Módulo de Eventos (Organización Solicitada)
        if (str_starts_with($slug, 'eventos.')) {
            $name = str_replace(['eventos.', '.index'], '', $slug);
            $orders = [
                'dashboard'    => 10,
                'clientes'     => 20,
                'equipos'      => 30,
                'paquetes'     => 40,
                'agenda'       => 50,
                'cotizaciones' => 60,
                'ventas'       => 70,
                'logistica'    => 80,
                'finanzas'     => 90,
            ];
            return $orders[$name] ?? 100;
        }

        if (str_contains($slug, 'usuarios')) return 200;
        if (str_contains($slug, 'roles')) return 210;
        if (str_contains($slug, 'settings')) return 300;
        
        return 150;
    }
}
