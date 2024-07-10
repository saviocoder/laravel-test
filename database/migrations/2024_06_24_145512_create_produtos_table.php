<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->decimal('preco', 8, 2);
            $table->unsignedInteger('qtd_estoque');
            $table->unsignedBigInteger('categoria_id')->unique(); // Definição da chave estrangeira
            $table->timestamps();
        
            // Constraint da chave estrangeira
            $table->foreign('categoria_id')->references('id')->on('categorias');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
