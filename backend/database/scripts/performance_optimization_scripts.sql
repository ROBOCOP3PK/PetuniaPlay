-- =====================================================
-- SCRIPTS DE OPTIMIZACIÓN Y ANÁLISIS DE RENDIMIENTO
-- PetuniaPlay Backend - Base de datos MySQL/MariaDB
-- =====================================================

-- ----------------------------------------------------
-- 1. VERIFICAR ÍNDICES EXISTENTES
-- ----------------------------------------------------

-- Ver todos los índices de la tabla products
SHOW INDEXES FROM products;

-- Ver todos los índices de la tabla orders
SHOW INDEXES FROM orders;

-- Ver todos los índices de la tabla reviews
SHOW INDEXES FROM reviews;

-- Ver todos los índices de la tabla order_items
SHOW INDEXES FROM order_items;


-- ----------------------------------------------------
-- 2. ANÁLISIS DE QUERIES LENTAS
-- ----------------------------------------------------

-- Habilitar el log de queries lentas (requiere permisos de admin)
-- SET GLOBAL slow_query_log = 'ON';
-- SET GLOBAL long_query_time = 2; -- queries que tomen más de 2 segundos

-- Ver variables relacionadas con performance
SHOW VARIABLES LIKE 'slow_query%';
SHOW VARIABLES LIKE 'long_query_time';


-- ----------------------------------------------------
-- 3. ESTADÍSTICAS DE TABLAS
-- ----------------------------------------------------

-- Ver tamaño de las tablas
SELECT
    table_name AS 'Tabla',
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Tamaño (MB)',
    table_rows AS 'Filas estimadas'
FROM information_schema.TABLES
WHERE table_schema = 'petunia_play'
ORDER BY (data_length + index_length) DESC;


-- ----------------------------------------------------
-- 4. ANÁLISIS DE ÍNDICES NO UTILIZADOS
-- ----------------------------------------------------

-- Ver estadísticas de uso de índices (MySQL 5.6+)
SELECT
    object_schema AS 'Base de datos',
    object_name AS 'Tabla',
    index_name AS 'Índice',
    count_star AS 'Lecturas',
    count_read AS 'Búsquedas'
FROM performance_schema.table_io_waits_summary_by_index_usage
WHERE object_schema = 'petunia_play'
ORDER BY count_star DESC;


-- ----------------------------------------------------
-- 5. OPTIMIZAR TABLAS
-- ----------------------------------------------------

-- Analizar tablas para actualizar estadísticas de índices
ANALYZE TABLE products;
ANALYZE TABLE orders;
ANALYZE TABLE order_items;
ANALYZE TABLE reviews;
ANALYZE TABLE categories;
ANALYZE TABLE product_images;
ANALYZE TABLE wishlist_items;
ANALYZE TABLE addresses;
ANALYZE TABLE notifications;
ANALYZE TABLE coupons;

-- Optimizar tablas para desfragmentar y recuperar espacio
-- NOTA: Esto puede tomar tiempo en tablas grandes
-- OPTIMIZE TABLE products;
-- OPTIMIZE TABLE orders;
-- OPTIMIZE TABLE order_items;


-- ----------------------------------------------------
-- 6. CONSULTAS DE ANÁLISIS DE RENDIMIENTO
-- ----------------------------------------------------

-- Productos más vendidos (útil para caché)
SELECT
    p.id,
    p.name,
    p.slug,
    COUNT(oi.id) as total_orders,
    SUM(oi.quantity) as total_quantity,
    SUM(oi.subtotal) as total_revenue
FROM products p
INNER JOIN order_items oi ON p.id = oi.product_id
INNER JOIN orders o ON oi.order_id = o.id
WHERE o.status != 'cancelled'
    AND o.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY p.id, p.name, p.slug
ORDER BY total_quantity DESC
LIMIT 20;


-- Productos con más reviews (impacto en performance)
SELECT
    p.id,
    p.name,
    COUNT(r.id) as total_reviews,
    AVG(r.rating) as avg_rating
FROM products p
LEFT JOIN reviews r ON p.id = r.product_id AND r.is_approved = 1
GROUP BY p.id, p.name
HAVING total_reviews > 0
ORDER BY total_reviews DESC
LIMIT 20;


-- Productos sin stock que están activos (posibles problemas)
SELECT
    id,
    name,
    sku,
    stock,
    is_active,
    is_featured
FROM products
WHERE stock = 0
    AND is_active = 1
