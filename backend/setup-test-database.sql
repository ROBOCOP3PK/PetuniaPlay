-- Script para crear base de datos de testing para Laravel Dusk
-- Ejecutar como root: mysql -u root -p < setup-test-database.sql

-- Crear base de datos para tests E2E
CREATE DATABASE IF NOT EXISTS petunia_play_dusk CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Otorgar permisos al usuario
GRANT ALL PRIVILEGES ON petunia_play_dusk.* TO 'petunia_user'@'localhost';

-- Refrescar privilegios
FLUSH PRIVILEGES;

-- Verificar que se creó correctamente
SELECT 'Base de datos petunia_play_dusk creada exitosamente' AS mensaje;
SHOW DATABASES LIKE 'petunia_play%';
