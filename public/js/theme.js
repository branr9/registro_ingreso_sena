(function () {
    'use strict';

    var storageKey = 'sena-color-theme';
    var root = document.documentElement;

    function preferredTheme() {
        var saved = localStorage.getItem(storageKey);
        if (saved === 'dark' || saved === 'light') {
            return saved;
        }
        return window.matchMedia &&
            window.matchMedia('(prefers-color-scheme: dark)').matches
            ? 'dark'
            : 'light';
    }

    function applyTheme(theme) {
        if (theme === 'dark') {
            root.setAttribute('data-theme', 'dark');
        } else {
            root.removeAttribute('data-theme');
        }

        var button = document.getElementById('themeToggle');
        if (!button) return;

        var dark = theme === 'dark';
        button.innerHTML = '<i class="fas ' +
            (dark ? 'fa-sun' : 'fa-moon') +
            '" aria-hidden="true"></i>';
        button.setAttribute(
            'aria-label',
            dark ? 'Activar modo claro' : 'Activar modo oscuro'
        );
        button.setAttribute(
            'title',
            dark ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro'
        );
        button.setAttribute('aria-pressed', String(dark));
    }

    applyTheme(preferredTheme());

    document.addEventListener('DOMContentLoaded', function () {
        var button = document.createElement('button');
        button.type = 'button';
        button.id = 'themeToggle';
        button.className = 'theme-toggle';
        document.body.appendChild(button);

        applyTheme(root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light');

        button.addEventListener('click', function () {
            var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            localStorage.setItem(storageKey, next);
            applyTheme(next);
        });
    });
})();
