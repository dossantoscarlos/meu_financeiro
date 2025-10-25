<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('despesas', function (Blueprint $blueprint): void {
            $blueprint->bigIncrements('id');
            $blueprint->string('descricao');
            $blueprint->string('data_vencimento');
            $blueprint->foreignId('status_despesa_id');
            $blueprint->foreignId('tipo_despesa_id');
            $blueprint->foreignId('plano_id');
            $blueprint->string('valor_documento');
            $blueprint->softDeletes();
            $blueprint->timestamps();
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
