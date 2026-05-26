<?php
require_once __DIR__ . '/View.php';

class Controller
{
    protected $view;

    public function __construct()
    {
        $this->view = new View();
    }

    protected function redirect($url)
    {
        header("Location: " . APP_URL . $url);
        exit;
    }

    protected function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}