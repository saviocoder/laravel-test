<?php

namespace App\Http\Controllers;

use App\Models\Aliquota;
use App\Models\Categoria;
use App\Models\Imposto;
use App\Models\Produto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produtos = Produto::all();
        return response()->json($produtos);
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
            'descricao' => 'nullable|text|max:200',
            'preco' => 'required|numeric|min:0',
            'qtd_estoque' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $produto = Produto::create([
                'nome' => $request->input('nome'),
                'descricao' => $request->input('descricao'),
                'preco' => $request->input('preco'),
                'qtd_estoque' => $request->input('qtd_estoque'),
                'categoria_id' => $request->input('categoria_id')
            ]);
            
            return response()->json($produto, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar o Produto',
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
            'descricao' => 'nullable|string|max:200',
            'preco' => 'required|numeric|min:0',
            'qtd_estoque' => 'required|integer|min:0',
            'categoria_id' => 'required|integer|exists:categorias,id'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        try {
            $produto = Produto::findOrFail($id);
            $produto->update($request->all());
    
            return response()->json($produto, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar o produto'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $produto = Produto::find($id);
            $produto->delete();
            return response()->json(['menssage' => 'Produto deletado com sucesso'], 200);

        } catch(Exception $e){
            return response()->json(['error' => 'Erro ao deletar produto'], 500);
        }
    }
    public function calculo_preco_produto(Request $request, $id){
        $produto_id = Produto::find($id);
        if (!$produto_id){
            return response()->json([
                'mensagem' => 'Produto não encontrado'
                ], 404);
        }

        $parcelas = $request->get('parcelas');
        $preco_produto = $produto_id->preco;
        $juros = 0;

        if ($parcelas >= 1 && $parcelas <= 3){
            $juros = 0.00;
        }elseif ($parcelas >= 4 && $parcelas <= 7) {
            $juros = 0.02;
        }elseif($parcelas >= 8 && $parcelas <= 10){
            $juros = 0.04;
        }else {
            return response()->json(['erro' => 'parcela inválida'], 404);
        }

        $preco_final = $preco_produto * (1 + $juros);

        return response()->json([
            'preco_produto' => $preco_produto,
            'preco_final' => $preco_final,
            'parcelas' => $parcelas,
            'juros' => 100 * $juros .'%',
        ]);
    }

    public function calcular_icms_produto(Request $request, $id){
        
        try {
            $produto = Produto::find($id);

            if(!$produto){
                return response()->json(['mensagem' => 'Produto nao encontrado', 404]);
            }

            $categoria = Categoria::find($produto->categoria_id);
            
            if (!$categoria) {
                return response()->json([
                    'message' => 'Categoria não encontrada para o produto'
                ], 404);
            }

            $soma_aliquotas = 0;
            $aliquotas = Aliquota::where('categoria_id', $produto->categoria_id)->get();

            foreach ($aliquotas as $aliquota) {
                $soma_aliquotas += $aliquota->aliquota;
            }
    
            $calculo_icms = $produto->preco * ($soma_aliquotas / 100);
            $produto_com_icms = $produto->preco + $calculo_icms;


            return response()->json([
                'produto' => $produto->nome,
                'preco' => $produto->preco,
                'categoria' => $categoria->nome,
                'ICMS' => $soma_aliquotas .'%',
                'preco_produto_atualizado' => $produto_com_icms
            ]);
            
            
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao calcular o ICMS'], 500);
        }

    }
}
