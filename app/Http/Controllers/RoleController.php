<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Http\Requests\StoreRolRequest;
use App\Http\Requests\UpdateRolRequest;
use Illuminate\Http\Request;


class RoleController extends Controller

{
     public function index()
    {
        $Role= Role::included()
        ->filter()
        ->sort()
        ->getOrPaginate();
        return response()->json($Role);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'permisos' => 'required|json'
        ]);

        $Role = Role::create($request->all());
        
        // Cargar las relaciones después de crear
        $Role->load('User');
        
        return response()->json($Role, 201);
    }

    /**
     * Mostrar una relación específica con sus datos relacionados
     */
    public function show($id)
    {
        $Role = Role::with('User')->findOrFail($id);
        return response()->json($Role);
    }

    /**
     * Actualizar una relación existente
     */
    public function update(Request $request, Role $Role)
    {
        $request->validate([
            'nombre' => 'required|string|',
            'permisos' => 'required|json|'
            
        ]);

        $Role->update($request->all());
        
        $Role->load('User');
        
        return response()->json($Role);
    }

    /**
     * Eliminar una relación
     */
    public function destroy(Role $Role)
    {
        $Role->delete();
        return response()->json(['message' => 'Relación eliminada exitosamente'], 200);
    }

}