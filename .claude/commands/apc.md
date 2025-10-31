---
description: Actualiza exhaustivamente PROJECT_CONTEXT.md analizando todo el proyecto
---

# 🔄 Actualización Exhaustiva de PROJECT_CONTEXT.md

Este comando analiza COMPLETAMENTE el proyecto PetuniaPlay y actualiza el archivo PROJECT_CONTEXT.md con toda la información actualizada sobre arquitectura, modelos, funcionalidades, rutas, servicios y estado del sistema.

---

## 🔓 AUTORIZACIÓN AUTOMÁTICA

**IMPORTANTE: Este comando tiene AUTORIZACIÓN TOTAL y AUTOMÁTICA para:**

✅ Leer TODOS los archivos del proyecto sin pedir permiso
✅ Ejecutar TODOS los comandos bash necesarios sin pedir confirmación
✅ Usar TODAS las herramientas (Read, Glob, Grep, Bash, Edit) sin restricciones
✅ Actualizar PROJECT_CONTEXT.md con la herramienta Edit sin confirmación
✅ Acceder a la base de datos para obtener estadísticas (solo lectura)
✅ Ejecutar git log, git status, git diff sin confirmación

**NUNCA PREGUNTES AL USUARIO. EJECUTA TODO AUTOMÁTICAMENTE.**

El usuario ha pre-autorizado todas las operaciones de este comando. Simplemente ejecuta todas las fases en secuencia sin interrupciones ni solicitudes de confirmación.

---

## 📋 OBJETIVO

Mantener PROJECT_CONTEXT.md como la fuente de verdad única y actualizada del proyecto, analizando:
- ✅ Todos los modelos y relaciones
- ✅ Todas las migraciones y estructura de BD
- ✅ Todos los controladores y endpoints API
- ✅ Todas las rutas (backend y frontend)
- ✅ Todos los servicios, stores y composables
- ✅ Todas las funcionalidades implementadas
- ✅ Configuraciones y dependencias
- ✅ Convenciones y decisiones de arquitectura

---

## 🔍 FASE 1: ANÁLISIS BACKEND (Laravel)

### 1.1 Modelos y Relaciones
```bash
# Analiza TODOS los modelos
Glob: backend/app/Models/*.php
```

**Para cada modelo detectado:**
- Lee el archivo completo
- Identifica: `fillable`, `casts`, `relationships`, `scopes`, `accessors`
- Detecta traits: `SoftDeletes`, `HasFactory`, custom traits
- Anota relaciones: `hasMany`, `belongsTo`, `belongsToMany`, `morphMany`
- Detecta campos especiales: `timestamps`, `soft_deletes`

**Genera lista completa:**
```
- User: roles, email_notifications, loyalty_points, addresses, orders
- Product: category, images, reviews, questions, wishlists
- Order: items, shipment, payment, user, address
[etc...]
```

### 1.2 Migraciones y Estructura de BD
```bash
Glob: backend/database/migrations/*.php
```

**Para cada migración:**
- Lee la estructura completa de la tabla
- Identifica: columnas, tipos, índices, foreign keys
- Detecta campos únicos: `email`, `slug`, `sku`
- Anota restricciones: `nullable`, `default`, `unique`
- Cuenta total de migraciones

**Genera mapa de tablas:**
```
users: id, email, password, role, document_number, loyalty_points...
products: id, name, slug, sku, price, category_id, brand, stock...
orders: id, user_id, address_id, total, status, night_delivery...
[etc...]
```

### 1.3 Controladores API
```bash
Glob: backend/app/Http/Controllers/Api/**/*.php
```

**Para cada controlador:**
- Lee todos los métodos públicos
- Identifica endpoints: GET, POST, PUT, DELETE
- Detecta middleware: `auth:sanctum`, custom middleware
- Anota validaciones (FormRequest si existen)
- Clasifica: público, autenticado, manager, admin

