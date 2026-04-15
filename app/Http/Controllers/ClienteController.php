<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        return view('eventos.clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('eventos.clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'documento' => 'nullable|string|max:50|unique:clientes',
            'email' => 'nullable|email|max:255|unique:clientes',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'tipo' => 'required|in:Persona,Empresa',
            'notas' => 'nullable|string',
        ]);

        Cliente::create($validated);

        return redirect()->route('eventos.clientes.index')->with('success', 'Cliente creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        return view('eventos.clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        return view('eventos.clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'documento' => 'nullable|string|max:50|unique:clientes,documento,' . $cliente->id,
            'email' => 'nullable|email|max:255|unique:clientes,email,' . $cliente->id,
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'tipo' => 'required|in:Persona,Empresa',
            'notas' => 'nullable|string',
        ]);

        $cliente->update($validated);

        return redirect()->route('eventos.clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('eventos.clientes.index')->with('success', 'Cliente eliminado correctamente.');
    }
}
