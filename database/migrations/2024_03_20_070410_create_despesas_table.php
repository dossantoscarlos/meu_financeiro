<?php

declare(strict_types=1);

use App\Models\Plano;
use App\Models\StatusDespesa;
use App\Models\TipoDespesa;
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
            $blueprint->foreignIdFor(StatusDespesa::class)
                ->index()
                ->constrained()
                ->cascadeOnDelete();
            $blueprint->foreignIdFor(TipoDespesa::class)
                ->index()
                ->constrained()
                ->cascadeOnDelete();
            $blueprint->foreignIdFor(Plano::class)
                ->index()
                ->constrained()
                ->cascadeOnDelete();
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
