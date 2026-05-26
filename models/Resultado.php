<?php
require_once __DIR__ . '/../core/Model.php';

class Resultado extends Model
{
    protected $table = 'resultados';

    /**
     * Crear una orden de examen para un paciente
     */
    public function crearOrden($data)
    {
        $sql = "INSERT INTO paciente_examenes (paciente_id, examen_id, metodo_pago, referencia_pago, estado)
                VALUES (?, ?, ?, ?, 'Pendiente')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['paciente_id'],
            $data['examen_id'],
            $data['metodo_pago'],
            $data['referencia_pago'] ?? null
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Guardar resultado de un examen
     */
    public function guardarResultado($pacienteExamenId, $resultadoTexto, $observaciones = null)
    {
        // Verificar si ya existe un resultado
        $stmt = $this->db->prepare("SELECT id FROM resultados WHERE paciente_examen_id = ?");
        $stmt->execute([$pacienteExamenId]);
        $existente = $stmt->fetch();

        if ($existente) {
            // Actualizar
            $sql = "UPDATE resultados SET resultado_texto = ?, observaciones = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$resultadoTexto, $observaciones, $existente['id']]);
        } else {
            // Insertar nuevo
            $sql = "INSERT INTO resultados (paciente_examen_id, resultado_texto, observaciones) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$pacienteExamenId, $resultadoTexto, $observaciones]);
        }

        // Actualizar estado a Completado
        $sql = "UPDATE paciente_examenes SET estado = 'Completado', fecha_completado = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$pacienteExamenId]);

        return true;
    }

    /**
     * Obtener datos completos para el reporte
     */
    public function getDatosReporte($pacienteExamenId)
    {
        $sql = "SELECT p.nombre_completo, p.cedula_identidad, p.fecha_nacimiento, p.edad, p.genero,
                       e.nombre AS nombre_examen, e.codigo, e.categoria,
                       pe.metodo_pago, pe.referencia_pago, pe.fecha_solicitud, pe.estado,
                       r.resultado_texto, r.observaciones, r.created_at AS fecha_resultado
                FROM paciente_examenes pe
                INNER JOIN pacientes p ON pe.paciente_id = p.id
                INNER JOIN examenes e ON pe.examen_id = e.id
                LEFT JOIN resultados r ON pe.id = r.paciente_examen_id
                WHERE pe.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$pacienteExamenId]);
        return $stmt->fetch();
    }
}