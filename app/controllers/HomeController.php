<?php

class HomeController
{
    public function index()
    {
        // Si el usuario ya está autenticado, redirigir al dashboard
        if (isAuthenticated()) {
            redirect('/dashboard');
            return;
        }
        
        // Mostrar página de inicio
        require_once APP_PATH . '/views/home/index.php';
    }
}
