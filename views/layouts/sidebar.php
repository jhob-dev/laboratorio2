<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <i class="fas fa-flask"></i>
            <span>LabClínico</span>
        </div>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="<?php echo APP_URL; ?>pacientes" class="<?php echo strpos($_SERVER['REQUEST_URI'], '/pacientes') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i>
                    <span>Pacientes</span>
                </a>
            </li>
            <li>
                <a href="<?php echo APP_URL; ?>pacientes/crear">
                    <i class="fas fa-user-plus"></i>
                    <span>Nuevo Paciente</span>
                </a>
            </li>
            <li class="sidebar-divider"></li>
            <li>
                <a href="#" id="btnExamenesRapidos">
                    <i class="fas fa-vial"></i>
                    <span>Exámenes Rápidos</span>
                </a>
            </li>
            <li class="sidebar-divider"></li>
            <li>
                <a href="#" id="btnNuevoExamen">
                    <i class="fas fa-plus-circle"></i>
                    <span>Nuevo Examen</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>