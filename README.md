# PetuniaPlay - E-commerce para Mascotas

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![Vue.js](https://img.shields.io/badge/Vue.js-3-green)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange)
![Node](https://img.shields.io/badge/Node-20.19+-brightgreen)

Sistema completo de e-commerce especializado en productos para mascotas, construido con Laravel 12 (backend REST API) y Vue.js 3 (frontend SPA). Incluye sistema de fidelidad, cupones avanzados, control de despachos, integración con Google Maps y cumplimiento legal colombiano.

---

## Características Principales

### Para Clientes
- Catálogo de productos con filtros avanzados (categoría, precio, marca)
- Búsqueda en tiempo real con autocompletado
- Selección de dirección con Google Maps (pin arrastrable, validación de cobertura)
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
- Integración con Google Maps API (Places, Geocoding, validación de cobertura)
- Sistema de notificaciones por email con preferencias granulares
- Cumplimiento Ley 1581/2012 (Colombia) - Protección de datos
- Gestión automática de stock con alertas
- Preparado para integración de pasarelas de pago (Stripe/PayU/MercadoPago)
- Rate limiting en endpoints críticos
- 22 modelos Eloquent con relaciones optimizadas
- API REST versionada (/api/v1/)

---

## Inicio Rápido

### Prerrequisitos

- PHP 8.2 o superior
- Composer 2.x
- Node.js 18+ y npm
- MySQL/MariaDB 8.0+
- Git

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
# VITE_API_URL=http://localhost:8000
# VITE_GOOGLE_MAPS_API_KEY=tu_api_key

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
│  • Tailwind CSS (estilos)                │
│  • Google Maps API (ubicación)           │
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
│  • Mailer (notificaciones)               │
└────────────┬─────────────────────────────┘
             │ Eloquent ORM
             │
┌────────────▼─────────────────────────────┐
│        BASE DE DATOS (MySQL)             │
│                                          │
│  • users, products, categories           │
│  • orders, order_items                   │
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
- [x] Sistema de envíos con Google Maps y validación de cobertura
- [x] Panel de administración con roles (customer, manager, admin)

**Funcionalidades Avanzadas:**
- [x] Sistema de cupones con límites por cliente
- [x] Programa de fidelidad (recompensas permanentes y campañas)
- [x] Reseñas y calificaciones con moderación
- [x] Preguntas y respuestas sobre productos
- [x] Lista de deseos con persistencia
- [x] Control de despachos con alertas
- [x] Notificaciones in-app y por email

**Integración y Configuración:**
- [x] Integración con Google Maps (Places, Geocoding)
- [x] Sistema de emails con cumplimiento Ley 1581/2012
- [x] Reportes exportables (Excel/PDF) con rate limiting
- [x] UI responsive con dark mode (paleta Beagle)
- [x] Configuraciones parametrizables (envíos, WhatsApp, horarios)
- [x] Envío nocturno opcional
- [x] WhatsApp button flotante

### Pendiente para Producción

- [ ] Integración de pasarela de pagos real (Stripe/PayU/Mercado Pago)
- [ ] Configuración de servidor de producción
- [ ] Certificado SSL
- [ ] Servidor SMTP para emails (SendGrid/SES)
- [ ] CDN para imágenes (Cloudinary/S3)
- [ ] Google Maps API key con restricciones
- [ ] Sistema de monitoreo (Sentry/Bugsnag)
- [ ] Backups automatizados

**Estadísticas del Proyecto:**
- 22 modelos Eloquent
- 37 migraciones de base de datos
- 31 tablas en MySQL
- 22 controladores API
- 33 vistas Vue.js
- 28 componentes reutilizables
- 17 servicios API
- 7 stores Pinia

---

## Stack Tecnológico

### Backend
- **Framework:** Laravel 12
- **Lenguaje:** PHP 8.2+
- **Base de Datos:** MySQL 8.0+ / MariaDB 10.6+
- **Autenticación:** Laravel Sanctum
- **ORM:** Eloquent

### Frontend
- **Framework:** Vue.js 3.5.22 (Composition API con `<script setup>`)
- **Build Tool:** Vite 7.1.11
- **Router:** Vue Router 4.6.3 (guards multinivel)
- **State:** Pinia 3.0.3 (stores modulares)
- **UI Components:** PrimeVue 4.4.1
- **HTTP:** Axios 1.12.2 (interceptors)
- **CSS:** Tailwind CSS 3.4.18 + PrimeIcons 7.0.0
- **Maps:** @googlemaps/js-api-loader 2.0.1
- **Notifications:** vue-toastification 2.0.0-rc.5
- **Node:** ^20.19.0 || >=22.12.0

### Servicios Externos
- **Mapas:** Google Maps API (Places, Geocoding)
- **Emails (Dev):** Mailtrap
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

## Estructura del Proyecto

```
PetuniaPlay/
├── backend/                 # API Laravel 12
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/Api/  # 22 controladores REST
│   │   │   ├── Middleware/       # AdminMiddleware, ManagerMiddleware
│   │   │   └── Resources/        # API Resources
│   │   ├── Models/               # 22 modelos Eloquent
│   │   ├── Services/             # Lógica de negocio
│   │   ├── Mail/                 # 4 Mailables (transaccionales)
│   │   └── Exports/              # Exportación Excel/PDF
│   ├── database/
│   │   ├── migrations/           # 37 migraciones (31 tablas)
│   │   ├── seeders/              # 7 seeders
│   │   └── factories/
│   └── routes/
│       └── api.php               # Rutas API versionadas (/api/v1)
│
├── frontend/                     # SPA Vue.js 3
│   ├── src/
│   │   ├── components/           # 28 componentes reutilizables
│   │   ├── views/                # 33 vistas (públicas, auth, admin)
│   │   ├── services/             # 17 servicios API (Axios)
│   │   ├── stores/               # 7 stores Pinia
│   │   ├── router/               # Vue Router con guards
│   │   └── composables/          # useTheme, useConfirm
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

## Localización

**Idioma:** Español (Colombia)
**Moneda:** Peso Colombiano (COP)
**Impuesto:** IVA 19%
**Formato de fecha:** DD/MM/YYYY
**Marco Legal:** Ley 1581/2012 (Protección de datos personales)
**Zona de Cobertura:** Bogotá y 50 km a la redonda (validación con Google Maps)

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
