<?php

use App\Http\Controllers\AliquotaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ImpostoController;
use App\Http\Controllers\ProdutoController;
use App\Models\Imposto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/produto')->group(function (){
    Route::post('/adicionar', [ProdutoController::class, 'store']);
    Route::put('/atualizar', [ProdutoController::class, 'update']);
    Route::delete('/deletar', [ProdutoController::class, 'destroy']);
    Route::get('/listar', [ProdutoController::class, 'index']);
    Route::get('/{id}/preco-parcelado', [ProdutoController::class, 'calculo_preco_produto']);
    Route::get('/{id}/calcular-icms', [ProdutoController::class, 'calcular_icms_produto']);
});

Route::prefix('/categoria')->group(function (){
    Route::post('/adicionar', [CategoriaController::class, 'store']);
    Route::put('/atualizar', [CategoriaController::class, 'update']);
    Route::delete('/deletar', [CategoriaController::class, 'destroy']);
    Route::get('/listar', [CategoriaController::class, 'index']);
    Route::prefix('/imposto')->group(function (){
        Route::post('/adicionar', [ImpostoController::class, 'store']);
        Route::put('/atualizar', [ImpostoController::class, 'update']);
        Route::delete('/deletar', [ImpostoController::class, 'destroy']);
        Route::get('/listar', [ImpostoController::class, 'index']);
        Route::prefix('/aliquota')->group(function (){
            Route::post('/adicionar', [AliquotaController::class, 'store']);
            Route::put('/atualizar', [AliquotaController::class, 'update']);
            Route::delete('/deletar', [AliquotaController::class, 'deletar_aliquota']);
            Route::get('/listar', [AliquotaController::class, 'index']);
        });
    });
});
