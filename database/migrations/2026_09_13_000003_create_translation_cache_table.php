<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('translation_cache', function (Blueprint $table) {
            $table->id();
            $table->string('hash', 32)->unique();
            $table->string('locale', 5)->default('pt');
            $table->text('source_text');
            $table->text('translated_text');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translation_cache');
    }
};
