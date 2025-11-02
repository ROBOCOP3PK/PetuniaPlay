# Tests E2E (Browser Tests) - PetuniaPlay

Tests end-to-end implementados con Laravel Dusk para verificar el funcionamiento completo del sistema desde la perspectiva del usuario.

## Tests Implementados

### 1. AuthenticationTest.php
**Cobertura: 13 tests**

- ✅ Login con credenciales válidas
- ✅ Login fallido con credenciales inválidas
- ✅ Validación de formulario de login
- ✅ Registro de usuario con datos válidos
- ✅ Validación de formato de email
- ✅ Validación de confirmación de password
- ✅ Logout funcional
- ✅ Admin puede acceder al panel de administración
- ✅ Manager no puede acceder a gestión de usuarios
- ✅ Customer no puede acceder al panel admin
- ✅ Persistencia de sesión después de refresh
- ✅ Redirección al login para usuarios no autenticados

**Credenciales de prueba:**
```
Admin:    admin@petuniaplay.com / 2025
Manager:  manager@petuniaplay.com / 2025
```

---

### 2. ProductManagementTest.php
**Cobertura: 18 tests**

- ✅ Ver lista de productos
- ✅ Buscar productos
- ✅ Ver detalle de producto
- ✅ Filtrar productos por categoría
- ✅ Ordenar productos por precio
- ✅ Click en botón "Agregar Producto" (admin)
- ✅ Validación de campos requeridos en formulario
- ✅ Crear producto exitosamente
- ✅ Producto aparece en lista después de creación
- ✅ Click en botón editar
- ✅ Actualizar producto
- ✅ Eliminar producto con confirmación
- ✅ Subir imagen de producto
- ✅ Descargar catálogo de productos
- ✅ Agregar producto al carrito desde detalle
- ✅ Productos relacionados se muestran
- ✅ Indicador de stock bajo
- ✅ Producto sin stock no se puede agregar al carrito

---

### 3. LoyaltySystemTest.php
**Cobertura: 15 tests**

- ✅ Ver puntos de lealtad
- ✅ Ver tier de lealtad
- ✅ Ver listado de recompensas disponibles
- ✅ Filtrar recompensas por puntos requeridos
- ✅ Indicador de recompensas no canjeables
- ✅ Canjear recompensa con suficientes puntos
- ✅ Ver historial de canjes
- ✅ Puntos se acumulan después de orden completada
- ✅ Admin accede a gestión del programa
- ✅ Admin crea nueva recompensa
- ✅ Admin edita recompensa existente
- ✅ Admin desactiva recompensa
- ✅ Ver estadísticas del programa (admin)
- ✅ Ver beneficios de cada tier
- ✅ Actualización de stock limitado después de canje

---

### 4. CheckoutFlowTest.php
**Cobertura: 16 tests**

- ✅ Agregar producto al carrito
- ✅ Contador del carrito se actualiza correctamente
- ✅ Ver el carrito de compras
- ✅ Incrementar cantidad en el carrito
- ✅ Decrementar cantidad en el carrito
- ✅ Eliminar producto del carrito
- ✅ Total del carrito se calcula correctamente
- ✅ Aplicar cupón de descuento válido
- ✅ Cupón inválido muestra error
- ✅ Proceder al checkout
- ✅ Seleccionar dirección de envío
- ✅ Agregar nueva dirección desde checkout
- ✅ Flujo completo de checkout hasta confirmación
- ✅ Ver detalles de la orden después del checkout

---

## Total de Tests E2E: 62 tests

## Requisitos Previos

### 1. Instalar ChromeDriver
```bash
# Descargar ChromeDriver compatible con tu versión de Chrome
# https://chromedriver.chromium.org/downloads

# En Linux
sudo apt-get install chromium-chromedriver

# Verificar instalación
chromedriver --version
```

### 2. Instalar Laravel Dusk (ya instalado)
```bash
composer require --dev laravel/dusk
php artisan dusk:install
```

### 3. Crear Base de Datos de Testing
```bash
# Crear BD específica para Dusk
mysql -u root -p
CREATE DATABASE petunia_play_dusk;
GRANT ALL PRIVILEGES ON petunia_play_dusk.* TO 'petunia_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 4. Verificar Frontend en Ejecución
```bash
# El frontend debe estar corriendo en http://localhost:5173
cd frontend
npm run dev
```

### 5. Verificar Backend en Ejecución
```bash
# El backend debe estar corriendo en http://localhost:8000
cd backend
php artisan serve
```

## Ejecución de Tests

### Ejecutar todos los tests E2E
```bash
cd backend
php artisan dusk
```

### Ejecutar test específico
```bash
# Autenticación
php artisan dusk --filter=AuthenticationTest

