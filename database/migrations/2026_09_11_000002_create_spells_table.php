<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spells', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedTinyInteger('level')->default(0);
            $table->string('school');
            $table->string('casting_time');
            $table->string('range');
            $table->string('components');
            $table->string('duration');
            $table->boolean('concentration')->default(false);
            $table->boolean('ritual')->default(false);
            $table->text('description');
            $table->json('classes');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spells');
    }
};
