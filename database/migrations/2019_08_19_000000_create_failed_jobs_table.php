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
        Schema::create('failed_jobs', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->string('uuid')->unique();
            $blueprint->text('connection');
            $blueprint->text('queue');
            $blueprint->longText('payload');
            $blueprint->longText('exception');
            $blueprint->timestamp('failed_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('failed_jobs');
    }
};
