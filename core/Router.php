<?php
class Router
{
    private $routes = [];

    public function add($route, $controller, $action)
    {
        $this->routes[$route] = ['controller' => $controller, 'action' => $action];
    }

    public function dispatch($url)
    {
        // Remover query strings
        $url = strtok($url, '?');
        $url = rtrim($url, '/');
        if ($url === '') $url = '/';

        if (array_key_exists($url, $this->routes)) {
            $controllerName = $this->routes[$url]['controller'];
            $action = $this->routes[$url]['action'];

            require_once __DIR__ . "/../controllers/{$controllerName}.php";
            $controller = new $controllerName();
            $controller->$action();
        } else {
            // Ruta no encontrada
            header("HTTP/1.0 404 Not Found");
            echo "<h1>404 - Página no encontrada</h1>";
            echo "<p>La ruta solicitada <strong>{$url}</strong> no existe.</p>";
            echo '<p><a href="' . APP_URL . '">Volver al inicio</a></p>';
        }
    }
}