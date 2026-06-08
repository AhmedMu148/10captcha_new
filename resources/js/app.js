import './bootstrap';

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

window.Livewire = Livewire;
window.Alpine = Alpine;

Alpine.store('auth', {
    modal: false,
    tab: 'login',
    open(tab) {
        this.tab = tab || 'login';
        this.modal = true;
    },
    close() {
        this.modal = false;
    }
});

Livewire.start();
