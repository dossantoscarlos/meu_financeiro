<?php

declare(strict_types=1);

use App\Models\Plano;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gastos', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->foreignIdFor(Plano::class)
                ->index()
                ->constrained()
                ->cascadeOnDelete();
            $blueprint->string('valor');
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gastos');
    }
};
