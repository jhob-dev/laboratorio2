-- ============================================
-- LABORATORIO CLÍNICO - ESQUEMA DE BASE DE DATOS
-- Basado en el modelo entidad-relación proporcionado
-- Motor: MariaDB / MySQL
-- ============================================

CREATE DATABASE IF NOT EXISTS laboratorio_clinico
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE laboratorio_clinico;

-- -----------------------------------------------------------
-- Tabla: pacientes
-- Almacena los datos personales de cada paciente
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS pacientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(200) NOT NULL,
    cedula_identidad VARCHAR(20) NOT NULL UNIQUE,
    fecha_nacimiento DATE NOT NULL,
    edad INT NOT NULL COMMENT 'Calculada automáticamente desde fecha_nacimiento',
    genero ENUM('Masculino', 'Femenino', 'Otro') NOT NULL,
    direccion TEXT NOT NULL,
    telefono VARCHAR(20) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_cedula (cedula_identidad),
    INDEX idx_nombre (nombre_completo)
) ENGINE=InnoDB;

-- -----------------------------------------------------------
-- Tabla: examenes
-- Catálogo de exámenes disponibles en el laboratorio
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS examenes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE COMMENT 'Código interno del examen',
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT DEFAULT NULL,
    precio DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    categoria VARCHAR(100) DEFAULT NULL COMMENT 'Ej: Hematología, Química, Orina',
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -----------------------------------------------------------
-- Tabla: paciente_examenes
-- Relación entre pacientes y exámenes, incluye método de pago
-- Representa cada "orden" o "solicitud" de examen
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS paciente_examenes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    examen_id INT NOT NULL,
    metodo_pago ENUM('Efectivo', 'Pago Móvil', 'Transferencia', 'Punto de Venta') NOT NULL,
    referencia_pago VARCHAR(50) DEFAULT NULL COMMENT 'Últimos 4 dígitos para Pago Móvil/Transferencia',
    estado ENUM('Pendiente', 'En Proceso', 'Completado', 'Entregado') DEFAULT 'Pendiente',
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_completado TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE,
    FOREIGN KEY (examen_id) REFERENCES examenes(id) ON DELETE RESTRICT,
    INDEX idx_paciente (paciente_id),
    INDEX idx_estado (estado),
    INDEX idx_fecha (fecha_solicitud)
) ENGINE=InnoDB;

-- -----------------------------------------------------------
-- Tabla: resultados
-- Almacena los resultados de los exámenes realizados
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS resultados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_examen_id INT NOT NULL,
    resultado_texto TEXT DEFAULT NULL COMMENT 'Resultados en formato texto/JSON',
    observaciones TEXT DEFAULT NULL,
    archivo_adjunto VARCHAR(500) DEFAULT NULL COMMENT 'Ruta a archivo PDF/imagen',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (paciente_examen_id) REFERENCES paciente_examenes(id) ON DELETE CASCADE,
    UNIQUE INDEX idx_examen_unico (paciente_examen_id)
) ENGINE=InnoDB;

-- -----------------------------------------------------------
-- Datos de ejemplo: Exámenes comunes
-- -----------------------------------------------------------
INSERT INTO examenes (codigo, nombre, descripcion, precio, categoria) VALUES
('HEM-001', 'Hematología Completa', 'Conteo de glóbulos rojos, blancos, hemoglobina, hematocrito y plaquetas', 25.00, 'Hematología'),
('QUI-001', 'Glicemia en Ayunas', 'Medición de glucosa en sangre tras 8 horas de ayuno', 15.00, 'Química Sanguínea'),
('QUI-002', 'Perfil Lipídico', 'Colesterol total, HDL, LDL y triglicéridos', 35.00, 'Química Sanguínea'),
('ORI-001', 'Examen de Orina Completo', 'Análisis físico-químico y microscópico de la orina', 18.00, 'Uroanálisis'),
('SER-001', 'Prueba de VIH', 'Detección de anticuerpos contra el VIH', 20.00, 'Serología'),
('SER-002', 'Prueba de Embarazo', 'Detección de hCG en sangre', 12.00, 'Serología'),
('HEM-002', 'Tiempo de Coagulación', 'TP, TPT y recuento de plaquetas', 30.00, 'Hematología'),
('QUI-003', 'Función Renal', 'Urea, creatinina y ácido úrico', 28.00, 'Química Sanguínea');