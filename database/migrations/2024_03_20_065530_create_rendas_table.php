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
        Schema::create('rendas', function (Blueprint $blueprint): void {
            $blueprint->bigIncrements('id');
            $blueprint->foreignId('user_id');
            $blueprint->string('saldo');
            $blueprint->string('custo')->default(0);
            $blueprint->softDeletes();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rendas');
    }
};
