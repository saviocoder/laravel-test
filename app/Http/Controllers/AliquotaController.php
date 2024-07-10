<?php

namespace App\Http\Controllers;

use App\Models\Aliquota;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AliquotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aliquotas = Aliquota::all();
        return response()->json($aliquotas);
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
            'categoria_id' => 'required|exists:categorias,id',
            'imposto_id' => 'required|exists:impostos,id',
            'aliquota' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $aliquota = Aliquota::create([
                'categoria_id' => $request->input('categoria_id'),
                'imposto_id' => $request->input('imposto_id'),
                'aliquota' => $request->input('aliquota'),
            ]);
            
            return response()->json($aliquota, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar a aliquota do imposto',
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
    public function update(Request $request)
    {
        $categoria_id = $request->input('categoria_id');
        $imposto_id = $request->input('imposto_id');
        $aliquota_request = $request->input('aliquota');

        $validator = Validator::make($request->all(), [
            'categoria_id' => 'required|exists:categorias,id',
            'imposto_id' => 'required|exists:impostos,id',
            'aliquota' => 'required|numeric|min:0',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        try {
            $aliquota = Aliquota::where('categoria_id', $categoria_id)
                ->where('imposto_id', $imposto_id)
                ->first();

            $aliquota->aliquota = $aliquota_request;
            $aliquota->save();
                
            return response()->json(['message' => 'Alíquota atualizada com sucesso'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar a aliquota'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }
    public function deletar_aliquota(Request $request)
    {
        $categoria_id = $request->input('categoria_id');
        $imposto_id = $request->input('imposto_id');
        
        try {
            DB::table('aliquotas')
            ->where('categoria_id', $categoria_id)
            ->where('imposto_id', $imposto_id)
            ->delete();
            return response()->json(['menssage' => 'Aliquota desvinculada da categoria com sucesso'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao desvincular Aliquota da Categoria'], 500);
        }
    }
}
