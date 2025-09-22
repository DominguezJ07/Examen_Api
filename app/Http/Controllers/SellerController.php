<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\User;
use App\Http\Requests\StoreRolRequest;
use App\Http\Requests\UpdateRolRequest;
use Illuminate\Http\Request;


class SellerController extends Controller

{
    public function index()
    {
        $Seller = Seller::with('User')->get();
        return response()->json($Seller);
    }

    public function store(Request $request)
    {
        $request->validate([
        'nombre' => 'required|string',
        'descripcion' => 'required|String'  
       ]);
        $Seller = Seller::create($request->all());
        
        // Cargar las relaciones después de crear
        $Seller->load('User');
        
        return response()->json($Seller, 201);
    }

    /**
     * Mostrar una relación específica con sus datos relacionados
     */
    public function show($id)
    {
        $Seller = Seller::with('User')->findOrFail($id);
        return response()->json($Seller);
    }

    /**
     * Actualizar una relación existente
     */
    public function update(Request $request, Seller $Seller)
    {
        $request->validate([
      
    'nombre' => 'required|string',
        'descripcion' => 'required|String'  
]);
            
    

        $Seller->update($request->all());
        
        $Seller->load('User');
        
        return response()->json($Seller);
    }

    /**
     * Eliminar una relación
     */
    public function destroy(Seller $Seller)
    {
        $Seller->delete();
        return response()->json(['message' => 'Relación eliminada exitosamente'], 200);
    }

}