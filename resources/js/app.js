import './bootstrap';
import { initDailyPage } from './daily.js';
import { initImportantPage } from './important.js';

document.addEventListener('DOMContentLoaded', () => {
    if (window.location.pathname.includes('/daily')) {
        initDailyPage();
    }
    if (window.location.pathname.includes('/important')) {
        initImportantPage();
    }
});
