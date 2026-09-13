<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Isolated app shell shared by the character sheet screens (create/edit/show)
 * and the account settings screens (profile).
 *
 * It intentionally does not reuse `layouts.navigation` or the tavern design
 * tokens from `resources/css/app.css` — it renders its own header and relies
 * on the `.gh-sheet`-scoped styles so the rest of the app is unaffected.
 */
class CharacterSheetLayout extends Component
{
    public function render(): View
    {
        return view('layouts.character-sheet');
    }
}
