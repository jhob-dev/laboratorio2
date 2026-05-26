<div class="page-header">
    <h2><i class="fas fa-user-plus"></i> Registrar Nuevo Paciente</h2>
    <a href="<?php echo APP_URL; ?>pacientes" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form id="formPaciente" method="POST" action="<?php echo APP_URL; ?>pacientes/guardar" class="form-paciente">
            <div class="form-row">
                <div class="form-group full-width">
                    <label for="nombre_completo">
                        <i class="fas fa-user"></i> Nombre Completo <span class="required">*</span>
                    </label>
                    <input type="text" id="nombre_completo" name="nombre_completo"
                           class="form-control" placeholder="Ej: Juan Carlos Pérez Rodríguez"
                           value="<?php echo htmlspecialchars($_SESSION['old_input']['nombre_completo'] ?? ''); ?>"
                           required autofocus>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="cedula_identidad">
                        <i class="fas fa-id-card"></i> Cédula de Identidad <span class="required">*</span>
                    </label>
                    <input type="text" id="cedula_identidad" name="cedula_identidad"
                           class="form-control" placeholder="Ej: V-12345678"
                           value="<?php echo htmlspecialchars($_SESSION['old_input']['cedula_identidad'] ?? ''); ?>"
                           required>
                </div>
                <div class="form-group">
                    <label for="fecha_nacimiento">
                        <i class="fas fa-calendar"></i> Fecha de Nacimiento <span class="required">*</span>
                    </label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                           class="form-control"
                           value="<?php echo $_SESSION['old_input']['fecha_nacimiento'] ?? ''; ?>"
                           required onchange="calcularEdad()">
                </div>
                <div class="form-group">
                    <label for="edad">
                        <i class="fas fa-clock"></i> Edad
                    </label>
                    <input type="text" id="edad" class="form-control" readonly
                           placeholder="Se calcula automáticamente">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="genero">
                        <i class="fas fa-venus-mars"></i> Género <span class="required">*</span>
                    </label>
                    <select id="genero" name="genero" class="form-control" required>
                        <option value="">Seleccione...</option>
                        <option value="Masculino" <?php echo ($_SESSION['old_input']['genero'] ?? '') === 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                        <option value="Femenino" <?php echo ($_SESSION['old_input']['genero'] ?? '') === 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
                        <option value="Otro" <?php echo ($_SESSION['old_input']['genero'] ?? '') === 'Otro' ? 'selected' : ''; ?>>Otro</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="telefono">
                        <i class="fas fa-phone"></i> Teléfono
                    </label>
                    <input type="tel" id="telefono" name="telefono"
                           class="form-control" placeholder="Ej: 0412-1234567"
                           value="<?php echo htmlspecialchars($_SESSION['old_input']['telefono'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Correo Electrónico
                    </label>
                    <input type="email" id="email" name="email"
                           class="form-control" placeholder="Ej: paciente@correo.com"
                           value="<?php echo htmlspecialchars($_SESSION['old_input']['email'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group full-width">
                    <label for="direccion">
                        <i class="fas fa-map-marker-alt"></i> Dirección <span class="required">*</span>
                    </label>
                    <textarea id="direccion" name="direccion" class="form-control"
                              rows="3" placeholder="Ingrese la dirección completa"
                              required><?php echo htmlspecialchars($_SESSION['old_input']['direccion'] ?? ''); ?></textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-save"></i> Registrar Paciente
                </button>
                <a href="<?php echo APP_URL; ?>pacientes" class="btn btn-outline btn-lg">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php unset($_SESSION['old_input']); ?>