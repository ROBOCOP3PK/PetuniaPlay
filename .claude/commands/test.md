# Generate Comprehensive Tests

Genera un conjunto completo de tests para el código o funcionalidad especificada.

## Contexto del Proyecto
- **Framework**: Laravel 11
- **Database**: MySQL/MariaDB
- **Proyecto**: PetuniaPlay - Sistema de lealtad para peluquería

## Tipos de Tests a Generar

### 1. Feature Tests (API/HTTP)
- Tests de endpoints y rutas
- Validación de requests y responses
- Códigos de estado HTTP correctos
- Autenticación y autorización
- Estructura de respuestas JSON
- Manejo de errores

### 2. Browser Tests (Laravel Dusk - E2E)
**IMPORTANTE**: Verifica primero si Laravel Dusk está instalado:
```bash
php artisan dusk --version
```
Si no está instalado, pregunta al usuario si desea instalarlo con:
```bash
composer require --dev laravel/dusk
php artisan dusk:install
```

Tests de navegador real que verifican:
- **Clicks en botones**: Verificar que los botones ejecuten la acción correcta
- **Formularios**: Submit, validación en vivo, mensajes de error
- **Descarga de archivos**: Verificar que PDFs/reportes se descarguen correctamente
- **Navegación**: Menús, redirecciones, breadcrumbs
- **CRUD completo**: Crear, leer, actualizar, eliminar registros
- **Interacciones UI**: Modals, dropdowns, tabs, alerts
- **Autenticación**: Login, logout, permisos por rol
- **JavaScript**: Verificar que funciones JS se ejecuten correctamente

### 3. Integration Tests
Tests de flujos completos de negocio:
- **Flujo de cliente**: Registro → Acumular puntos → Canjear recompensa
- **Flujo de administración**: Crear promoción → Asignar a clientes → Verificar aplicación
- **Flujo de productos**: Agregar producto → Asociar imagen → Mostrar en catálogo
- **Transacciones**: Crear orden → Aplicar descuento → Generar factura/ticket

### 4. Unit Tests
Tests de lógica de negocio aislada:
- Métodos de modelos
- Cálculos (puntos, descuentos, totales)
- Validaciones personalizadas
- Helpers y utilidades
- Formateo de datos

## Instrucciones de Ejecución

1. **Analiza el código especificado** por el usuario (controlador, modelo, vista, o funcionalidad)

2. **Identifica todas las funcionalidades** que necesitan testing:
   - Lee el código del controlador
   - Revisa las rutas relacionadas
   - Examina los modelos y relaciones
   - Verifica las vistas si hay interacción UI

3. **Genera los archivos de test** en las ubicaciones correctas:
   - Feature: `tests/Feature/[Nombre]Test.php`
   - Browser: `tests/Browser/[Nombre]Test.php`
   - Unit: `tests/Unit/[Nombre]Test.php`

4. **Incluye en cada test**:
   - Setup y teardown apropiados
   - Factories para datos de prueba
   - Assertions completas y específicas
   - Tests para casos exitosos y errores
   - Comentarios explicativos
   - Database transactions cuando sea necesario

5. **Para Browser Tests, incluye**:
   - Selectores específicos (IDs, data attributes)
   - Waits apropiados para cargas asíncronas
   - Screenshots en caso de fallos
   - Tests de diferentes roles de usuario

6. **Ejecuta los tests generados**:
```bash
# Feature y Unit tests
php artisan test --filter=[NombreTest]

# Browser tests
php artisan dusk --filter=[NombreTest]
```

7. **Reporta los resultados**:
   - Cantidad de tests generados por tipo
   - Resultado de la ejecución
   - Cobertura estimada
   - Sugerencias de tests adicionales si es necesario

## Ejemplo de Funcionalidades a Testear

Si el usuario especifica "ProductController", genera tests para:

### Feature Tests:
- `test_index_returns_products_list()`
- `test_store_creates_new_product()`
- `test_store_validates_required_fields()`
- `test_update_modifies_existing_product()`
- `test_destroy_deletes_product()`
- `test_unauthorized_user_cannot_create_product()`

### Browser Tests:
- `test_user_can_click_add_product_button()`
- `test_form_validates_empty_fields()`
- `test_user_can_submit_product_form_successfully()`
- `test_product_appears_in_list_after_creation()`
- `test_user_can_upload_product_image()`
- `test_user_can_download_product_catalog_pdf()`
- `test_edit_button_opens_edit_modal()`
- `test_delete_button_shows_confirmation()`

### Integration Tests:
- `test_complete_product_creation_flow()`
- `test_product_with_image_displays_in_catalog()`

### Unit Tests:
- `test_product_price_calculation_includes_tax()`
- `test_product_is_available_returns_correct_status()`

## Prioridades

1. **Funcionalidades críticas primero**: Login, pagos, puntos de lealtad
2. **Casos edge**: Valores nulos, strings vacíos, límites numéricos
3. **Seguridad**: Intentos de acceso no autorizado, SQL injection, XSS
4. **Performance**: Tests de carga si es necesario

## Resultado Final

Al finalizar, debes entregar:
- ✅ Archivos de test generados y guardados
- ✅ Tests ejecutados con resultados
- ✅ Reporte de cobertura
- ✅ Sugerencias de mejora

Si encuentras código sin tests o funcionalidades no testeadas, menciónalas al usuario.
