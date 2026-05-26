<?php
require_once __DIR__ . '/../core/Model.php';

class Paciente extends Model
{
    protected $table = 'pacientes';

    /**
     * Buscar paciente por cédula de identidad
     */
    public function findByCedula($cedula)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE cedula_identidad = ?");
        $stmt->execute([$cedula]);
        return $stmt->fetch();
    }

    /**
     * Obtener pacientes con sus exámenes asociados
     */
    public function getAllWithExams()
    {
        $sql = "SELECT p.*, 
                       COUNT(pe.id) AS total_examenes,
                       SUM(CASE WHEN pe.estado = 'Pendiente' THEN 1 ELSE 0 END) AS pendientes,
                       SUM(CASE WHEN pe.estado = 'Completado' THEN 1 ELSE 0 END) AS completados
                FROM {$this->table} p
                LEFT JOIN paciente_examenes pe ON p.id = pe.paciente_id
                GROUP BY p.id
                ORDER BY p.created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Buscar pacientes por nombre o cédula
     */
    public function search($term)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} 
             WHERE nombre_completo LIKE ? OR cedula_identidad LIKE ?
             ORDER BY nombre_completo ASC"
        );
        $searchTerm = "%{$term}%";
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }
}