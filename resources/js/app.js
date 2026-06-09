import './bootstrap';

import Alpine from 'alpinejs';

import { createIcons, icons } from 'lucide';

document.addEventListener("DOMContentLoaded", () => {
    createIcons({ icons });
});

window.Alpine = Alpine;

Alpine.start();
