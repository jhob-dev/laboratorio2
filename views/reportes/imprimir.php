<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Examen - <?php echo htmlspecialchars($datos['nombre_completo']); ?></title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>public/css/print.css">
    <style>
        /* Estilos inline para impresión */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', 'Arial', sans-serif;
            font-size: 12pt;
            color: #000;
            background: #fff;
            padding: 20px;
        }
        .report-container {
            max-width: 210mm;
            margin: 0 auto;
            border: 2px solid #1a3a5c;
            border-radius: 8px;
            overflow: hidden;
        }
        .report-header {
            background: linear-gradient(135deg, #1a3a5c, #2c5f8a);
            color: #fff;
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .report-header h1 {
            font-size: 20pt;
            font-weight: 700;
        }
        .report-header .logo {
            font-size: 14pt;
            text-align: right;
        }
        .report-body { padding: 30px; }
        .report-section { margin-bottom: 25px; }
        .report-section h3 {
            font-size: 13pt;
            color: #1a3a5c;
            border-bottom: 2px solid #1a3a5c;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .info-item { margin-bottom: 8px; }
        .info-label { font-weight: 600; color: #555; }
        .info-value { color: #000; }
        .result-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 20px;
            margin-top: 15px;
            min-height: 100px;
            white-space: pre-wrap;
            font-family: 'Courier New', monospace;
            font-size: 11pt;
        }
        .report-footer {
            background: #f0f4f8;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            font-size: 9pt;
            color: #666;
            border-top: 1px solid #dee2e6;
        }
        .firma-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .firma-linea {
            width: 200px;
            border-top: 1px solid #000;
            text-align: center;
            padding-top: 5px;
            font-size: 10pt;
        }
        .estado-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 11pt;
        }
        .estado-completado { background: #d4edda; color: #155724; }
        @media print {
            body { padding: 0; }
            .report-container { border: none; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <!-- Botones de impresión (no se imprimen) -->
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="
            padding: 12px 30px;
            font-size: 14pt;
            background: #1a3a5c;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-right: 10px;
        ">
            <i class="fas fa-print"></i> Imprimir Reporte
        </button>
        <button onclick="window.close()" style="
            padding: 12px 30px;
            font-size: 14pt;
            background: #6c757d;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        ">
            Cerrar
        </button>
    </div>

    <div class="report-container">
        <!-- Encabezado -->
        <div class="report-header">
            <div>
                <h1>LabClínico</h1>
                <p>Laboratorio de Exámenes Clínicos</p>
            </div>
            <div class="logo">
                <p>RIF: J-12345678-9</p>
                <p>reportes@labclinico.com</p>
            </div>
        </div>

        <!-- Cuerpo del reporte -->
        <div class="report-body">
            <!-- Datos del paciente -->
            <div class="report-section">
                <h3>Datos del Paciente</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Nombre Completo:</span>
                        <span class="info-value"><?php echo htmlspecialchars($datos['nombre_completo']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Cédula de Identidad:</span>
                        <span class="info-value"><?php echo htmlspecialchars($datos['cedula_identidad']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Edad:</span>
                        <span class="info-value"><?php echo $datos['edad']; ?> años</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Género:</span>
                        <span class="info-value"><?php echo $datos['genero']; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Fecha de Nacimiento:</span>
                        <span class="info-value"><?php echo date('d/m/Y', strtotime($datos['fecha_nacimiento'])); ?></span>
                    </div>
                </div>
            </div>

            <!-- Datos del examen -->
            <div class="report-section">
                <h3>Información del Examen</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Examen:</span>
                        <span class="info-value"><?php echo htmlspecialchars($datos['nombre_examen']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Código:</span>
                        <span class="info-value"><?php echo $datos['codigo']; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Categoría:</span>
                        <span class="info-value"><?php echo $datos['categoria']; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Método de Pago:</span>
                        <span class="info-value"><?php echo $datos['metodo_pago']; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Fecha de Solicitud:</span>
                        <span class="info-value"><?php echo date('d/m/Y H:i', strtotime($datos['fecha_solicitud'])); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Estado:</span>
                        <span class="estado-badge estado-completado"><?php echo $datos['estado']; ?></span>
                    </div>
                </div>
            </div>

            <!-- Resultados -->
            <div class="report-section">
                <h3>Resultados del Examen</h3>
                <div class="result-box">
                    <?php echo nl2br(htmlspecialchars($datos['resultado_texto'] ?? 'Sin resultados registrados.')); ?>
                </div>
                <?php if (!empty($datos['observaciones'])): ?>
                    <h4 style="margin-top: 15px;">Observaciones:</h4>
                    <p><?php echo nl2br(htmlspecialchars($datos['observaciones'])); ?></p>
                <?php endif; ?>
            </div>

            <!-- Firmas -->
            <div class="firma-section">
                <div class="firma-linea">Firma del Bioanalista</div>
                <div class="firma-linea">Sello del Laboratorio</div>
            </div>
        </div>

        <!-- Pie de página -->
        <div class="report-footer">
            <span>Reporte generado el: <?php echo date('d/m/Y H:i:s'); ?></span>
            <span>LabClínico v<?php echo APP_VERSION; ?> | Este reporte es confidencial</span>
        </div>
    </div>

    <!-- Auto-imprimir al cargar -->
    <script>
        window.onload = function() {
            // Pequeño retraso para asegurar que todo se renderice
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>