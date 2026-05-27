<?php
require_once __DIR__ . '/../core/Model.php';

class Examen extends Model
{
    protected $table = 'examenes';

    /**
     * Obtener exámenes activos agrupados por categoría
     */
    public function getActivosPorCategoria()
    {
        $sql = "SELECT * FROM {$this->table} WHERE activo = 1 ORDER BY categoria, nombre";
        $stmt = $this->db->query($sql);
        $resultados = $stmt->fetchAll();

        $agrupado = [];
        foreach ($resultados as $examen) {
            $categoria = $examen['categoria'] ?? 'Sin Categoría';
            $agrupado[$categoria][] = $examen;
        }
        return $agrupado;
    }

    /**
     * Obtener exámenes de un paciente específico
     */
    public function getExamenesPaciente($pacienteId)
    {
        $sql = "SELECT pe.*, e.nombre AS nombre_examen, e.codigo, e.categoria,
                       r.id AS resultado_id, r.resultado_texto, r.observaciones
                FROM paciente_examenes pe
                INNER JOIN examenes e ON pe.examen_id = e.id
                LEFT JOIN resultados r ON pe.id = r.paciente_examen_id
                WHERE pe.paciente_id = ?
                ORDER BY pe.fecha_solicitud DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$pacienteId]);
        return $stmt->fetchAll();
    }
    // ==================================
    // CREAR EXAMEN DESDE EL FRONT-END
    // ==================================
    public function crearExamen($data) {
    $sql = "INSERT INTO examenes (codigo, nombre, descripcion, precio, categoria, activo) VALUES (?, ?, ?, ?, ?, 1)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$data['codigo'], $data['nombre'], $data['descripcion'] ?? '', $data['precio'], $data['categoria'] ?? 'General']);
    return $this->db->lastInsertId();
}
}