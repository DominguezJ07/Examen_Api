<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\Saller;
use App\Models\Category;
use App\Http\Requests\StoreRolRequest;
use App\Http\Requests\UpdateRolRequest;
use Illuminate\Http\Request;


class PublicationController extends Controller

{
    public function index()
    {
        $Publication = Publication::included()
        ->filter()
        ->sort()
        ->getOrPaginate();
        return response()->json($Publication);
    }
    public function store(Request $request)
    {
        $request->validate([
        'titulo' => 'required|string',
        'precio' => 'required|integer',
        'descripcion' => 'required|text', 
        'visibilidad' => 'required|boolean'   
       ]);
        $Publication = Publication::create($request->all());
        
        // Cargar las relaciones después de crear
        $Publication->load('seller', 'category');
        
        return response()->json($Publication, 201);
    }

    /**
     * Mostrar una relación específica con sus datos relacionados
     */
    public function show($id)
    {
        $Publication = Publication::with('seller', 'category')->findOrFail($id);
        return response()->json($Publication);
    }

    /**
     * Actualizar una relación existente
     */
    public function update(Request $request, Publication $Publication)
    {
        $request->validate([
      
    'nombre' => 'required|string',
        'descripcion' => 'required|String'  
]);
            
    

        $Publication->update($request->all());
        
        $Publication->load('seller', 'category');
        
        return response()->json($Publication);
    }

    /**
     * Eliminar una relación
     */
    public function destroy(Publication $Publication)
    {
        $Publication->delete();
        return response()->json(['message' => 'Relación eliminada exitosamente'], 200);
    }

}