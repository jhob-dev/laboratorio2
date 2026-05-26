<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Paciente.php';
require_once __DIR__ . '/../models/Examen.php';

class PacienteController extends Controller
{
    private $pacienteModel;
    private $examenModel;

    public function __construct()
    {
        parent::__construct();
        $this->pacienteModel = new Paciente();
        $this->examenModel = new Examen();
    }

    /**
     * Página principal - Listado de pacientes
     */
    public function index()
    {
        // Búsqueda
        $search = $_GET['search'] ?? '';
        if (!empty($search)) {
            $pacientes = $this->pacienteModel->search($search);
        } else {
            $pacientes = $this->pacienteModel->getAllWithExams();
        }

        $this->view->renderWithLayout('pacientes/index', [
            'pacientes' => $pacientes,
            'search' => $search,
            'title' => 'Pacientes - ' . APP_NAME
        ]);
    }

    /**
     * Mostrar formulario de creación de paciente
     */
    public function crear()
    {
        $this->view->renderWithLayout('pacientes/crear', [
            'title' => 'Nuevo Paciente - ' . APP_NAME
        ]);
    }

    /**
     * Procesar creación de paciente
     */
    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('pacientes/crear');
        }

        $nombre = trim($_POST['nombre_completo'] ?? '');
        $cedula = trim($_POST['cedula_identidad'] ?? '');
        $fechaNacimiento = $_POST['fecha_nacimiento'] ?? '';
        $genero = $_POST['genero'] ?? '';
        $direccion = trim($_POST['direccion'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $email = trim($_POST['email'] ?? '');

        // Validaciones
        $errores = [];

        if (empty($nombre)) $errores[] = 'El nombre completo es obligatorio.';
        if (empty($cedula)) $errores[] = 'La cédula de identidad es obligatoria.';
        if (empty($fechaNacimiento)) $errores[] = 'La fecha de nacimiento es obligatoria.';

        // Verificar cédula duplicada
        $existente = $this->pacienteModel->findByCedula($cedula);
        if ($existente) {
            $errores[] = 'Ya existe un paciente registrado con esa cédula de identidad.';
        }

        // Calcular edad
        $edad = $this->calcularEdad($fechaNacimiento);

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['old_input'] = $_POST;
            $this->redirect('pacientes/crear');
        }

        $data = [
            'nombre_completo'  => $nombre,
            'cedula_identidad' => $cedula,
            'fecha_nacimiento' => $fechaNacimiento,
            'edad'             => $edad,
            'genero'           => $genero,
            'direccion'        => $direccion,
            'telefono'         => $telefono,
            'email'            => $email
        ];

        $pacienteId = $this->pacienteModel->create($data);

        $_SESSION['mensaje'] = 'Paciente registrado exitosamente.';
        $this->redirect("pacientes/ver/{$pacienteId}");
    }

    /**
     * Ver detalle de un paciente y sus exámenes
     */
    public function ver($id = null)
    {
        $id = $id ?? $_GET['id'] ?? 0;
        $paciente = $this->pacienteModel->find($id);

        if (!$paciente) {
            $_SESSION['error'] = 'Paciente no encontrado.';
            $this->redirect('pacientes');
        }

        $examenesPaciente = $this->examenModel->getExamenesPaciente($id);

        $this->view->renderWithLayout('pacientes/ver', [
            'paciente' => $paciente,
            'examenes' => $examenesPaciente,
            'title' => $paciente['nombre_completo'] . ' - ' . APP_NAME
        ]);
    }

    /**
     * API: Calcular edad desde fecha de nacimiento
     */
    public function calcularEdadApi()
    {
        $fecha = $_GET['fecha'] ?? '';
        $edad = $this->calcularEdad($fecha);
        $this->jsonResponse(['edad' => $edad]);
    }

    /**
     * Función auxiliar para calcular edad
     */
    private function calcularEdad($fechaNacimiento)
    {
        if (empty($fechaNacimiento)) return 0;

        $nacimiento = new DateTime($fechaNacimiento);
        $hoy = new DateTime();
        $diferencia = $hoy->diff($nacimiento);
        return $diferencia->y;
    }
}
