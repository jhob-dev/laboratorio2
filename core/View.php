<?php
class View
{
    public function render($view, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . "/../views/{$view}.php";

        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("Vista no encontrada: {$viewFile}");
        }
    }

    public function renderWithLayout($view, $data = [], $layout = 'layouts/header')
    {
        // Cargar cabecera
        extract($data);
        require_once __DIR__ . "/../views/{$layout}.php";

        // Cargar contenido
        $this->render($view, $data);

        // Cargar pie
        require_once __DIR__ . "/../views/layouts/footer.php";
    }
}