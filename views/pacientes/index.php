<div class="page-header">
    <div class="header-actions">
        <form method="GET" action="<?php echo APP_URL; ?>pacientes" class="search-form">
            <div class="search-input-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Buscar por nombre o cédula..."
                       value="<?php echo htmlspecialchars($search ?? ''); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Buscar</button>
            <?php if (!empty($search)): ?>
                <a href="<?php echo APP_URL; ?>pacientes" class="btn btn-outline">Limpiar</a>
            <?php endif; ?>
        </form>
        <a href="<?php echo APP_URL; ?>pacientes/crear" class="btn btn-success">
            <i class="fas fa-plus"></i> Nuevo Paciente
        </a>
    </div>
</div>

<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre Completo</th>
                <th>Cédula</th>
                <th>Edad</th>
                <th>Género</th>
                <th>Exámenes</th>
                <th>Pendientes</th>
                <th>Completados</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pacientes)): ?>
                <tr>
                    <td colspan="9" class="text-center">
                        <div class="empty-state">
                            <i class="fas fa-user-slash"></i>
                            <p>No se encontraron pacientes.</p>
                            <a href="<?php echo APP_URL; ?>pacientes/crear" class="btn btn-primary">Registrar Primer Paciente</a>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($pacientes as $paciente): ?>
                    <tr>
                        <td><?php echo $paciente['id']; ?></td>
                        <td>
                            <a href="<?php echo APP_URL; ?>pacientes/ver/<?php echo $paciente['id']; ?>" class="patient-name">
                                <?php echo htmlspecialchars($paciente['nombre_completo']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($paciente['cedula_identidad']); ?></td>
                        <td><?php echo $paciente['edad']; ?> años</td>
                        <td>
                            <span class="badge badge-info"><?php echo $paciente['genero']; ?></span>
                        </td>
                        <td><?php echo $paciente['total_examenes'] ?? 0; ?></td>
                        <td>
                            <?php if (($paciente['pendientes'] ?? 0) > 0): ?>
                                <span class="badge badge-warning"><?php echo $paciente['pendientes']; ?></span>
                            <?php else: ?>
                                <span class="badge badge-success">0</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-success"><?php echo $paciente['completados'] ?? 0; ?></span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo APP_URL; ?>pacientes/ver/<?php echo $paciente['id']; ?>"
                                   class="btn-icon" title="Ver paciente">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn-icon btn-realizar-examen"
                                        data-paciente-id="<?php echo $paciente['id']; ?>"
                                        data-paciente-nombre="<?php echo htmlspecialchars($paciente['nombre_completo']); ?>"
                                        title="Realizar examen">
                                    <i class="fas fa-vial"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>