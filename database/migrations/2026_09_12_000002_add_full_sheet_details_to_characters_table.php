<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('characters', function (Blueprint $table) {
            // Identificação
            $table->string('player_name')->nullable()->after('name');
            $table->string('master_name')->nullable()->after('player_name');
            $table->string('campaign_name')->nullable()->after('master_name');
            $table->string('deity')->nullable()->after('alignment');
            $table->string('size')->default('Médio')->after('deity');

            // Aparência
            $table->string('age')->nullable()->after('size');
            $table->string('height')->nullable()->after('age');
            $table->string('weight')->nullable()->after('height');
            $table->string('eyes')->nullable()->after('weight');
            $table->string('skin')->nullable()->after('eyes');
            $table->string('hair')->nullable()->after('skin');
            $table->string('portrait_url')->nullable()->after('hair');

            // Testes de resistência e perícias
            $table->json('saving_throw_proficiencies')->nullable()->after('proficiencies');
            $table->json('skill_proficiencies')->nullable()->after('saving_throw_proficiencies');
            $table->json('skill_expertise')->nullable()->after('skill_proficiencies');

            // Proficiências específicas e idiomas
            $table->json('armor_proficiencies')->nullable()->after('skill_expertise');
            $table->json('weapon_proficiencies')->nullable()->after('armor_proficiencies');
            $table->json('tool_proficiencies')->nullable()->after('weapon_proficiencies');
            $table->json('languages')->nullable()->after('tool_proficiencies');

            // Equipamento e tesouro
            $table->json('equipment')->nullable()->after('languages');
            $table->unsignedInteger('coins_cp')->default(0)->after('equipment');
            $table->unsignedInteger('coins_sp')->default(0)->after('coins_cp');
            $table->unsignedInteger('coins_ep')->default(0)->after('coins_sp');
            $table->unsignedInteger('coins_gp')->default(0)->after('coins_ep');
            $table->unsignedInteger('coins_pp')->default(0)->after('coins_gp');
            $table->text('treasure')->nullable()->after('coins_pp');

            // Ataques
            $table->json('attacks')->nullable()->after('treasure');

            // Testes de morte
            $table->unsignedTinyInteger('death_save_successes')->default(0)->after('attacks');
            $table->unsignedTinyInteger('death_save_failures')->default(0)->after('death_save_successes');

            // Conjuração
            $table->string('spellcasting_ability')->nullable()->after('spell_attack_bonus');
            $table->json('cantrips')->nullable()->after('spells');

            // Antecedente e narrativa
            $table->text('allies_organizations')->nullable()->after('cantrips');
            $table->text('backstory')->nullable()->after('allies_organizations');
        });
    }

    public function down(): void
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->dropColumn([
                'player_name',
                'master_name',
                'campaign_name',
                'deity',
                'size',
                'age',
                'height',
                'weight',
                'eyes',
                'skin',
                'hair',
                'portrait_url',
                'saving_throw_proficiencies',
                'skill_proficiencies',
                'skill_expertise',
                'armor_proficiencies',
                'weapon_proficiencies',
                'tool_proficiencies',
                'languages',
                'equipment',
                'coins_cp',
                'coins_sp',
                'coins_ep',
                'coins_gp',
                'coins_pp',
                'treasure',
                'attacks',
                'death_save_successes',
                'death_save_failures',
                'spellcasting_ability',
                'cantrips',
                'allies_organizations',
                'backstory',
            ]);
        });
    }
};
