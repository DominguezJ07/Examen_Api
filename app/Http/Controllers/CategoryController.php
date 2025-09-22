<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Publication;
use App\Http\Requests\StoreRolRequest;
use App\Http\Requests\UpdateRolRequest;
use Illuminate\Http\Request;


class CategoryController extends Controller

{
    public function index()
    {
        $Category = Category::included()
        ->filter()
        ->sort()
        ->getOrPaginate();
        return response()->json($Category);
    }


    public function store(Request $request)
    {
        $request->validate([
            'categoria' => 'required|string|'
            
        ]);

        $Category = Category::create($request->all());
        
        // Cargar las relaciones después de crear
        $Category->load('publication');
        
        return response()->json($Category, 201);
    }

    /**
     * Mostrar una relación específica con sus datos relacionados
     */
    public function show($id)
    {
        $Category = Category::with('publication')->findOrFail($id);
        return response()->json($Category);
    }

    /**
     * Actualizar una relación existente
     */
    public function update(Request $request, Category $Category)
    {
        $request->validate([
            'nombre' => 'required|string|',
            'permisos' => 'required|json|'
            
        ]);

        $Category->update($request->all());
        
        $Category->load('publication');
        
        return response()->json($Category);
    }

    /**
     * Eliminar una relación
     */
    public function destroy(Category $Category)
    {
        $Category->delete();
        return response()->json(['message' => 'Relación eliminada exitosamente'], 200);
    }

}