import './bootstrap';
import './countdown';
import './dicom-viewer';
import Alpine from 'alpinejs';
import flatpickr from 'flatpickr';
import { Ukrainian } from 'flatpickr/dist/l10n/uk.js';
import '../css/flatpickr-tailwind.css';

window.Alpine = Alpine;

flatpickr.localize(Ukrainian);

const defaultConfig = {
    dateFormat: 'd.m.Y',
    allowInput: true,
    disableMobile: true,
};

const datetimeConfig = {
    ...defaultConfig,
    enableTime: true,
    dateFormat: 'd.m.Y H:i',
    time_24hr: true,
};

function initDatepickers() {
    document.querySelectorAll('[x-datepicker]').forEach(el => {
        if (el._flatpickr) return;

        const isDatetime = el.hasAttribute('x-datepicker.datetime');
        const config = isDatetime ? { ...datetimeConfig } : { ...defaultConfig };

        flatpickr(el, config);
    });
}

document.addEventListener('DOMContentLoaded', initDatepickers);

document.addEventListener('alpine:init', () => {
    Alpine.directive('datepicker', (el, { modifiers, expression }, { evaluate }) => {
        const isDatetime = modifiers.includes('datetime');
        const config = isDatetime ? { ...datetimeConfig } : { ...defaultConfig };

        if (expression) {
            Object.assign(config, evaluate(expression));
        }

        flatpickr(el, config);
    });
});

Alpine.start();
