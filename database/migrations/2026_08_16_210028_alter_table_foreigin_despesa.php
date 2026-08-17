<?php

use App\Models\Plano;
use App\Models\StatusDespesa;
use App\Models\TipoDespesa;
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
        Schema::table('despesas', function (Blueprint $table) {
            $table->foreignIdFor(StatusDespesa::class)
            ->index()
            ->constrained()
            ->cascadeOnDelete();
        $table->foreignIdFor(TipoDespesa::class)
            ->index()
            ->constrained()
            ->cascadeOnDelete();
        $table->foreignIdFor(Plano::class)
            ->index()
            ->constrained()
            ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('despesas', function (Blueprint $table) {
            $table->dropColumn(StatusDespesa::class);
            $table->dropColumn(TipoDespesa::class);
            $table->foreignIdFor(Plano::class);
        });
    }
};
