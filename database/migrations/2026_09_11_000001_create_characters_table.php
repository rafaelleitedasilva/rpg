<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('race');
            $table->string('class');
            $table->unsignedTinyInteger('level')->default(1);
            $table->string('background')->nullable();
            $table->string('alignment')->nullable();
            $table->unsignedInteger('experience')->default(0);
            $table->unsignedTinyInteger('strength')->default(10);
            $table->unsignedTinyInteger('dexterity')->default(10);
            $table->unsignedTinyInteger('constitution')->default(10);
            $table->unsignedTinyInteger('intelligence')->default(10);
            $table->unsignedTinyInteger('wisdom')->default(10);
            $table->unsignedTinyInteger('charisma')->default(10);
            $table->unsignedTinyInteger('proficiency_bonus')->default(2);
            $table->unsignedTinyInteger('initiative')->default(0);
            $table->unsignedTinyInteger('armor_class')->default(10);
            $table->unsignedTinyInteger('speed')->default(30);
            $table->unsignedTinyInteger('current_hp')->default(8);
            $table->unsignedTinyInteger('max_hp')->default(8);
            $table->unsignedTinyInteger('temp_hp')->default(0);
            $table->unsignedTinyInteger('hit_dice')->default(8);
            $table->boolean('inspiration')->default(false);
            $table->unsignedTinyInteger('passive_perception')->default(10);
            $table->unsignedTinyInteger('spell_save_dc')->default(8);
            $table->unsignedTinyInteger('spell_attack_bonus')->default(0);
            $table->json('spells')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
