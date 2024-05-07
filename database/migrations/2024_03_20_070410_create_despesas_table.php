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
        Schema::create('despesas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('descricao');
            $table->string('data_vencimento');
            $table->foreignId('status_despesa_id');
            $table->foreignId('tipo_despesa_id');
            $table->foreignId('plano_id');
            $table->string('valor_documento');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('despesas');
    }
};
