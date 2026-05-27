<?php
/**
 * LABCLÍNICO - Sistema de Exámenes Clínicos
 * Front Controller - Punto de entrada único
 */

session_start();

// Cargar configuraciones
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';

// Cargar núcleo MVC
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/View.php';

// Inicializar el Router
$router = new Router();

// ============================================
// DEFINICIÓN DE RUTAS
// ============================================

// Ruta raíz - redirige a pacientes
$router->add('/', 'PacienteController', 'index');

// Pacientes
$router->add('/pacientes', 'PacienteController', 'index');
$router->add('/pacientes/crear', 'PacienteController', 'crear');
$router->add('/pacientes/guardar', 'PacienteController', 'guardar');
$router->add('/pacientes/calcular-edad', 'PacienteController', 'calcularEdadApi');

// Rutas con parámetros - se manejan en el controlador
// /pacientes/ver/{id}

// Exámenes (API)
$router->add('/examenes/listar', 'ExamenController', 'listarExamenes');
$router->add('/examenes/realizar', 'ExamenController', 'realizarExamen');
$router->add('/examenes/obtener', 'ExamenController', 'obtenerExamen');
$router->add('/examenes/guardar-resultados', 'ExamenController', 'guardarResultados');
$router->add('/examenes/crear', 'ExamenController', 'crearExamen');

// Reportes
// /reportes/imprimir/{id}

// ============================================
// DESPACHAR LA PETICIÓN
// ============================================

$requestUri = $_SERVER['REQUEST_URI'];

// Remover el prefijo de la aplicación
$basePath = '/laboratorio_clinico';
$requestUri = str_replace($basePath, '', $requestUri);

// // Manejar rutas con parámetros
// // /pacientes/ver/123 -> PacienteController::ver(123)
// if (preg_match('#^/pacientes/ver/(\d+)$#', $requestUri, $matches)) {
//     require_once __DIR__ . '/controllers/PacienteController.php';
//     $controller = new PacienteController();
//     $controller->ver($matches[1]);
//     exit;
// }

// // /reportes/imprimir/123 -> ReporteController::imprimir(123)
// if (preg_match('#^/reportes/imprimir/(\d+)$#', $requestUri, $matches)) {
//     require_once __DIR__ . '/controllers/ReporteController.php';
//     $controller = new ReporteController();
//     $controller->imprimir($matches[1]);
//     exit;
// }

// Rutas con parámetros (MODIFICADO para mayor robustez)
$requestUriWithoutQuery = strtok($requestUri, '?');
if (preg_match('#^/pacientes/ver/(\d+)/?$#', $requestUriWithoutQuery, $matches)) {
    require_once __DIR__ . '/controllers/PacienteController.php';
    $controller = new PacienteController();
    $controller->ver($matches[1]);
    exit;
}
if (preg_match('#^/reportes/imprimir/(\d+)/?$#', $requestUriWithoutQuery, $matches)) {
    require_once __DIR__ . '/controllers/ReporteController.php';
    $controller = new ReporteController();
    $controller->imprimir($matches[1]);
    exit;
}

// Despachar rutas estáticas
$router->dispatch($requestUri);