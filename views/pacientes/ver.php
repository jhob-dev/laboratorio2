<?php
// Depuración temporal (comentar o eliminar en producción)
if (!isset($paciente)) {
    die('Error: La variable $paciente no está definida. Revisa el controlador y la ruta.');
}
if (!isset($examenes)) {
    die('Error: La variable $examenes no está definida.');
}
?>
<div class="page-header">
    <h2><i class="fas fa-user"></i> Ficha del Paciente - <?php echo htmlspecialchars($paciente['nombre_completo'] ?? 'Nombre no disponible'); ?></h2>
    <!-- resto del código -->

<div class="page-header">
    <h2><i class="fas fa-user"></i> Ficha del Paciente</h2>
    <div class="header-actions">
        <button class="btn btn-primary btn-realizar-examen"
                data-paciente-id="<?php echo $paciente['id']; ?>"
                data-paciente-nombre="<?php echo htmlspecialchars($paciente['nombre_completo']); ?>">
            <i class="fas fa-vial"></i> Realizar Examen
        </button>
        <a href="<?php echo APP_URL; ?>pacientes" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<!-- Datos del paciente -->
<div class="card patient-info-card">
    <div class="card-body">
        <div class="patient-avatar">
            <div class="avatar-circle">
                <?php echo strtoupper(substr($paciente['nombre_completo'], 0, 1)); ?>
            </div>
        </div>
        <div class="patient-details">
            <h3><?php echo htmlspecialchars($paciente['nombre_completo']); ?></h3>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Cédula:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($paciente['cedula_identidad']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Edad:</span>
                    <span class="detail-value"><?php echo $paciente['edad']; ?> años</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Fecha Nac.:</span>
                    <span class="detail-value"><?php echo date('d/m/Y', strtotime($paciente['fecha_nacimiento'])); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Género:</span>
                    <span class="detail-value"><?php echo $paciente['genero']; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Dirección:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($paciente['direccion']); ?></span>
                </div>
                <?php if (!empty($paciente['telefono'])): ?>
                <div class="detail-item">
                    <span class="detail-label">Teléfono:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($paciente['telefono']); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Exámenes del paciente -->
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-flask"></i> Exámenes Realizados</h3>
    </div>
    <div class="card-body">
        <?php if (empty($examenes)): ?>
            <div class="empty-state">
                <i class="fas fa-vial"></i>
                <p>Este paciente aún no tiene exámenes registrados.</p>
                <button class="btn btn-primary btn-realizar-examen"
                        data-paciente-id="<?php echo $paciente['id']; ?>"
                        data-paciente-nombre="<?php echo htmlspecialchars($paciente['nombre_completo']); ?>">
                    Realizar Primer Examen
                </button>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th># Orden</th>
                            <th>Código</th>
                            <th>Examen</th>
                            <th>Categoría</th>
                            <th>Método de Pago</th>
                            <th>Estado</th>
                            <th>Fecha Solicitud</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($examenes as $examen): ?>
                            <tr>
                                <td><?php echo $examen['id']; ?></td>
                                <td><?php echo $examen['codigo']; ?></td>
                                <td><?php echo $examen['nombre_examen']; ?></td>
                                <td><span class="badge badge-info"><?php echo $examen['categoria']; ?></span></td>
                                <td><?php echo $examen['metodo_pago']; ?></td>
                                <td>
                                    <?php
                                    $estadoClase = match($examen['estado']) {
                                        'Pendiente' => 'badge-warning',
                                        'En Proceso' => 'badge-info',
                                        'Completado' => 'badge-success',
                                        'Entregado' => 'badge-primary',
                                        default => 'badge-secondary'
                                    };
                                    ?>
                                    <span class="badge <?php echo $estadoClase; ?>">
                                        <?php echo $examen['estado']; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($examen['fecha_solicitud'])); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <?php if ($examen['estado'] === 'Pendiente' || $examen['estado'] === 'En Proceso'): ?>
                                            <button class="btn-icon btn-agregar-resultados"
                                                    data-orden-id="<?php echo $examen['id']; ?>"
                                                    title="Agregar resultados">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        <?php endif; ?>

                                        <?php if ($examen['estado'] === 'Completado' && $examen['resultado_id']): ?>
                                            <a href="<?php echo APP_URL; ?>reportes/imprimir/<?php echo $examen['id']; ?>"
                                               class="btn-icon" target="_blank" title="Imprimir reporte">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($examen['estado'] === 'Pendiente'): ?>
                                            <span class="waiting-text">
                                                <i class="fas fa-hourglass-half"></i> En espera de resultados
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>