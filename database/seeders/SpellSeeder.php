<?php

namespace Database\Seeders;

use App\Models\Spell;
use Illuminate\Database\Seeder;

class SpellSeeder extends Seeder
{
    public function run(): void
    {
        $spells = [
            [
                'name' => 'Fire Bolt',
                'level' => 0,
                'school' => 'Evocação',
                'casting_time' => '1 ação',
                'range' => '36 metros',
                'components' => 'V, S',
                'duration' => 'Instantânea',
                'concentration' => false,
                'ritual' => false,
                'description' => 'Você dispara uma faísca de fogo em um alvo dentro do alcance. Faça um ataque à distância com magia.',
                'classes' => ['Wizard', 'Sorcerer'],
                'races' => ['Human', 'Elf', 'Tiefling'],
            ],
            [
                'name' => 'Magic Missile',
                'level' => 1,
                'school' => 'Evocação',
                'casting_time' => '1 ação',
                'range' => '120 pés',
                'components' => 'V, S',
                'duration' => 'Instantânea',
                'concentration' => false,
                'ritual' => false,
                'description' => 'Você cria três dardos de energia forte e os atira contra criaturas de sua escolha.',
                'classes' => ['Wizard', 'Sorcerer'],
                'races' => ['Human', 'Elf', 'Dragonborn'],
            ],
            [
                'name' => 'Shield',
                'level' => 1,
                'school' => 'Abjuração',
                'casting_time' => '1 reação',
                'range' => 'Pessoal',
                'components' => 'V, S',
                'duration' => '1 rodada',
                'concentration' => false,
                'ritual' => false,
                'description' => 'Uma barreira de energia mágica surge ao seu redor, proporcionando +5 na classe de armadura contra ataques.',
                'classes' => ['Wizard'],
                'races' => ['Human', 'Elf', 'Dwarf'],
            ],
            [
                'name' => 'Bless',
                'level' => 1,
                'school' => 'Encantamento',
                'casting_time' => '1 ação',
                'range' => '18 metros',
                'components' => 'V, S, M',
                'duration' => 'Concentração, até 1 minuto',
                'concentration' => true,
                'ritual' => false,
                'description' => 'Você concede a seres escolhidos uma bênção que aumenta seus testes de ataque e salvaguarda.',
                'classes' => ['Cleric', 'Paladin'],
                'races' => ['Human', 'Half-Elf', 'Dwarf'],
            ],
            [
                'name' => 'Cure Wounds',
                'level' => 1,
                'school' => 'Evocação',
                'casting_time' => '1 ação',
                'range' => 'Toque',
                'components' => 'V, S',
                'duration' => 'Instantânea',
                'concentration' => false,
                'ritual' => false,
                'description' => 'Você toca uma criatura e restaura uma quantidade de pontos de vida equivalente a 1d8 + seu modificador de habilidade.',
                'classes' => ['Cleric', 'Druid', 'Bard'],
                'races' => ['Human', 'Elf', 'Half-Elf'],
            ],
            [
                'name' => 'Detect Magic',
                'level' => 1,
                'school' => 'Adivinhação',
                'casting_time' => '1 ação',
                'range' => 'Pessoal',
                'components' => 'V, S',
                'duration' => 'Concentração, até 10 minutos',
                'concentration' => true,
                'ritual' => true,
                'description' => 'Você sente a presença de magia em um raio ao redor, revelando auras e fontes.',
                'classes' => ['Wizard', 'Cleric', 'Druid'],
                'races' => ['Elf', 'Human', 'Dwarf'],
            ],
        ];

        foreach ($spells as $spell) {
            Spell::query()->updateOrCreate(
                ['name' => $spell['name']],
                $spell
            );
        }
    }
}
