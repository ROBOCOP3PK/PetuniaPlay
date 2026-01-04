# PetuniaPlay - E-commerce para Mascotas

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![Vue.js](https://img.shields.io/badge/Vue.js-3-green)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange)
![Node](https://img.shields.io/badge/Node-20.19%20|%2022.12+-brightgreen)
![MercadoPago](https://img.shields.io/badge/MercadoPago-Integrado-009ee3)

Sistema completo de e-commerce especializado en productos para mascotas, construido con Laravel 12 (backend REST API) y Vue.js 3 (frontend SPA). Incluye sistema de fidelidad, cupones avanzados, control de despachos, pagos con Mercado Pago y cumplimiento legal colombiano.

---

## Características Principales

### Para Clientes
- Catálogo de productos con filtros avanzados (categoría, precio, marca)
- Búsqueda en tiempo real con autocompletado
- Pago seguro con Mercado Pago
- Sistema de cupones de descuento con validación en tiempo real
- Lista de deseos (wishlist) con persistencia
- Reseñas y calificaciones (solo productos comprados)
- Rastreo público de pedidos (número + email)
- Gestión de cuenta y preferencias de notificaciones
- Programa de fidelidad con recompensas por compras
- Sistema de preguntas y respuestas sobre productos
- Envío nocturno opcional con cargo adicional

### Para Administradores
- Dashboard con estadísticas en tiempo real (ventas, órdenes, stock)
- Gestión completa de inventario con alertas de stock bajo
- Control de despachos con alertas de órdenes antiguas
- Gestión de cupones con límites por cliente
- Gestión de programa de fidelidad (recompensas permanentes y campañas)
- Administración de usuarios y roles (solo admin)
- Sistema de emails con cumplimiento Ley 1581/2012
- Reportes exportables (Excel/PDF) con rate limiting
- Moderación de reseñas y respuesta a preguntas
- Configuraciones parametrizables (envíos, WhatsApp, horarios)

### Técnicas
- Autenticación con Laravel Sanctum (SPA authentication)
- UI moderna con PrimeVue + Tailwind CSS y Dark Mode
- Diseño 100% responsive
- Pagos integrados con Mercado Pago (checkout, webhooks, estados)
- Sistema de notificaciones por email con preferencias granulares
- Cumplimiento Ley 1581/2012 (Colombia) - Protección de datos
- Gestión automática de stock con alertas
- Rate limiting en endpoints críticos
- 24 modelos Eloquent con relaciones optimizadas
- API REST versionada (/api/v1/)

---

## Inicio Rápido

### Prerrequisitos

- PHP 8.2 o superior
- Composer 2.x
- Node.js 20.19+ o 22.12+ y npm
- MySQL/MariaDB 8.0+
- Git
- Cuenta de Mercado Pago (para pagos)

### Instalación

#### 1. Backend (Laravel)

```bash
# Clonar repositorio
git clone https://github.com/your-repo/petuniaplay.git
cd petuniaplay/backend

# Instalar dependencias
composer install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar base de datos en .env
# Editar DB_DATABASE, DB_USERNAME, DB_PASSWORD

# Configurar Mercado Pago en .env
# MERCADOPAGO_ACCESS_TOKEN=tu_access_token
# MERCADOPAGO_PUBLIC_KEY=tu_public_key
# MERCADOPAGO_TEST_MODE=true

# Migrar y poblar base de datos
php artisan migrate --seed

# Link storage para imágenes
php artisan storage:link

# Iniciar servidor
php artisan serve
# Backend disponible en: http://localhost:8000
```

#### 2. Frontend (Vue.js)

```bash
cd ../frontend

# Instalar dependencias
npm install

# Configurar entorno
cp .env.example .env

# Editar .env:
# VITE_API_URL=http://localhost:8000/api/v1

# Iniciar servidor de desarrollo
npm run dev
# Frontend disponible en: http://localhost:5173
```

### Acceder al Sistema

**URL:** http://localhost:5173

**Usuarios de Prueba:**

| Rol | Email | Contraseña | Permisos |
|-----|-------|------------|----------|
| Admin | admin@petuniaplay.com | 2025 | Acceso completo al sistema |
| Manager | manager@petuniaplay.com | 2025 | Panel admin (excepto gestión de usuarios) |
| Cliente | (generados por seeder) | 2025 | Compras, wishlist, reseñas, fidelidad |

**Nota:** Los clientes de prueba son generados automáticamente por el seeder (20 usuarios).

---

## Arquitectura

```
┌──────────────────────────────────────────┐
│           FRONTEND (Vue.js 3)            │
│         http://localhost:5173            │
│                                          │
│  • Vue Router (navegación)               │
│  • Pinia (state management)              │
│  • Axios (HTTP client)                   │
│  • PrimeVue + Tailwind CSS (UI)          │
│  • Mercado Pago SDK (pagos)              │
└────────────┬─────────────────────────────┘
             │ REST API (JSON)
             │
┌────────────▼─────────────────────────────┐
│          BACKEND (Laravel 12)            │
│         http://localhost:8000            │
│                                          │
│  • Controllers (lógica de control)       │
│  • Services (lógica de negocio)          │
│  • Models (Eloquent ORM)                 │
│  • Sanctum (autenticación)               │
│  • Mercado Pago SDK (pagos)              │
│  • Mailer (notificaciones)               │
└────────────┬─────────────────────────────┘
             │ Eloquent ORM
             │
┌────────────▼─────────────────────────────┐
│        BASE DE DATOS (MySQL)             │
│                                          │
│  • users, products, categories           │
│  • orders, order_items, payments         │
│  • shipments, addresses                  │
│  • coupons, reviews, wishlists           │
└──────────────────────────────────────────┘
```

---

## Estado del Proyecto

### Completado (100% Funcional en Desarrollo)

**Core del E-commerce:**
- [x] Sistema de autenticación completo (Laravel Sanctum)
- [x] Catálogo de productos con filtros avanzados y búsqueda en tiempo real
- [x] Carrito de compras y checkout (guest y autenticado)
- [x] Gestión de órdenes con estados y tracking
- [x] Sistema de envíos con validación de cobertura
- [x] Panel de administración con roles (customer, manager, admin)
- [x] Pagos con Mercado Pago (checkout, webhooks, estados)

**Funcionalidades Avanzadas:**
- [x] Sistema de cupones con límites por cliente
- [x] Programa de fidelidad (recompensas permanentes y campañas)
- [x] Reseñas y calificaciones con moderación
- [x] Preguntas y respuestas sobre productos
- [x] Lista de deseos con persistencia
- [x] Control de despachos con alertas
- [x] Notificaciones in-app y por email

**Integración y Configuración:**
- [x] Integración con Mercado Pago (pagos online)
- [x] Sistema de emails con cumplimiento Ley 1581/2012
- [x] Reportes exportables (Excel/PDF) con rate limiting
- [x] UI responsive con dark mode (paleta Beagle)
- [x] Configuraciones parametrizables (envíos, WhatsApp, horarios)
- [x] Envío nocturno opcional
- [x] WhatsApp button flotante

### Pendiente para Producción

- [ ] Configuración de servidor de producción
- [ ] Certificado SSL
- [ ] Servidor SMTP para emails (SendGrid/SES)
- [ ] CDN para imágenes (Cloudinary/S3)
- [ ] Credenciales de producción de Mercado Pago
- [ ] Sistema de monitoreo (Sentry/Bugsnag)
- [ ] Backups automatizados

**Estadísticas del Proyecto:**
- 24 modelos Eloquent
- 44 migraciones de base de datos
- 27 controladores API
- 38 vistas Vue.js
- 31 componentes reutilizables
- 20 servicios API (frontend)
- 8 servicios (backend)
- 7 stores Pinia
- 6 composables
- 10 seeders

---

## Stack Tecnológico

### Backend
- **Framework:** Laravel 12
- **Lenguaje:** PHP 8.2+
- **Base de Datos:** MySQL 8.0+ / MariaDB 10.6+
- **Autenticación:** Laravel Sanctum 4.2
- **ORM:** Eloquent
- **Pagos:** mercadopago/dx-php 3.7
- **Exportaciones:** maatwebsite/excel 3.1, barryvdh/laravel-dompdf 3.1
- **Testing:** PHPUnit 11.5, Laravel Dusk 8.3

### Frontend
- **Framework:** Vue.js 3.5.22 (Composition API con `<script setup>`)
- **Build Tool:** Vite 7.1.11
- **Router:** Vue Router 4.6.3 (guards multinivel)
- **State:** Pinia 3.0.3 (stores modulares)
- **UI Components:** PrimeVue 4.4.1
- **HTTP:** Axios 1.12.2 (interceptors)
- **CSS:** Tailwind CSS 3.4.18 + PrimeIcons 7.0.0
- **Pagos:** @mercadopago/sdk-js 0.0.3
- **Notifications:** vue-toastification 2.0.0-rc.5
- **Node:** ^20.19.0 || >=22.12.0

### Servicios Externos
- **Pagos:** Mercado Pago (checkout, webhooks)
- **Emails (Dev):** Mailtrap / Log
- **Emails (Prod):** SendGrid / Amazon SES (configurar)

---

## Guías Rápidas

### Para Clientes

1. **Comprar un producto:**
   - Navegar catálogo → Agregar al carrito → Checkout → Completar datos → Pagar

2. **Rastrear pedido:**
   - Footer → "Rastrear Pedido" → Ingresar número de orden y email

3. **Escribir reseña:**
   - Mi Cuenta → Mis Pedidos → Ver pedido → Seleccionar producto → Escribir reseña

### Para Administradores

1. **Agregar producto:**
   - Panel Admin → Productos → + Nuevo Producto → Llenar formulario → Publicar

2. **Crear envío para orden:**
   - Panel Admin → Envíos → Pendientes de Despacho → Crear Envío

3. **Crear cupón:**
   - Panel Admin → Cupones → + Nuevo Cupón → Configurar → Guardar

---

## Panel de Administración

El sistema cuenta con un panel de administración completo accesible en `/admin`. A continuación se detallan todas las secciones disponibles:

### 1. Dashboard (`/admin`)
Centro de control con estadísticas en tiempo real:
- **Tarjetas de resumen:** Ingresos totales, ingresos del mes, total de pedidos, productos y usuarios
- **Alertas de inventario:** Notificación automática de productos con stock bajo o agotados
- **Últimos pedidos:** Lista de los pedidos más recientes con estado y monto
- **Productos con bajo stock:** Vista rápida de productos que necesitan reabastecimiento
- **Productos más vendidos:** Top de ventas del último mes
- **Exportar reportes:** Generación de reportes de ventas en Excel con filtros por fecha y agrupación (día/semana/mes)

### 2. Gestión de Productos (`/admin/products`)
Administración completa del catálogo de productos:
- **Listado con filtros:** Búsqueda por nombre/SKU, filtro por categoría y estado de stock
- **Edición rápida:** Modificar precio y stock directamente desde la tabla
- **Crear/Editar producto:**
  - Información básica: nombre, categoría, SKU, marca, peso, descripción
  - Precio e inventario: precio regular, precio de oferta, stock, umbral de stock bajo
  - Imágenes: carga múltiple con imagen principal
  - Estado: activo/inactivo, destacado
- **Exportar a Excel:** Descarga del catálogo completo o filtrado
- **Indicadores visuales:** Badges de stock bajo, agotado, activo/inactivo

### 3. Gestión de Categorías (`/admin/categories`)
Organización jerárquica de productos:
- **Listado con filtros:** Búsqueda por nombre, filtro por estado
- **Crear/Editar categoría:**
  - Nombre y slug (URL amigable)
  - Descripción e imagen
  - Sección de animal (Perros, Gatos, etc.)
  - Categoría padre (para subcategorías)
  - Orden de visualización
- **Jerarquía:** Soporte para categorías padre e hijas
- **Activar/Desactivar:** Toggle de estado desde la tabla

### 4. Secciones de Animales (`/admin/animal-sections`)
Configuración de las secciones principales del catálogo:
- **Gestión de secciones:** Perros, Gatos, Aves, Peces, etc.
- **Iconos personalizados:** Emojis o iconos para cada sección
- **Orden de visualización:** Control del orden en el menú

### 5. Gestión de Pedidos (`/admin/orders`)
Control completo de órdenes:
- **Listado con filtros:**
  - Búsqueda por número de pedido o cliente
  - Filtro por estado (Pendiente, Procesando, Enviado, Entregado, Cancelado)
  - Filtro por fecha
- **Información del pedido:** Número, cliente, fecha, total, productos
- **Cambio de estado:** Actualización del estado del pedido
- **Exportar:** Excel y PDF con los filtros aplicados
- **Detalle completo:** Vista de productos, dirección de envío, información de pago

### 6. Gestión de Cupones (`/admin/coupons`)
Sistema avanzado de descuentos:
- **Estadísticas:** Total de cupones, activos, usos totales, cupón más usado
- **Listado con filtros:** Búsqueda por código, filtro por estado
- **Crear/Editar cupón:**
  - Código único (mayúsculas automáticas)
  - Tipo: porcentaje o monto fijo
  - Valor del descuento
  - Monto mínimo de compra
  - Límite de usos (global)
  - Vigencia: fecha inicio y fin
- **Toggle de estado:** Activar/desactivar cupones
- **Protección:** No se pueden eliminar cupones ya utilizados

### 7. Gestión de Envíos (`/admin/shipments`)
Control de despachos y entregas:
- **Panel de estadísticas:**
  - Por despachar, listas para envío, en tránsito, entregados
  - Total histórico de despachados
- **Alertas urgentes:** Órdenes con más días esperando despacho
- **Pestañas:**
  - Pendientes de despacho: órdenes pagadas sin envío creado
  - Enviados: historial de envíos con estados
- **Crear envío:**
  - Transportadora y número de guía
  - Notas del envío
  - Notificación automática al cliente
- **Actualizar estado:** Procesando → Enviado → En tránsito → Entregado

### 8. Configuración de Envíos (`/admin/shipping-config`)
Parámetros del sistema de entregas:
- **Zonas de cobertura:** Ciudades y regiones habilitadas
- **Tarifas de envío:** Costos por zona
- **Envío nocturno:** Configuración de horarios y recargo adicional
- **Umbral de envío gratis:** Monto mínimo para envío sin costo

### 9. Programa de Lealtad (`/admin/loyalty`)
Sistema de puntos y recompensas:
- **Toggle global:** Activar/desactivar el programa completo
- **Estadísticas:**
  - Recompensas activas
  - Canjes pendientes y completados
  - Órdenes generadas por canjes
- **Pestañas:**
  - **Recompensas:** Crear y gestionar recompensas disponibles
    - Permanentes o por campaña (con fechas)
    - Puntos requeridos
    - Tipo: descuento porcentual, monto fijo, producto gratis, envío gratis
  - **Canjes:** Historial y gestión de canjes de clientes
    - Aprobar/rechazar canjes pendientes
    - Ver historial completo
  - **Configuración:** Reglas del programa
    - Puntos por compra (ej: 1 punto por cada $1,000)
    - Vigencia de puntos
    - Mínimo para canjear

### 10. Gestión de Preguntas (`/admin/questions`)
Moderación de preguntas sobre productos:
- **Listado:** Preguntas pendientes de respuesta
- **Responder:** Formulario para responder preguntas de clientes
- **Publicar/Ocultar:** Control de visibilidad de preguntas y respuestas
- **Notificación:** Email automático al cliente cuando se responde

### 11. Gestión de Usuarios (`/admin/users`) *(Solo Admin)*
Administración de cuentas:
- **Listado con filtros:**
  - Búsqueda por nombre o email
  - Filtro por rol (Cliente, Gestor, Admin)
  - Filtro por estado (Activo, Inactivo)
- **Cambio de rol:** Dropdown para modificar rol directamente
- **Toggle de estado:** Activar/desactivar usuarios
- **Detalle de usuario:** Modal con información completa
- **Protección:** No se puede modificar el usuario actual

### 12. Configuración del Sitio (`/admin/site-config`) *(Solo Admin)*
Parámetros generales del e-commerce:
- **Botón de WhatsApp:**
  - Habilitar/deshabilitar botón flotante
  - Número de WhatsApp (formato internacional)
  - Mensaje predeterminado
  - Vista previa del enlace
- **Información de contacto:** Teléfono, email de soporte
- **Redes sociales:** Enlaces a perfiles
- **Configuración de emails:** Plantillas y preferencias

### Roles y Permisos

| Sección | Customer | Manager | Admin |
|---------|:--------:|:-------:|:-----:|
| Dashboard | - | ✅ | ✅ |
| Productos | - | ✅ | ✅ |
| Categorías | - | ✅ | ✅ |
| Secciones Animales | - | ✅ | ✅ |
| Pedidos | - | ✅ | ✅ |
| Cupones | - | ✅ | ✅ |
| Envíos | - | ✅ | ✅ |
| Config. Envíos | - | ✅ | ✅ |
| Programa Lealtad | - | ✅ | ✅ |
| Preguntas | - | ✅ | ✅ |
| **Usuarios** | - | - | ✅ |
| **Config. Sitio** | - | - | ✅ |

---

## Estructura del Proyecto

```
PetuniaPlay/
├── backend/                      # API Laravel 12
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/      # 27 controladores REST
│   │   │   │   └── Api/Admin/    # Controladores admin (Loyalty)
│   │   │   ├── Middleware/       # AdminMiddleware, ManagerMiddleware
│   │   │   └── Resources/        # API Resources
│   │   ├── Models/               # 24 modelos Eloquent
│   │   ├── Services/             # 8 servicios de negocio
│   │   ├── Mail/                 # 4 Mailables (transaccionales)
│   │   └── Exports/              # 3 Exports (Excel/PDF)
│   ├── database/
│   │   ├── migrations/           # 44 migraciones
│   │   ├── seeders/              # 10 seeders
│   │   └── factories/
│   └── routes/
│       └── api.php               # Rutas API versionadas (/api/v1)
│
├── frontend/                     # SPA Vue.js 3
│   ├── src/
│   │   ├── components/           # 31 componentes reutilizables
│   │   ├── views/                # 38 vistas (públicas, auth, admin)
│   │   ├── services/             # 20 servicios API (Axios)
│   │   ├── stores/               # 7 stores Pinia
│   │   ├── router/               # Vue Router con guards
│   │   ├── composables/          # 6 composables (useTheme, useConfirm, etc.)
│   │   └── layouts/              # AdminLayout
│   └── public/
│
└── README.md                     # Este archivo
```

---

## Seguridad

- Laravel Sanctum (SPA authentication con cookies httpOnly)
- Middleware de roles (customer, manager, admin)
- Validación doble (frontend y backend)
- Protección CSRF nativa de Laravel
- Encriptación de tokens sensibles (unsubscribe, reset password)
- Rate limiting en endpoints críticos (auth, checkout, export)
- Passwords hasheados con bcrypt (12 rounds)
- Sanitización de inputs y protección XSS
- Guards de navegación en Vue Router
- Cumplimiento Ley 1581/2012 (protección de datos Colombia)

---

## Sistema de Emails

### Emails Automáticos

1. **Confirmación de Orden**
   - Disparado al crear orden con pago exitoso
   - Incluye detalles, productos, total, link de tracking

2. **Actualización de Envío**
   - Disparado al crear/actualizar shipment
   - Estados: Despachado, En tránsito, Entregado

3. **Cumplimiento Legal (Ley 1581/2012)**
   - Footer con información de derechos ARCO
   - Link de unsubscribe con token encriptado
   - Respeto a preferencias del usuario

### Configuración

**Desarrollo:** Mailtrap (ver emails en sandbox)

**Producción:** Configurar SMTP real:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_sendgrid_api_key
```

---

## Mercado Pago

### Configuración

El sistema utiliza Mercado Pago como pasarela de pagos. Configura las siguientes variables en el backend `.env`:

```env
# Mercado Pago - Obtener credenciales en https://www.mercadopago.com.co/developers
MERCADOPAGO_ACCESS_TOKEN=APP_USR-xxxx     # Access Token (privado)
MERCADOPAGO_PUBLIC_KEY=APP_USR-xxxx       # Public Key (público)
MERCADOPAGO_TEST_MODE=true                # true = sandbox, false = producción
```

### Flujo de Pago

1. **Checkout:** El cliente completa el carrito y procede al pago
2. **Preferencia:** El backend crea una preferencia de pago en Mercado Pago
3. **Redirección:** El cliente es redirigido a Mercado Pago para pagar
4. **Webhook:** Mercado Pago notifica al backend el estado del pago
5. **Confirmación:** El sistema actualiza la orden y envía email de confirmación

### Endpoints de Pago

- `POST /api/v1/payments/create-preference` - Crear preferencia de pago
- `POST /api/v1/payments/webhook` - Recibir notificaciones de Mercado Pago
- `GET /api/v1/payments/status` - Consultar estado del pago

### URLs de Retorno (configurar en producción)

- **Success:** `https://tudominio.com/payment/success`
- **Failure:** `https://tudominio.com/payment/failure`
- **Pending:** `https://tudominio.com/payment/pending`

---

## Localización

**Idioma:** Español (Colombia)
**Moneda:** Peso Colombiano (COP)
**Impuesto:** IVA 19%
**Formato de fecha:** DD/MM/YYYY
**Marco Legal:** Ley 1581/2012 (Protección de datos personales)
**Pasarela de Pagos:** Mercado Pago (Colombia)

---

## Desarrollador

**David González**
- Portfolio: [https://robocop3pk.github.io/Portafolio/](https://robocop3pk.github.io/Portafolio/)
- Email: contacto@petuniaplay.com
- Full Stack Developer

---

## Licencia

Proyecto privado. Todos los derechos reservados © 2025 PetuniaPlay

---

## Soporte

**Para usuarios del sistema:**
- Email: soporte@petuniaplay.com
- Teléfono: +57 305 759 4088

---

## Comandos Útiles

```bash
# Backend (Laravel)
php artisan migrate:fresh --seed  # Reset DB completo con datos de prueba
php artisan route:list            # Ver todas las rutas API
php artisan tinker                # REPL de Laravel (explorar modelos)
php artisan storage:link          # Link storage público (imágenes)
php artisan queue:work            # Procesar trabajos en cola
php artisan db:show               # Información de la base de datos
php artisan serve                 # Servidor dev (puerto 8000)

# Frontend (Vue.js)
npm run dev                       # Servidor desarrollo (puerto 5173)
npm run build                     # Build para producción
npm run preview                   # Preview del build
```

---

<div align="center">
  <p><strong>PetuniaPlay © 2025</strong></p>
</div>
