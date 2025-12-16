-- ============================================
-- SCRIPT COMPLETO MYSQL - SISTEMA AVÍCOLA
-- ============================================

CREATE DATABASE IF NOT EXISTS db_sistema;
USE db_sistema;

-- ============================================
-- TABLA: USUARIOS
-- ============================================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('administrador', 'usuario', 'visitante') DEFAULT 'usuario',
    estado ENUM('activo', 'inactivo', 'eliminado') DEFAULT 'activo',
    avatar_url VARCHAR(255),
    ultimo_acceso DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: GALPONES
-- ============================================
CREATE TABLE IF NOT EXISTS galpones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    capacidad_maxima INT NOT NULL,
    area_m2 DECIMAL(10,2),
    ubicacion VARCHAR(255),
    estado ENUM('activo', 'mantenimiento', 'inactivo', 'eliminado') DEFAULT 'activo',
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_codigo (codigo),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: LOTES
-- ============================================
CREATE TABLE IF NOT EXISTS lotes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    galpon_id INT NOT NULL,
    cantidad_inicial INT NOT NULL,
    cantidad_actual INT NOT NULL,
    edad_semanas INT,
    raza VARCHAR(100),
    tipo_ave ENUM('ponedora', 'engorde', 'reproductora') DEFAULT 'ponedora',
    fecha_ingreso DATE NOT NULL,
    fecha_salida DATE,
    estado ENUM('en_produccion', 'finalizado', 'cuarentena') DEFAULT 'en_produccion',
    observaciones TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (galpon_id) REFERENCES galpones(id) ON DELETE RESTRICT,
    INDEX idx_codigo (codigo),
    INDEX idx_galpon (galpon_id),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: CLASIFICACION_HUEVOS
-- ============================================
CREATE TABLE IF NOT EXISTS clasificacion_huevos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lote_id INT NOT NULL,
    fecha DATE NOT NULL,
    cantidad_jumbo INT DEFAULT 0,
    cantidad_aaa INT DEFAULT 0,
    cantidad_aa INT DEFAULT 0,
    cantidad_a INT DEFAULT 0,
    cantidad_b INT DEFAULT 0,
    cantidad_c INT DEFAULT 0,
    cantidad_rotos INT DEFAULT 0,
    total_huevos INT GENERATED ALWAYS AS (cantidad_jumbo + cantidad_aaa + cantidad_aa + cantidad_a + cantidad_b + cantidad_c) STORED,
    observaciones TEXT,
    registrado_por INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (lote_id) REFERENCES lotes(id) ON DELETE RESTRICT,
    FOREIGN KEY (registrado_por) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_lote_fecha (lote_id, fecha),
    INDEX idx_fecha (fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: NOTIFICACIONES
-- ============================================
CREATE TABLE IF NOT EXISTS notificaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    tipo VARCHAR(50),
    titulo VARCHAR(255) NOT NULL,
    mensaje TEXT NOT NULL,
    icono VARCHAR(50) DEFAULT 'fa-bell',
    color VARCHAR(20) DEFAULT 'info',
    url_destino VARCHAR(255),
    leido TINYINT(1) DEFAULT 0,
    fecha_leido DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_usuario (usuario_id),
    INDEX idx_leido (leido),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- DATOS DE PRUEBA
-- ============================================

-- Usuarios de prueba
INSERT INTO usuarios (nombre, username, email, password_hash, rol, estado) VALUES
('Administrador SENA', 'admin', 'admin@sena.edu.co', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'administrador', 'activo'),
('Usuario Operador', 'usuario', 'usuario@sena.edu.co', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'usuario', 'activo'),
('Usuario Visitante', 'visitante', 'visitante@sena.edu.co', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'visitante', 'activo');

-- Galpones de prueba
INSERT INTO galpones (codigo, nombre, capacidad_maxima, area_m2, ubicacion, estado, descripcion) VALUES
('GAL-001', 'Galpón Principal', 5000, 250.50, 'Sector Norte', 'activo', 'Galpón principal para aves ponedoras'),
('GAL-002', 'Galpón Secundario', 3000, 180.00, 'Sector Sur', 'activo', 'Galpón secundario');

-- Lote de prueba
INSERT INTO lotes (codigo, nombre, galpon_id, cantidad_inicial, cantidad_actual, edad_semanas, raza, tipo_ave, fecha_ingreso, estado) VALUES
('LOTE-001', 'Lote Ponedoras 2024', 1, 1000, 1000, 20, 'Leghorn Blanca', 'ponedora', '2024-01-15', 'en_produccion');

-- Clasificación de prueba
INSERT INTO clasificacion_huevos (lote_id, fecha, cantidad_jumbo, cantidad_aaa, cantidad_aa, cantidad_a, cantidad_b, cantidad_c, cantidad_rotos, registrado_por) VALUES
(1, '2024-12-15', 50, 120, 200, 150, 80, 30, 10, 1);

-- Notificaciones de prueba
INSERT INTO notificaciones (usuario_id, tipo, titulo, mensaje, icono, color, url_destino, leido) VALUES
(1, 'nuevo_lote', 'Nuevo lote registrado', 'Se ha registrado un nuevo lote de aves', 'fa-check', 'success', '/lotes/1', 0),
(1, 'alerta', 'Alerta de producción', 'La producción ha disminuido en el lote 1', 'fa-exclamation-triangle', 'warning', '/lotes/1', 0);