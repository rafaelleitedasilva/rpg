<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    /**
     * @param  string|null  $eyebrow  Small uppercase label above the left panel heading (e.g. "A Taverna").
     * @param  string  $heading  Main display heading of the left panel.
     * @param  string|null  $subheading  Secondary heading under the main one.
     * @param  string|null  $description  Paragraph text under the heading(s).
     * @param  string|null  $image  Public path (relative to public/) for the left panel background photo.
     *                              Left empty until the real artwork is provided; a placeholder is shown instead.
     */
    public function __construct(
        public ?string $eyebrow = null,
        public string $heading = 'Guildhall',
        public ?string $subheading = null,
        public ?string $description = null,
        public ?string $image = null,
    ) {}

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.guest');
    }
}
