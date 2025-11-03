import './bootstrap';
import { initDailyPage } from './daily.js';
import { initImportantPage } from './important.js';
import { initUserTodoPage } from './user.js'; 

document.addEventListener('DOMContentLoaded', () => {
    if (window.location.pathname.includes('/daily')) {
        initDailyPage();
    }
    if (window.location.pathname.includes('/important')) {
        initImportantPage();
    }
    if (window.location.pathname.includes('/user') || window.location.pathname.includes('/categories')) {
        initUserTodoPage();
    }
});
