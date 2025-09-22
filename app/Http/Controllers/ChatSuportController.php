<?php

namespace App\Http\Controllers;

use App\Models\ChatSuport;
use App\Models\User;
use App\Http\Requests\StoreRolRequest;
use App\Http\Requests\UpdateRolRequest;
use Illuminate\Http\Request;


class ChatSuportController extends Controller

{
    public function index()
    {
        $ChatSuport = ChatSuport::with('User')->get();
        return response()->json($ChatSuport);
    }

    public function store(Request $request)
    {
        $request->validate([
            'mensaje' => 'required|text',
            'fecha_mensajes' => 'required|timestamp',
            'atendido' => 'required|boolean'
            
        ]);

        $ChatSuport = ChatSuport::create($request->all());
        
        // Cargar las relaciones después de crear
        $ChatSuport->load('User');
        
        return response()->json($ChatSuport, 201);
    }

    /**
     * Mostrar una relación específica con sus datos relacionados
     */
    public function show($id)
    {
        $ChatSuport = ChatSuport::with('User')->findOrFail($id);
        return response()->json($ChatSuport);
    }

    /**
     * Actualizar una relación existente
     */
    public function update(Request $request, ChatSuport $ChatSuport)
    {
        $request->validate([
            'mensaje' => 'required|text',
            'fecha_mensajes' => 'required|timestamp',
            'atendido' => 'required|boolean'
            
        ]);

        $ChatSuport->update($request->all());
        
        $ChatSuport->load('User');
        
        return response()->json($ChatSuport);
    }

    /**
     * Eliminar una relación
     */
    public function destroy(ChatSuport $ChatSuport)
    {
        $ChatSuport->delete();
        return response()->json(['message' => 'Relación eliminada exitosamente'], 200);
    }

}