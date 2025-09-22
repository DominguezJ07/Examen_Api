<?php

namespace App\Http\Controllers;

use App\Models\image;
use App\Models\user;
use App\Models\publication;
use App\Models\seller;
use App\Http\Requests\StoreRolRequest;
use App\Http\Requests\UpdateRolRequest;
use Illuminate\Http\Request;


class imageController extends Controller

{
    public function index()
    {
        $image = image::included()
        ->filter()
        ->sort()
        ->getOrPaginate();
        return response()->json($image);
    }
    public function store(Request $request)
    {
        $request->validate([
            'imageable' => 'required|morphs',
            'descripcion' => 'required|string'
            
        ]);

        $image = image::create($request->all());
        
        // Cargar las relaciones después de crear
        $image->load('User', 'Publication', 'Seller');
        
        return response()->json($image, 201);
    }

    /**
     * Mostrar una relación específica con sus datos relacionados
     */
    public function show($id)
    {
        $image = image::with('User', 'Publication', 'Seller')->findOrFail($id);
        return response()->json($image);
    }

    /**
     * Actualizar una relación existente
     */
    public function update(Request $request, image $image)
    {
        $request->validate([
            'imageable' => 'required|morphs',
            'descripcion' => 'required|string'
            
        ]);

        $image->update($request->all());
        
        $image->load('User', 'Publication', 'Seller');
        
        return response()->json($image);
    }

    /**
     * Eliminar una relación
     */
    public function destroy(image $image)
    {
        $image->delete();
        return response()->json(['message' => 'Relación eliminada exitosamente'], 200);
    }

}