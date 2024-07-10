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
            $produto_id = Produto::find($id);
            $categoria = $produto_id->categoria_id;
            
            $nome_categoria = Categoria::select('nome')
                ->where('id', $categoria)
                ->first();

            if(!$produto_id){
                return response()->json(['mensagem' => 'Produto nao encontrado', 404]);
            }
            if (!$categoria) {
                return response()->json([
                    'message' => 'Categoria não encontrada para o produto'
                ], 404);
            }
                        
            $aliquota_icms = Aliquota::where('categoria_id', $categoria)->first();

            if(!$aliquota_icms){
                return response()->json([
                    'mensagem' => 'Alíquota de ICMS não encontrada para a categoria do produto'
                ], 404);
            }

            // $imposto = Imposto::select('nome')
                // ->where('id', $aliquota_icms->imposto_id)
                // ->first();
            $imposto = Imposto::find($aliquota_icms->imposto_id);
    
            // Convertendo a alíquota para formato decimal (0.05 -> 5%)
            $icms = floatval($aliquota_icms->aliquota);
            $calculo_icms = $produto_id->preco * $icms;

            return response()->json([
                'produto' => $produto_id->nome,
                'preco' => $produto_id->preco,
                'categoria' => $nome_categoria->nome,
                'imposto' => $imposto ? $imposto->nome : 'Nenhum imposto encontrado',
                'ICMS' => $calculo_icms
            ]);
            

            // fazer uma forma de calcular todos os impostos vinculados à categoria do produto
            
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao calcular o ICMS'], 500);
        }

    }
}