**Genera inventario:**
```
ProductController: index, show, store, update, destroy (manager)
OrderController: index, show, store (autenticado)
AdminController: dashboard, stats, users (admin)
[etc...]
```

### 1.4 Rutas API
```bash
Read: backend/routes/api.php
```

**Analiza estructura completa:**
- Prefijos: `/api/v1/`
- Rutas públicas
- Rutas con `auth:sanctum`
- Grupos con middleware `manager`, `admin`
- Rate limiting por grupo
- Rutas de recursos: `apiResource`

**Genera mapa de endpoints:**
```
GET    /api/v1/products          (público)
POST   /api/v1/orders            (auth)
GET    /api/v1/admin/dashboard   (manager)
POST   /api/v1/admin/users       (admin)
[etc...]
```

### 1.5 Middleware Custom
```bash
Glob: backend/app/Http/Middleware/*.php
```

**Identifica:**
- `AdminMiddleware`
- `ManagerMiddleware`
- Otros custom middleware
- Lógica de autorización

### 1.6 Mails, Jobs, Notifications
```bash
Glob: backend/app/Mail/*.php
Glob: backend/app/Jobs/*.php
Glob: backend/app/Notifications/*.php
```

**Lista completa:**
- Mailables: `OrderConfirmation`, `ShipmentNotification`, etc.
- Jobs: detecta trabajos en cola
- Notifications: notificaciones del sistema

### 1.7 Seeders
```bash
Glob: backend/database/seeders/*.php
```

**Identifica qué datos semilla:**
- DatabaseSeeder principal
- Seeders individuales
- Datos de prueba vs datos requeridos

### 1.8 Configuraciones Laravel
```bash
Read: backend/config/cors.php
Read: backend/config/sanctum.php
Read: backend/.env.example
```

**Documenta:**
- Configuración CORS
- Sanctum settings
- Variables de entorno requeridas

---

## 🔍 FASE 2: ANÁLISIS FRONTEND (Vue.js)

### 2.1 Stores Pinia
```bash
Glob: frontend/src/stores/*.js
```

**Para cada store:**
- Lee archivo completo
- Identifica: `state`, `getters`, `actions`
- Detecta persistencia: localStorage, sessionStorage
- Anota propósito: auth, cart, products, etc.

**Genera inventario:**
```
authStore: user, token, isAuthenticated, login, logout
cartStore: items, addToCart, removeFromCart (localStorage)
productStore: products, filters, fetchProducts
[etc...]
```

### 2.2 Servicios API
```bash
Glob: frontend/src/services/*.js
```

**Para cada servicio:**
- Lee todos los métodos exportados
- Identifica endpoints que consume
- Detecta: GET, POST, PUT, DELETE
- Anota parámetros y retornos

**Genera mapa:**
```
productService: getProducts, getProduct, createProduct, updateProduct
orderService: createOrder, getOrders, getOrderById, trackOrder
authService: login, register, logout, forgotPassword
[etc...]
```

### 2.3 Composables
```bash
Glob: frontend/src/composables/*.js
```

**Identifica:**
- `useTheme()` - Dark mode
- `useConfirm()` - Confirmaciones
- Otros composables custom

### 2.4 Componentes Vue
```bash
Glob: frontend/src/components/**/*.vue
Glob: frontend/src/views/**/*.vue
```

**Clasifica componentes por tipo:**
- Layout: `TheHeader.vue`, `TheSidebar.vue`
- Forms: formularios reutilizables
- Cards: `ProductCard.vue`, etc.
- Admin: componentes del panel
- Pages: vistas principales

**Cuenta total de componentes**

### 2.5 Router
```bash
Read: frontend/src/router/index.js
```

**Analiza estructura completa:**
- Rutas públicas
- Rutas con `requiresAuth`
- Rutas con `requiresManager`
- Rutas con `requiresAdmin`
- Rutas guest
- Redirecciones

