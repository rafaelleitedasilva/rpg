<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
            $table->foreignId('map_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('combat_scene_id')->nullable()->constrained('combat_scenes')->nullOnDelete();
            $table->foreignId('monster_template_id')->nullable()->constrained('monster_templates')->nullOnDelete();
            $table->string('name');
            $table->string('type')->default('monster');
            $table->unsignedInteger('x')->default(0);
            $table->unsignedInteger('y')->default(0);
            $table->integer('initiative')->default(0);
            $table->unsignedInteger('hp')->default(0);
            $table->unsignedInteger('max_hp')->default(0);
            $table->string('color')->nullable();
            $table->json('details')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tokens');
    }
};
