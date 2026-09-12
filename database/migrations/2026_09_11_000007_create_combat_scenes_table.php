<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('combat_scenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
            $table->foreignId('map_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('status')->default('ativo');
            $table->string('ruleset')->default('D&D 5e');
            $table->unsignedInteger('round')->default(1);
            $table->unsignedInteger('turn')->default(1);
            $table->text('initiative_order')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('combat_scenes');
    }
};