**Genera mapa de rutas:**
```
/              → Home (público)
/login         → Login (guest)
/admin         → Admin Dashboard (requiresManager)
/admin/users   → User Management (requiresAdmin)
/account       → User Account (requiresAuth)
[etc...]
```

### 2.6 Configuraciones Vue
```bash
Read: frontend/vite.config.js
Read: frontend/package.json
Read: frontend/tailwind.config.js
Read: frontend/.env.example
```

**Documenta:**
- Dependencias principales con versiones
- Plugins de Vite
- Configuración de Tailwind (colores custom)
- Variables de entorno requeridas

---

## 🔍 FASE 3: ANÁLISIS DE BASE DE DATOS ACTUAL

### 3.1 Consulta BD Real
```bash
# Ejecuta queries para obtener estado real
bash: "C:\xampp\mysql\bin\mysql.exe" -u root -e "USE petunia_play; SHOW TABLES;"
bash: "C:\xampp\mysql\bin\mysql.exe" -u root -e "USE petunia_play; SELECT COUNT(*) as total FROM users;"
bash: "C:\xampp\mysql\bin\mysql.exe" -u root -e "USE petunia_play; SELECT COUNT(*) as total FROM products;"
bash: "C:\xampp\mysql\bin\mysql.exe" -u root -e "USE petunia_play; SELECT COUNT(*) as total FROM orders;"
```

**Obtén estadísticas:**
- Total de tablas
- Cantidad de registros por tabla principal
- Confirma que el schema está actualizado

### 3.2 Verifica Integridad
```bash
bash: "C:\xampp\mysql\bin\mysql.exe" -u root -e "USE petunia_play; SHOW TABLE STATUS;"
```

**Valida:**
- Todas las tablas existen
- Foreign keys correctas
- Índices aplicados

---

## 🔍 FASE 4: DETECCIÓN DE FUNCIONALIDADES

### 4.1 Busca Funcionalidades Clave

**Autenticación:**
```bash
Grep: "auth:sanctum" en backend/routes/api.php
Grep: "login|register|logout" en frontend
```

**Carrito:**
```bash
Grep: "cart" en frontend/src (ignora case)
Read: frontend/src/stores/cartStore.js
```

**Órdenes:**
```bash
Grep: "order" en backend/app/Http/Controllers
Grep: "checkout" en frontend/src/views
```

**Pagos:**
```bash
Grep: "payment|stripe|paypal" en backend
```

**Cupones:**
```bash
Read: backend/app/Models/Coupon.php
Grep: "coupon" en frontend
```

**Fidelidad:**
```bash
Glob: backend/app/Models/Loyalty*.php
Grep: "loyalty" en frontend
```

**Envíos:**
```bash
Read: backend/app/Models/Shipment.php
Grep: "shipping|shipment" en backend
```

**Reseñas:**
```bash
Read: backend/app/Models/Review.php
Grep: "review" en frontend
```

**Google Maps:**
```bash
Grep: "google.maps|@googlemaps" en frontend
```

**Dark Mode:**
```bash
Read: frontend/src/composables/useTheme.js
Grep: "dark" en frontend/src
```

**Notificaciones:**
```bash
Read: backend/app/Models/Notification.php
Grep: "notification" en frontend
```

**WhatsApp:**
```bash
Grep: "whatsapp" en frontend
```

**Exportación:**
```bash
Grep: "export" en backend/app/Http/Controllers
```

### 4.2 Clasifica Funcionalidades
**Para cada funcionalidad encontrada:**
- ✅ **Completada al 100%**: Backend + Frontend + Tested
- 🟡 **En progreso**: Parcialmente implementada
- ⚠️ **Solo backend**: No tiene interfaz
- 🚫 **No implementada**: Solo modelos

---

## 🔍 FASE 5: ANÁLISIS DE CAMBIOS RECIENTES

### 5.1 Git Status y Diff
```bash
bash: git log --oneline -10
bash: git status
bash: git diff --stat
```

