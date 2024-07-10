<?php

namespace App\Http\Controllers;

use App\Models\Imposto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImpostoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $impostos = Imposto::all();
        return response()->json($impostos);
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
            $imposto = Imposto::create([
                'nome' => $request->input('nome'),
            ]);
            
            return response()->json($imposto, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar o imposto',
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
            $imposto = Imposto::find($id);
            $imposto->update($request->all());
    
            return response()->json(['mensagem' => 'Imposto atualizado com sucesso', 200]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar o Imposto'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $imposto = Imposto::find($id);
            $imposto->delete();
            return response()->json(['menssage' => 'Imposto deletado com sucesso'], 200);

        } catch(Exception $e){
            return response()->json(['error' => 'Erro ao deletar imposto'], 500);
        }
    }
}
