<?php

namespace App\Http\Controllers;

use App\Models\Paquete;
use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PaqueteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paquetes = Paquete::withCount('equipos')->get();
        return view('eventos.paquetes.index', compact('paquetes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $equipos = Equipo::where('estado', 'Disponible')->get();
        return view('eventos.paquetes.create', compact('equipos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio_dia' => 'required|numeric|min:0',
            'precio_hora' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|max:2048',
            'equipos' => 'required|array',
            'equipos.*.id' => 'required|exists:equipos,id',
            'equipos.*.cantidad' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('imagen')) {
                $validated['imagen'] = $request->file('imagen')->store('paquetes', 'public');
            }

            $paquete = Paquete::create($validated);

            foreach ($request->equipos as $item) {
                $paquete->equipos()->attach($item['id'], ['cantidad' => $item['cantidad']]);
            }

            DB::commit();
            return redirect()->route('eventos.paquetes.index')->with('success', 'Paquete creado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al crear el paquete: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Paquete $paquete)
    {
        $paquete->load('equipos');
        return view('eventos.paquetes.show', compact('paquete'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Paquete $paquete)
    {
        $paquete->load('equipos');
        $equipos = Equipo::where('estado', 'Disponible')->get();
        return view('eventos.paquetes.edit', compact('paquete', 'equipos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Paquete $paquete)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio_dia' => 'required|numeric|min:0',
            'precio_hora' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|max:2048',
            'equipos' => 'required|array',
            'equipos.*.id' => 'required|exists:equipos,id',
            'equipos.*.cantidad' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('imagen')) {
                if ($paquete->imagen) {
                    Storage::disk('public')->delete($paquete->imagen);
                }
                $validated['imagen'] = $request->file('imagen')->store('paquetes', 'public');
            }

            $paquete->update($validated);

            $syncData = [];
            foreach ($request->equipos as $item) {
                $syncData[$item['id']] = ['cantidad' => $item['cantidad']];
            }
            $paquete->equipos()->sync($syncData);

            DB::commit();
            return redirect()->route('eventos.paquetes.index')->with('success', 'Paquete actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar el paquete: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paquete $paquete)
    {
        if ($paquete->imagen) {
            Storage::disk('public')->delete($paquete->imagen);
        }
        $paquete->delete();
        return redirect()->route('eventos.paquetes.index')->with('success', 'Paquete eliminado correctamente.');
    }
}
