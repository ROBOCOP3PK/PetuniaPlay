# ⚡ Instrucciones Rápidas - Ejecutar Tests E2E

## 🎯 3 Pasos Simples

### 1️⃣ Instalar ChromeDriver
```bash
sudo apt-get install -y chromium-chromedriver
```

### 2️⃣ Configurar Base de Datos
```bash
cd /home/david_gonzalez/personal_projects/PetuniaPlay/backend
mysql -u root -p < setup-test-database.sql
```

### 3️⃣ Ejecutar Tests

**Opción A: Abrir 3 terminales**

```bash
# Terminal 1 - Frontend
cd /home/david_gonzalez/personal_projects/PetuniaPlay/frontend
npm run dev

# Terminal 2 - Backend
cd /home/david_gonzalez/personal_projects/PetuniaPlay/backend
php artisan serve

# Terminal 3 - Tests
cd /home/david_gonzalez/personal_projects/PetuniaPlay/backend
php artisan migrate:fresh --env=dusk.local --force
php artisan db:seed --env=dusk.local --force
php artisan dusk
```

**Opción B: Usar el script automatizado**

```bash
cd /home/david_gonzalez/personal_projects/PetuniaPlay/backend

# Primero ejecuta esto en terminales separadas:
# Terminal 1: cd ../frontend && npm run dev
# Terminal 2: php artisan serve

# Luego en terminal 3:
./setup-dusk-tests.sh
```

---

## 📊 Resultado Esperado

```
OK (62 tests, 180+ assertions)
Time: 00:12:34
```

---

## 🆘 Si algo falla

1. **ChromeDriver no encontrado**
   ```bash
   which chromedriver
   sudo apt-get install chromium-chromedriver
   ```

2. **Frontend no responde**
   ```bash
   cd frontend && npm run dev
   # Verificar: curl http://localhost:5173
   ```

3. **Backend no responde**
   ```bash
   cd backend && php artisan serve
   # Verificar: curl http://localhost:8000
   ```

4. **Base de datos no existe**
   ```bash
   mysql -u root -p < setup-test-database.sql
   ```

---

## ✅ Tests Generados

- ✅ **AuthenticationTest** (13 tests) - Login, registro, roles
- ✅ **ProductManagementTest** (18 tests) - CRUD productos
- ✅ **LoyaltySystemTest** (15 tests) - Sistema de puntos
- ✅ **CheckoutFlowTest** (16 tests) - Carrito y checkout

**Total: 62 tests E2E listos para ejecutar**
