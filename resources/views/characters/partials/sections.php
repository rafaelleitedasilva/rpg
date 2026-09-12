<?php

// Single source of truth for the character sheet sections, shared by the
// sidebar navigation (create/edit views) and the in-form "Voltar/Próximo"
// footer navigation (partials/form.blade.php).
return [
    'identity' => ['label' => 'Identificação', 'icon' => 'user'],
    'abilities' => ['label' => 'Atributos & Resistências', 'icon' => 'shield'],
    'skills' => ['label' => 'Perícias', 'icon' => 'sparkles'],
    'combat' => ['label' => 'Combate', 'icon' => 'sword'],
    'gear' => ['label' => 'Proficiências & Equipamento', 'icon' => 'bag'],
    'spellcasting' => ['label' => 'Conjuração', 'icon' => 'book'],
    'story' => ['label' => 'História', 'icon' => 'scroll'],
];