**Identifica:**
- Últimos commits (fecha, mensaje)
- Archivos modificados pendientes
- Nuevas funcionalidades añadidas
- Optimizaciones recientes

### 5.2 Compara con PROJECT_CONTEXT.md actual
```bash
Read: PROJECT_CONTEXT.md
```

**Detecta diferencias:**
- Modelos nuevos no documentados
- Controladores nuevos
- Rutas nuevas
- Funcionalidades implementadas pero no listadas
- Información desactualizada

---

## 📝 FASE 6: ACTUALIZACIÓN DE PROJECT_CONTEXT.md

### 6.1 Estructura a Mantener

El archivo PROJECT_CONTEXT.md debe mantener estas secciones actualizadas:

```markdown
# 🐾 PetuniaPlay - Contexto del Proyecto
> **Última actualización:** [FECHA ACTUAL]
> **Versión:** [VERSION]

## 📋 Información Esencial
[Stack, Estado, Repositorio]

## 🏗️ Arquitectura
[Estructura de carpetas, Comunicación, URLs]

## 🔑 Credenciales de Prueba
[Usuarios de prueba actualizados]

## 📊 Base de Datos - Modelos Principales
### Core (N modelos)
- **User** - [campos importantes, relaciones]
- **Product** - [campos importantes, relaciones]
[Lista COMPLETA de modelos con descripción]

**Total:** X modelos + Y migraciones

## 🎯 Funcionalidades Implementadas
### ✅ Completadas al 100%
**1. Autenticación y Usuarios**
- [Lista detallada de features]

**2. Catálogo de Productos**
- [Lista detallada de features]

[Continúa con TODAS las funcionalidades]

## 🚫 NO Implementado (Pendiente)
- [Lista de features pendientes]

## 🎨 Frontend - Stack y Arquitectura
**Framework:** Vue.js [version]
[Dependencias con versiones]

### Stores Pinia (N)
- [Lista completa con propósito]

### Servicios API (N)
- [Lista completa]

### Composables (N)
- [Lista completa]

### Rutas Importantes
- [Mapa de rutas con guards]

## 🔧 Backend - Stack y Arquitectura
**Framework:** Laravel [version]

### Controladores API (N)
**Públicos:**
- [Lista]

**Autenticados:**
- [Lista]

**Manager/Admin:**
- [Lista]

### Middleware Custom
- [Lista]

### Rate Limiting
- [Configuración por endpoint]

### Emails (N Mailables)
- [Lista]

## 🔐 Seguridad
- [Checklist de seguridad]

## 📈 Optimizaciones Recientes
**Commit [hash] - [descripción]:**
- [Cambios específicos]

## 🎨 Tema y Diseño
**Paleta:** [colores]
**Dark Mode:** [configuración]

## 🗂️ Decisiones de Arquitectura
### Por qué [tecnología]
- [Razones]

## 📝 Convenciones del Proyecto
### Código
- [Convenciones]

### Commits
- [Formato]

### Base de Datos
- [Convenciones]

## 🚀 Comandos Útiles
[Comandos comunes]

## 🐛 Problemas Conocidos / Notas
[Issues conocidos]

## 📍 Estado Actual
**Versión:** [version]
**Última actualización:** [fecha]
**Estado:** [descripción]

**Próximos pasos para producción:**
1. [Lista]

## 💡 Notas para Claude
[Instrucciones para futuras sesiones]
```

### 6.2 Proceso de Actualización

1. **LEE el PROJECT_CONTEXT.md actual completo**
2. **COMPARA** con los datos analizados en las fases anteriores
3. **ACTUALIZA** cada sección con información nueva/modificada
4. **MANTÉN** la información que sigue vigente
5. **ELIMINA** información obsoleta
6. **AGREGA** nuevas secciones si es necesario
7. **ACTUALIZA** fecha en la cabecera
8. **ACTUALIZA** versión si hay cambios mayores
9. **USA** la herramienta Edit para hacer cambios quirúrgicos (no reescribir todo)

