<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('planos', function (Blueprint $blueprint): void {
            $blueprint->bigIncrements('id');
            $blueprint->string('descricao_simples');
            $blueprint->string('mes_ano');
            $blueprint->foreignIdFor(User::class)
                ->index()
                ->constrained()
                ->cascadeOnDelete();
            $blueprint->softDeletes();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planos');
    }
};
