import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

/**
 * Client-side search + filters for the character sheets dashboard.
 * The characters themselves are rendered server-side (Blade); this only
 * decides which of the already-rendered cards stay visible.
 */
Alpine.data('characterDirectory', (characters) => ({
    characters,
    search: '',
    campaign: 'all',
    klass: 'all',
    race: 'all',
    level: 'all',
    filtersOpen: false,

    get filteredIds() {
        const q = this.search.trim().toLowerCase();

        return this.characters
            .filter((c) => {
                if (this.campaign !== 'all' && (c.campaign ?? '') !== this.campaign) return false;
                if (this.klass !== 'all' && c.class !== this.klass) return false;
                if (this.race !== 'all' && c.race !== this.race) return false;
                if (this.level !== 'all' && String(c.level) !== this.level) return false;

                if (!q) return true;

                return [c.name, c.race, c.class, c.campaign]
                    .filter(Boolean)
                    .some((value) => value.toLowerCase().includes(q));
            })
            .map((c) => c.id);
    },

    get hasActiveFilters() {
        return this.search !== '' || this.campaign !== 'all' || this.klass !== 'all' || this.race !== 'all' || this.level !== 'all';
    },

    get resultsLabel() {
        const total = this.characters.length;
        const visible = this.filteredIds.length;

        if (!this.hasActiveFilters) {
            return total === 1 ? '1 personagem' : `${total} personagens`;
        }

        return `${visible} de ${total} ${total === 1 ? 'personagem' : 'personagens'}`;
    },

    isVisible(id) {
        return this.filteredIds.includes(id);
    },

    reset() {
        this.search = '';
        this.campaign = 'all';
        this.klass = 'all';
        this.race = 'all';
        this.level = 'all';
    },
}));

Alpine.start();
