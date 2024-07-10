<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;

class CategoriaController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::all();
        return response()->json($categorias);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $categoria = Categoria::create([
                'nome'=>$request->get('nome')
            ]);
            
            return response()->json($categoria, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar a categoria',
                'error' => $e->getMessage()
            ], 500);
        }

    }
    


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:100',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        try {
            $categoria = Categoria::find($id);
            $categoria->update($request->all());
    
            return response()->json($categoria, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar a categoria'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $categoria = Categoria::find($id);
            $categoria->delete();
            return response()->json(['menssage' => 'Categoria adicionada com sucesso'], 200);

        } catch(Exception $e){
            return response()->json(['error' => 'Erro ao deletar categoria'], 500);
        }
    }
}
