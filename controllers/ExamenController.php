<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Examen.php';
require_once __DIR__ . '/../models/Resultado.php';

class ExamenController extends Controller
{
    private $examenModel;
    private $resultadoModel;

    public function __construct()
    {
        parent::__construct();
        $this->examenModel = new Examen();
        $this->resultadoModel = new Resultado();
    }

    /**
     * API: Obtener lista de exámenes para el modal
     */
    public function listarExamenes()
    {
        $examenes = $this->examenModel->getActivosPorCategoria();
        $this->jsonResponse(['examenes' => $examenes]);
    }

    /**
     * Procesar la realización de un examen (crear orden)
     */
    public function realizarExamen()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Método no permitido'], 405);
        }

        $pacienteId = $_POST['paciente_id'] ?? 0;
        $examenId = $_POST['examen_id'] ?? 0;
        $metodoPago = $_POST['metodo_pago'] ?? '';
        $referenciaPago = $_POST['referencia_pago'] ?? null;

        // Validar método de pago
        $metodosValidos = ['Efectivo', 'Pago Móvil', 'Transferencia', 'Punto de Venta'];
        if (!in_array($metodoPago, $metodosValidos)) {
            $this->jsonResponse(['error' => 'Método de pago no válido.'], 400);
        }

        // Si es Pago Móvil o Transferencia, exigir los últimos 4 dígitos
        if (in_array($metodoPago, ['Pago Móvil', 'Transferencia'])) {
            if (empty($referenciaPago) || strlen($referenciaPago) < 4) {
                $this->jsonResponse(['error' => 'Debe proporcionar los últimos 4 dígitos de la transacción.'], 400);
            }
            $referenciaPago = substr($referenciaPago, -4); // Solo últimos 4 dígitos
        }

        // Efectivo y Punto de Venta: ejecutan el procedimiento directamente
        $ordenId = $this->resultadoModel->crearOrden([
            'paciente_id'     => $pacienteId,
            'examen_id'       => $examenId,
            'metodo_pago'     => $metodoPago,
            'referencia_pago' => $referenciaPago
        ]);

        $this->jsonResponse([
            'success'  => true,
            'mensaje'  => 'Examen registrado exitosamente.',
            'orden_id' => $ordenId
        ]);
    }

    /**
     * API: Obtener datos del examen para el modal de resultados
     */
    public function obtenerExamen()
    {
        $ordenId = $_GET['orden_id'] ?? 0;
        $datos = $this->resultadoModel->getDatosReporte($ordenId);
        $this->jsonResponse(['examen' => $datos]);
    }

    /**
     * Guardar resultados de un examen
     */
    public function guardarResultados()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Método no permitido'], 405);
        }

        $ordenId = $_POST['orden_id'] ?? 0;
        $resultadoTexto = $_POST['resultado_texto'] ?? '';
        $observaciones = $_POST['observaciones'] ?? '';

        if (empty($resultadoTexto)) {
            $this->jsonResponse(['error' => 'Los resultados son obligatorios.'], 400);
        }

        $this->resultadoModel->guardarResultado($ordenId, $resultadoTexto, $observaciones);

        $this->jsonResponse([
            'success' => true,
            'mensaje' => 'Resultados guardados exitosamente.'
        ]);
    }

    public function crearExamen() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->jsonResponse(['error' => 'Método no permitido'], 405);
    }

    $codigo = trim($_POST['codigo'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $precio = floatval($_POST['precio'] ?? 0);
    $categoria = trim($_POST['categoria'] ?? 'General');
    $descripcion = trim($_POST['descripcion'] ?? '');

    // Validaciones básicas
    if (empty($codigo) || empty($nombre) || $precio <= 0) {
        $this->jsonResponse(['error' => 'Código, nombre y precio son obligatorios.'], 400);
    }

    $data = [
        'codigo' => $codigo,
        'nombre' => $nombre,
        'descripcion' => $descripcion,
        'precio' => $precio,
        'categoria' => $categoria
    ];

    $id = $this->examenModel->crearExamen($data);
    $this->jsonResponse(['success' => true, 'mensaje' => 'Examen creado exitosamente.', 'id' => $id]);
}
}