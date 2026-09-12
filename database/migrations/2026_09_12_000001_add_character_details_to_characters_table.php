<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->string('secondary_class')->nullable()->after('class');
            $table->unsignedTinyInteger('secondary_class_level')->nullable()->after('secondary_class');
            $table->json('personality_traits')->nullable()->after('spells');
            $table->json('ideals')->nullable()->after('personality_traits');
            $table->json('bonds')->nullable()->after('ideals');
            $table->json('flaws')->nullable()->after('bonds');
            $table->json('features')->nullable()->after('flaws');
            $table->json('proficiencies')->nullable()->after('features');
        });
    }

    public function down(): void
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->dropColumn([
                'secondary_class',
                'secondary_class_level',
                'personality_traits',
                'ideals',
                'bonds',
                'flaws',
                'features',
                'proficiencies',
            ]);
        });
    }
};