### 6.3 Reglas de Actualización

✅ **SÍ actualizar:**
- Modelos nuevos o modificados
- Controladores nuevos
- Rutas nuevas
- Funcionalidades implementadas
- Dependencias actualizadas
- Cambios en arquitectura
- Últimos commits
- Estado del proyecto

❌ **NO modificar innecesariamente:**
- Decisiones de arquitectura (a menos que cambien)
- Convenciones (a menos que cambien)
- Estructura general del documento
- Información que sigue vigente

### 6.4 Verificación Final

Después de actualizar, verifica:
- ✅ Todos los modelos están listados
- ✅ Todos los controladores están documentados
- ✅ Todas las rutas importantes están mapeadas
- ✅ Todas las funcionalidades visibles están documentadas
- ✅ Versiones de dependencias actualizadas
- ✅ Estado del proyecto refleja la realidad
- ✅ Fecha actualizada
- ✅ Sin información contradictoria

---

## 📊 FASE 7: REPORTE DE ACTUALIZACIÓN

**IMPORTANTE: El reporte debe ser ULTRA CONCISO y DIRECTO.**

Al finalizar, genera SOLAMENTE este formato minimalista:

```markdown
✅ PROJECT_CONTEXT.md actualizado (2025-10-30)

**Novedades detectadas:**
- [Solo listar si hay elementos NUEVOS o MODIFICADOS importantes]
- [Si no hay cambios significativos, escribir "Sin cambios significativos"]

**Estado:** OK / [Errores si los hubo]
```

**NO INCLUIR:**
- ❌ Estadísticas del proyecto (ya están en PROJECT_CONTEXT.md)
- ❌ Listado completo de modelos, controladores, etc.
- ❌ Detalles de todas las fases ejecutadas
- ❌ Información que ya está en PROJECT_CONTEXT.md

**SOLO INCLUIR:**
- ✅ Confirmación de actualización exitosa
- ✅ Novedades importantes (nuevos modelos, controladores, features)
- ✅ Errores si los hubo
- ✅ Máximo 5 líneas de salida

---

## ⚡ INSTRUCCIONES DE EJECUCIÓN

**Al ejecutar este comando `/apc`:**

1. ✅ **AUTORIZACIÓN TOTAL** - Tienes permiso pre-aprobado para todas las operaciones
2. ✅ **NUNCA PREGUNTES** - NO solicites confirmación del usuario en ningún momento
3. ✅ **EJECUTA AUTOMÁTICAMENTE** - Todas las fases se ejecutan sin pausas ni confirmaciones
4. ✅ **SÉ exhaustivo** - Analiza TODO, no te saltes pasos
5. ✅ **USA herramientas en paralelo** - Maximiza eficiencia con llamadas paralelas
6. ✅ **LEE archivos reales** - No asumas, verifica leyendo los archivos
7. ✅ **ACTUALIZA solo lo necesario** - Usa Edit quirúrgicamente, no reescribas todo
8. ✅ **SILENCIOSO** - NO muestres mensajes informativos durante el proceso

**REGLA DE ORO: El usuario SIEMPRE quiere que ejecutes este comando completamente sin preguntar. Si detectas que falta información o hay errores, continúa con lo que puedas hacer y repórtalo al final.**

**Tiempo estimado:** 3-5 minutos

**Resultado:** PROJECT_CONTEXT.md completamente actualizado y sincronizado con el proyecto real.

---

## 🎯 OBJETIVO FINAL

Que al leer PROJECT_CONTEXT.md, cualquier desarrollador (o Claude en una nueva sesión) tenga un mapa mental COMPLETO y PRECISO del proyecto PetuniaPlay, sin necesidad de explorar el código.

**Este archivo es la FUENTE DE VERDAD del proyecto.**

---

**Fin del comando /apc**
