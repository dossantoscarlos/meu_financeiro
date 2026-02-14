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
        Schema::create('caixinhas', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->decimal('valor_produto', 10, 2);
            $table->integer('parcelas');
            $table->decimal('valor_parcela', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caixinhas');
    }
};
