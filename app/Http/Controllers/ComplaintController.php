<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\User;
use App\Models\Publication;
use App\Http\Requests\StoreRolRequest;
use App\Http\Requests\UpdateRolRequest;
use Illuminate\Http\Request;


class ComplaintController extends Controller

{
    

    public function store(Request $request)
    {
        $request->validate([
    'estado' => 'required|boolean',
    'descripcion' => 'required|text'  
]);
        $Complaint = Complaint::create($request->all());
        
        // Cargar las relaciones después de crear
        $Complaint->load('User', 'publication');
        
        return response()->json($Complaint, 201);
    }

    /**
     * Mostrar una relación específica con sus datos relacionados
     */
    public function show($id)
    {
        $Complaint = Complaint::with('User', 'publication')->findOrFail($id);
        return response()->json($Complaint);
    }

    /**
     * Actualizar una relación existente
     */
    public function update(Request $request, Complaint $Complaint)
    {
        $request->validate([
      
    'estado' => 'required|boolean',
    'descripcion' => 'required|text'  
]);
            
    

        $Complaint->update($request->all());
        
        $Complaint->load('User', 'publication');
        
        return response()->json($Complaint);
    }

    /**
     * Eliminar una relación
     */
    public function destroy(Complaint $Complaint)
    {
        $Complaint->delete();
        return response()->json(['message' => 'Relación eliminada exitosamente'], 200);
    }

}