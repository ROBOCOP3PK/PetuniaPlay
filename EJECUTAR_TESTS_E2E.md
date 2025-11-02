# 🧪 Guía para Ejecutar Tests E2E - PetuniaPlay

## ⚠️ Estado Actual

**Tests Generados:** ✅ 62 tests E2E
**Requisitos Faltantes:** ❌ ChromeDriver, Frontend, Backend

---

## 📋 Checklist de Requisitos

Antes de ejecutar los tests, necesitas:

- [ ] ChromeDriver instalado
- [ ] Frontend corriendo en http://localhost:5173
- [ ] Backend corriendo en http://localhost:8000
- [ ] Base de datos de testing creada

---

## 🚀 Pasos para Ejecutar Tests E2E

### PASO 1: Instalar ChromeDriver

```bash
# Opción A: Ubuntu/Debian
sudo apt-get update
sudo apt-get install -y chromium-chromedriver

# Opción B: Descargar manualmente
wget https://chromedriver.storage.googleapis.com/LATEST_RELEASE
# Descargar la versión correspondiente a tu Chrome

# Verificar instalación
chromedriver --version
```

### PASO 2: Iniciar el Frontend (Terminal 1)

```bash
cd /home/david_gonzalez/personal_projects/PetuniaPlay/frontend
npm install  # Si es la primera vez
npm run dev
```

**Espera ver:**
```
VITE v5.x.x  ready in xxx ms

➜  Local:   http://localhost:5173/
➜  Network: use --host to expose
```

### PASO 3: Iniciar el Backend (Terminal 2)

```bash
cd /home/david_gonzalez/personal_projects/PetuniaPlay/backend
php artisan serve
```

**Espera ver:**
```
Starting Laravel development server: http://127.0.0.1:8000
[Sat Oct 30 23:00:00 2025] PHP 8.2.x Development Server (http://127.0.0.1:8000) started
```

### PASO 4: Configurar Base de Datos de Testing (Terminal 3)

```bash
cd /home/david_gonzalez/personal_projects/PetuniaPlay/backend

# Crear base de datos
mysql -u root -p << EOF
CREATE DATABASE IF NOT EXISTS petunia_play_dusk CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON petunia_play_dusk.* TO 'petunia_user'@'localhost';
FLUSH PRIVILEGES;
EOF

# Ejecutar migraciones
php artisan migrate:fresh --database=mysql --env=dusk.local --force

# Ejecutar seeders
php artisan db:seed --env=dusk.local --force
```

### PASO 5: Ejecutar Tests E2E

```bash
cd /home/david_gonzalez/personal_projects/PetuniaPlay/backend

# Opción A: Todos los tests (aprox. 10-15 minutos)
php artisan dusk

# Opción B: Tests específicos (aprox. 2-3 minutos cada uno)
php artisan dusk --filter=AuthenticationTest
php artisan dusk --filter=ProductManagementTest
php artisan dusk --filter=LoyaltySystemTest
php artisan dusk --filter=CheckoutFlowTest

# Opción C: Un test individual (aprox. 30 segundos)
php artisan dusk --filter=test_user_can_login_with_valid_credentials
```

---

## 🎯 Opción Rápida: Script Automatizado

Si prefieres, usa el script que automatiza la configuración:

```bash
cd /home/david_gonzalez/personal_projects/PetuniaPlay/backend
./setup-dusk-tests.sh
```

El script te guiará paso a paso.

---

## 📊 Interpretando Resultados

### ✅ Test Exitoso
```
OK (13 tests, 45 assertions)
```

### ❌ Test Fallido
```
FAILURES!
Tests: 13, Assertions: 42, Failures: 3

Ver screenshots en: tests/Browser/screenshots/
Ver logs en: tests/Browser/console/
```

---

## 🐛 Troubleshooting

### Error: "ChromeDriver not found"
```bash
sudo apt-get install chromium-chromedriver
which chromedriver  # Verificar ubicación
```

### Error: "Connection refused localhost:5173"
```bash
# Terminal separada
cd frontend
npm run dev
```

### Error: "Connection refused localhost:8000"
```bash
# Terminal separada
cd backend
php artisan serve
```

### Error: "SQLSTATE[HY000] [1049] Unknown database"
```bash
mysql -u root -p
CREATE DATABASE petunia_play_dusk;
GRANT ALL PRIVILEGES ON petunia_play_dusk.* TO 'petunia_user'@'localhost';
```

### Error: "Session not created"
Versión de Chrome no compatible con ChromeDriver:
```bash
google-chrome --version
chromedriver --version
# Ambos deben ser compatibles
```

### Tests muy lentos
```bash
# Aumentar recursos del sistema
# O ejecutar tests individuales
php artisan dusk --filter=AuthenticationTest
```

---

## 📁 Archivos de Tests Generados

1. **AuthenticationTest.php** - 13 tests
   - Login, registro, logout, roles

2. **ProductManagementTest.php** - 18 tests
   - CRUD, búsqueda, filtros, imágenes

3. **LoyaltySystemTest.php** - 15 tests
   - Puntos, recompensas, canjes

4. **CheckoutFlowTest.php** - 16 tests
   - Carrito, checkout, cupones

**Total: 62 tests E2E**

---

## 🎥 Modo Visual (Ver el Navegador)

Para ver el navegador durante los tests:

1. Edita `.env.dusk.local`:
```env
DUSK_HEADLESS_DISABLED=true
```

2. Ejecuta tests:
```bash
php artisan dusk
```

Verás una ventana de Chrome ejecutando los tests en tiempo real.

---

## ⚡ Comandos Rápidos

```bash
# Verificar todo está listo
curl http://localhost:5173  # Frontend
curl http://localhost:8000  # Backend
chromedriver --version      # ChromeDriver

# Ejecutar tests
cd backend
php artisan dusk

# Ver resultados
ls tests/Browser/screenshots/
ls tests/Browser/console/
```

---

## 📝 Notas Importantes

⏱️ **Tiempo estimado:** 10-15 minutos para todos los tests
💾 **Base de datos:** Se limpia automáticamente entre tests
🖼️ **Screenshots:** Se guardan automáticamente en fallos
🔄 **Repetibilidad:** Tests son independientes y repetibles

---

## 🎯 Siguientes Pasos

Una vez ejecutados los tests:

1. Revisa el reporte de cobertura
2. Corrige cualquier fallo encontrado
3. Agrega `data-test` attributes en el frontend para mayor estabilidad
4. Configura CI/CD para ejecutar tests automáticamente

---

## 📞 Ayuda

Si encuentras problemas:

1. Verifica que los 3 servicios estén corriendo (Frontend, Backend, ChromeDriver)
2. Revisa los logs en `tests/Browser/console/`
3. Revisa screenshots en `tests/Browser/screenshots/`
4. Lee el README detallado en `tests/Browser/README.md`

**¡Buena suerte! 🚀**
