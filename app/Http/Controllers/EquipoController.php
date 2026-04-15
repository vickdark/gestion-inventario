<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\CategoriaEquipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipos = Equipo::with('categoria')->get();
        $categorias = CategoriaEquipo::all();
        return view('eventos.equipos.index', compact('equipos', 'categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = CategoriaEquipo::all();
        return view('eventos.equipos.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'categoria_id' => 'required|exists:categoria_equipos,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio_dia' => 'required|numeric|min:0',
            'precio_hora' => 'required|numeric|min:0',
            'cantidad_total' => 'required|integer|min:0',
            'estado' => 'required|in:Disponible,Mantenimiento,Baja',
            'imagen' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('equipos', 'public');
        }

        // Por defecto, la cantidad disponible es igual a la total al crear
        $validated['cantidad_disponible'] = $validated['cantidad_total'];

        Equipo::create($validated);

        return redirect()->route('eventos.equipos.index')->with('success', 'Equipo creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipo $equipo)
    {
        return view('eventos.equipos.show', compact('equipo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipo $equipo)
    {
        $categorias = CategoriaEquipo::all();
        return view('eventos.equipos.edit', compact('equipo', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipo $equipo)
    {
        $validated = $request->validate([
            'categoria_id' => 'required|exists:categoria_equipos,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio_dia' => 'required|numeric|min:0',
            'precio_hora' => 'required|numeric|min:0',
            'cantidad_total' => 'required|integer|min:0',
            'estado' => 'required|in:Disponible,Mantenimiento,Baja',
            'imagen' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior
            if ($equipo->imagen) {
                Storage::disk('public')->delete($equipo->imagen);
            }
            $validated['imagen'] = $request->file('imagen')->store('equipos', 'public');
        }

        // Ajustar cantidad disponible si la total cambió
        $diferencia = $validated['cantidad_total'] - $equipo->cantidad_total;
        $validated['cantidad_disponible'] = max(0, $equipo->cantidad_disponible + $diferencia);

        $equipo->update($validated);

        return redirect()->route('eventos.equipos.index')->with('success', 'Equipo actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipo $equipo)
    {
        if ($equipo->imagen) {
            Storage::disk('public')->delete($equipo->imagen);
        }
        $equipo->delete();
        return redirect()->route('eventos.equipos.index')->with('success', 'Equipo eliminado correctamente.');
    }
}
