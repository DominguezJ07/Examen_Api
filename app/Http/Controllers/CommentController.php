<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use App\Http\Requests\StoreRolRequest;
use App\Http\Requests\UpdateRolRequest;
use Illuminate\Http\Request;


class CommentController extends Controller

{
    public function index()
    {
        $Comment = Comment::included()
        ->filter()
        ->sort()
        ->getOrPaginate();
        return response()->json($Comment);
    }

    public function store(Request $request)
    {
        $request->validate([
    'texto' => 'required|string',
    'valor_estrella' => 'required|integer'  
]);
        $Comment = Comment::create($request->all());
        
        // Cargar las relaciones después de crear
        $Comment->load('User', 'publication');
        
        return response()->json($Comment, 201);
    }

    /**
     * Mostrar una relación específica con sus datos relacionados
     */
    public function show($id)
    {
        $Comment = Comment::with('User', 'publication')->findOrFail($id);
        return response()->json($Comment);
    }

    /**
     * Actualizar una relación existente
     */
    public function update(Request $request, Comment $Comment)
    {
        $request->validate([
      
    'texto' => 'required|string',
    'valor_estrella' => 'required|integer|min:1|max:5'  
]);
            
    

        $Comment->update($request->all());
        
        $Comment->load('User', 'publication');
        
        return response()->json($Comment);
    }

    /**
     * Eliminar una relación
     */
    public function destroy(Comment $Comment)
    {
        $Comment->delete();
        return response()->json(['message' => 'Relación eliminada exitosamente'], 200);
    }

}