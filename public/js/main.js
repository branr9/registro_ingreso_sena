/**
 * Funcionalidad JavaScript Principal
 */

document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar en móvil
    const mobileToggle = document.getElementById('mobileToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    
    if (mobileToggle && sidebar) {
        mobileToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }

    // Cerrar sidebar al hacer click fuera de él (móvil)
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 1024) {
            if (sidebar && !sidebar.contains(e.target) && e.target !== mobileToggle) {
                sidebar.classList.remove('active');
            }
        }
    });

    // Auto-ocultar mensajes flash después de 5 segundos
    const flashMessages = document.querySelectorAll('.flash-message');
    if (flashMessages.length > 0) {
        flashMessages.forEach(message => {
            setTimeout(() => {
                message.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                message.style.opacity = '0';
                message.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    message.remove();
                }, 500);
            }, 5000);
        });
    }

    // Validación del formulario de login
    const loginForm = document.querySelector('.auth-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const credential = document.getElementById('credential');
            const password = document.getElementById('password');
            
            let valid = true;
            let errorMessage = '';

            // Validar que los campos no estén vacíos
            if (!credential.value.trim()) {
                valid = false;
                errorMessage = 'Por favor ingrese su usuario o email';
                credential.focus();
            } else if (!password.value) {
                valid = false;
                errorMessage = 'Por favor ingrese su contraseña';
                password.focus();
            }

            if (!valid) {
                e.preventDefault();
                alert(errorMessage);
            }
        });
    }

    // Confirmación de cierre de sesión
    const logoutLinks = document.querySelectorAll('.logout-btn');
    logoutLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('¿Está seguro que desea cerrar sesión?')) {
                e.preventDefault();
            }
        });
    });

    // Marcar enlace activo en el menú
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        const linkPath = new URL(link.href).pathname;
        if (linkPath === currentPath) {
            link.classList.add('active');
        }
    });
});

// Prevenir reenvío de formularios (F5/Refresh después de POST)
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

