<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Resultado.php';

class ReporteController extends Controller
{
    private $resultadoModel;

    public function __construct()
    {
        parent::__construct();
        $this->resultadoModel = new Resultado();
    }

    /**
     * Generar reporte imprimible
     */
    public function imprimir($ordenId = null)
    {
        $ordenId = $ordenId ?? $_GET['orden_id'] ?? 0;

        $datos = $this->resultadoModel->getDatosReporte($ordenId);

        if (!$datos) {
            die("No se encontraron datos para el reporte solicitado.");
        }

        // Renderizar vista de impresión (sin layout)
        $this->view->render('reportes/imprimir', [
            'datos' => $datos
        ]);
    }
}