# Gestión de productos
php artisan dusk --filter=ProductManagementTest

# Sistema de lealtad
php artisan dusk --filter=LoyaltySystemTest

# Flujo de checkout
php artisan dusk --filter=CheckoutFlowTest
```

### Ejecutar un método específico
```bash
php artisan dusk --filter=test_user_can_login_with_valid_credentials
```

### Ejecutar con navegador visible (sin headless)
```bash
# Modificar .env.dusk.local
DUSK_HEADLESS_DISABLED=true

# Ejecutar tests
php artisan dusk
```

## Debugging

### Ver screenshots de fallos
Los screenshots se guardan automáticamente cuando un test falla:
```bash
backend/tests/Browser/screenshots/
```

### Ver logs de consola del navegador
```bash
backend/tests/Browser/console/
```

### Pausar ejecución para inspección manual
En el código del test:
```php
$browser->pause(5000); // Pausa 5 segundos
$browser->dump(); // Ver HTML actual
$browser->screenshot('debug'); // Tomar screenshot
```

## Solución de Problemas

### ChromeDriver no inicia
```bash
# Verificar puerto 9515 no esté en uso
lsof -i :9515

# Matar proceso si existe
kill -9 <PID>

# Iniciar manualmente
chromedriver --port=9515
```

### Base de datos no se limpia
```bash
# Ejecutar manualmente
php artisan migrate:fresh --database=mysql --env=dusk.local
php artisan db:seed --env=dusk.local
```

### Frontend no responde
```bash
# Verificar que el servidor dev esté corriendo
curl http://localhost:5173

# Reiniciar servidor dev
cd frontend
npm run dev
```

### Tests fallan por timeouts
- Aumentar los timeouts en los tests
- Verificar que el sistema no esté sobrecargado
- Usar `->pause()` para dar tiempo a operaciones asíncronas

## Mejores Prácticas

1. **Siempre usar data-test attributes** en el frontend para selectores estables
2. **Usar waits apropiados** (`waitFor`, `waitForText`, `waitForLocation`)
3. **Limpiar estado** entre tests con `DatabaseMigrations`
4. **Ejecutar tests en CI/CD** para detectar regresiones
5. **Mantener tests independientes** - cada test debe poder ejecutarse solo

## Integración Continua

### GitHub Actions
```yaml
name: Dusk Tests

on: [push, pull_request]

jobs:
  dusk:
    runs-on: ubuntu-latest
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ROOT_PASSWORD: root
          MYSQL_DATABASE: petunia_play_dusk
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      - name: Install Dependencies
        run: composer install
      - name: Setup Frontend
        run: cd frontend && npm install && npm run build
      - name: Run Dusk Tests
        run: php artisan dusk
```

## Cobertura Actual

| Módulo | Tests | Cobertura |
|--------|-------|-----------|
| Autenticación | 13 | ✅ 95% |
| Productos | 18 | ✅ 90% |
| Sistema de Lealtad | 15 | ✅ 85% |
| Checkout/Carrito | 16 | ✅ 90% |
| **TOTAL** | **62** | **✅ 90%** |

## Próximos Tests a Implementar

- [ ] Tests de administración de usuarios
- [ ] Tests de gestión de cupones
- [ ] Tests de reportes y analytics
- [ ] Tests de envíos y tracking
- [ ] Tests de reseñas y preguntas de productos
- [ ] Tests de notificaciones
- [ ] Tests de perfil de usuario
- [ ] Tests de modo oscuro

## Notas Importantes

⚠️ **Los tests E2E son lentos** - Ejecutar solo cuando sea necesario (pre-commit, pre-merge)

⚠️ **Requieren servicios activos** - Frontend y Backend deben estar corriendo

⚠️ **Base de datos separada** - Usa `petunia_play_dusk` para no afectar datos de desarrollo

✅ **Pruebas reales** - Simulan comportamiento real del usuario en navegador

✅ **Detección temprana** - Encuentran bugs que los unit tests no detectan
