<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Seller;
use App\Models\Comment;
use App\Models\ChatSuport;
use App\Models\Role;
use App\Models\Complaint;
use App\Models\Image;
use App\Models\Publication;
use App\Http\Requests\StoreRolRequest;
use App\Http\Requests\UpdateRolRequest;
use Illuminate\Http\Request;


class UserController extends Controller

{
    public function index()
    {
        $User = User::included()
        ->filter()
        ->sort()
        ->getOrPaginate();
        return response()->json($User);
    }

    public function store(Request $request)
    {
        $request->validate([    
    'primer_nombre' => 'required|string',
    'segundo_nombre' => 'required|string',
    'primer_apellido' => 'required|string',
    'segundo_apellido' => 'required|string',
    'activo' => 'required|boolean',
    'rol_id' => 'required|exists:roles,id' 
]);

        $User = User::create($request->all());
        
        // Cargar las relaciones después de crear
        $User->load('seller', 'comment', 'ChatSuport', 'role', 'complaint', 'image', 'publication');
        
        return response()->json($User, 201);
    }

    /**
     * Mostrar una relación específica con sus datos relacionados
     */
    public function show($id)
    {
        $User = User::with('Seller', 'Comment', 'ChatSuport', 'Role', 'Complaint', 'Image', 'Publication')->findOrFail($id);
        return response()->json($User);
    }

    /**
     * Actualizar una relación existente
     */
    public function update(Request $request, User $User)
    {
        $request->validate([
            'primer_nombre' => 'required|string',
    'segundo_nombre' => 'required|string',
    'primer_apellido' => 'required|string',
    'segundo_apellido' => 'required|string',
    'activo' => 'required|boolean'
            
        ]);

        $User->update($request->all());
        
        $User->load('Seller', 'Comment', 'ChatSuport', 'Role', 'Complaint', 'Image', 'Publication');
        
        return response()->json($User);
    }

    /**
     * Eliminar una relación
     */
    public function destroy(User $User)
    {
        $User->delete();
        return response()->json(['message' => 'Relación eliminada exitosamente'], 200);
    }

}