ORDER BY is_featured DESC, name ASC;


-- Órdenes pendientes de hace más de 7 días
SELECT
    o.id,
    o.order_number,
    o.status,
    o.payment_status,
    o.total,
    u.name as customer_name,
    u.email as customer_email,
    DATEDIFF(NOW(), o.created_at) as days_pending
FROM orders o
INNER JOIN users u ON o.user_id = u.id
WHERE o.status IN ('pending', 'processing')
    AND o.created_at < DATE_SUB(NOW(), INTERVAL 7 DAY)
ORDER BY o.created_at ASC;


-- ----------------------------------------------------
-- 7. QUERIES DE MANTENIMIENTO PARA CACHÉ
-- ----------------------------------------------------

-- Limpiar cache de Laravel (ejecutar desde artisan)
-- php artisan cache:clear
-- php artisan config:clear
-- php artisan route:clear
-- php artisan view:clear

-- Ver tamaño del cache (si usa database driver)
SELECT
    COUNT(*) as total_entries,
    ROUND(SUM(LENGTH(value)) / 1024 / 1024, 2) as size_mb
FROM cache;


-- ----------------------------------------------------
-- 8. ÍNDICES CRÍTICOS RECOMENDADOS
-- ----------------------------------------------------

-- Si necesitas crear índices manualmente (ya están en las migraciones):

-- Products
-- ALTER TABLE products ADD INDEX idx_brand (brand);
-- ALTER TABLE products ADD INDEX idx_is_active (is_active);
-- ALTER TABLE products ADD INDEX idx_is_featured (is_featured);
-- ALTER TABLE products ADD INDEX idx_category_active (category_id, is_active);

-- Orders
-- ALTER TABLE orders ADD INDEX idx_status (status);
-- ALTER TABLE orders ADD INDEX idx_payment_status (payment_status);
-- ALTER TABLE orders ADD INDEX idx_status_payment (status, payment_status);
-- ALTER TABLE orders ADD INDEX idx_created_at (created_at);

-- Reviews
-- ALTER TABLE reviews ADD INDEX idx_product_approved (product_id, is_approved);
-- ALTER TABLE reviews ADD INDEX idx_product_rating (product_id, rating);
-- ALTER TABLE reviews ADD INDEX idx_is_approved (is_approved);

-- Order Items
-- ALTER TABLE order_items ADD INDEX idx_product_id (product_id);
-- ALTER TABLE order_items ADD INDEX idx_order_product (order_id, product_id);


-- ----------------------------------------------------
-- 9. QUERIES PARA MONITOREO DE PERFORMANCE
-- ----------------------------------------------------

-- Ver queries activas en este momento
SHOW PROCESSLIST;

-- Ver queries lentas recientes (si está habilitado el log)
-- SELECT * FROM mysql.slow_log ORDER BY start_time DESC LIMIT 20;

-- Ver configuración de buffer pool (InnoDB)
SHOW VARIABLES LIKE 'innodb_buffer_pool%';

-- Ver estado de conexiones
SHOW STATUS LIKE 'Threads%';
SHOW STATUS LIKE 'Connections';


-- ----------------------------------------------------
-- 10. LIMPIEZA Y MANTENIMIENTO
-- ----------------------------------------------------

-- Eliminar notificaciones leídas antiguas (más de 30 días)
-- DELETE FROM notifications
-- WHERE is_read = 1
--   AND created_at < DATE_SUB(NOW(), INTERVAL 30 DAY);

-- Eliminar carritos abandonados (session cache antiguo)
-- DELETE FROM cache
-- WHERE expiration < UNIX_TIMESTAMP();

-- Limpiar logs antiguos de jobs fallidos
-- DELETE FROM failed_jobs
-- WHERE failed_at < DATE_SUB(NOW(), INTERVAL 90 DAY);


-- =====================================================
-- NOTAS IMPORTANTES:
-- =====================================================
--
-- 1. Ejecutar ANALYZE TABLE regularmente (semanal/mensual)
-- 2. Monitorear el slow query log
-- 3. Revisar índices no utilizados cada trimestre
-- 4. Mantener el cache limpio
-- 5. Los índices mejoran SELECT pero ralentizan INSERT/UPDATE
-- 6. No crear índices innecesarios
-- 7. Usar EXPLAIN para analizar queries problemáticas
-- 8. Considerar particionamiento para tablas muy grandes
--
-- =====================================================
