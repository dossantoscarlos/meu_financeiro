<?php

declare(strict_types=1);

use App\Models\Despesa;
use App\Models\StatusDespesa;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('historico_despesas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Despesa::class);
            $table->foreignIdFor(StatusDespesa::class);
            $table->date('data')->default(now()->format('Y-m-d'));
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historico_despesas');
    }
};
