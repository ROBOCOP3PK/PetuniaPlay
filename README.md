# 🐾 PetuniaPlay - E-commerce para Mascotas

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![Vue.js](https://img.shields.io/badge/Vue.js-3-green)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange)

Sistema completo de e-commerce especializado en productos para mascotas, construido con Laravel 12 (backend) y Vue.js 3 (frontend).

---

## 🌟 Características Principales

### Para Clientes
- 🛒 Catálogo de productos con filtros avanzados
- 🔍 Búsqueda en tiempo real con autocompletado
- 📍 Selección de dirección con Google Maps
- 🎁 Sistema de cupones de descuento
- ❤️ Lista de deseos (wishlist)
- ⭐ Reseñas y calificaciones
- 📦 Rastreo público de pedidos
- 👤 Gestión de cuenta y preferencias

### Para Administradores
- 📊 Dashboard con estadísticas en tiempo real
- 📦 Gestión completa de inventario
- 🚚 Control de despachos y envíos
- 💰 Gestión de cupones y promociones
- 👥 Administración de usuarios y roles
- 📧 Sistema de emails con cumplimiento legal
- 📈 Reportes exportables (Excel/PDF)
- ✅ Moderación de reseñas

### Técnicas
- 🔐 Autenticación con Laravel Sanctum
- 🎨 UI moderna con Tailwind CSS y Dark Mode
- 📱 Diseño 100% responsive
- 🌍 Integración con Google Maps API
- 📧 Sistema de notificaciones por email
- 📋 Cumplimiento Ley 1581/2012 (Colombia)
- 🔄 Gestión automática de stock
- 💳 Preparado para integración de pasarelas de pago

---

## 📚 Documentación

Este proyecto cuenta con documentación completa y detallada:

| Documento | Descripción | Para Quién |
|-----------|-------------|------------|
| **[MANUAL_USUARIO.md](MANUAL_USUARIO.md)** | Guía completa de uso del sistema, organizada por módulos y roles | Usuarios finales, clientes, administradores, managers |
| **[DOCUMENTACION_TECNICA.md](DOCUMENTACION_TECNICA.md)** | Arquitectura, API, base de datos, servicios y deployment | Desarrolladores, DevOps, equipo técnico |
| **[PROYECTO_STATUS.md](PROYECTO_STATUS.md)** | Estado actual del proyecto, funcionalidades implementadas y pendientes | Product owners, stakeholders |
| **[TESTING_CHECKLIST.md](TESTING_CHECKLIST.md)** | Checklist exhaustivo de pruebas (500+ puntos) | QA, testers |
| **[SHIPPING_GUIDE.md](SHIPPING_GUIDE.md)** | Guía específica del sistema de control de despachos | Managers, personal de logística |

---

## 🚀 Inicio Rápido

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
| Admin | admin@petuniaplay.com | password | Acceso completo |
| Manager | manager@petuniaplay.com | password | Panel admin (sin gestión de usuarios) |
| Cliente | customer@petuniaplay.com | password | Compras y cuenta |

---

## 🏗️ Arquitectura

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

## 📊 Estado del Proyecto

### ✅ Completado (100% Funcional en Desarrollo)

- [x] Sistema de autenticación completo
- [x] Catálogo de productos con filtros avanzados
- [x] Carrito de compras y checkout
- [x] Gestión de órdenes y envíos
- [x] Panel de administración completo
- [x] Sistema de cupones
- [x] Reseñas y calificaciones
- [x] Integración con Google Maps
- [x] Sistema de emails con cumplimiento legal
- [x] Reportes y exportación de datos
- [x] UI responsive con dark mode
- [x] Control de despachos

### ⚠️ Pendiente para Producción

- [ ] Integración de pasarela de pagos real (Stripe/PayU/Mercado Pago)
- [ ] Configuración de servidor de producción
- [ ] Certificado SSL
- [ ] Servidor SMTP para emails (SendGrid/SES)
- [ ] CDN para imágenes (Cloudinary/S3)
- [ ] Google Maps API key con restricciones
- [ ] Sistema de monitoreo (Sentry/Bugsnag)
- [ ] Backups automatizados

**Ver detalles completos:** [PROYECTO_STATUS.md](PROYECTO_STATUS.md)

---

## 🛠️ Stack Tecnológico

### Backend
- **Framework:** Laravel 12
- **Lenguaje:** PHP 8.2+
- **Base de Datos:** MySQL 8.0+ / MariaDB 10.6+
- **Autenticación:** Laravel Sanctum
- **ORM:** Eloquent

### Frontend
- **Framework:** Vue.js 3 (Composition API)
- **Build Tool:** Vite 5
- **Router:** Vue Router 4
- **State:** Pinia 2
- **HTTP:** Axios
- **CSS:** Tailwind CSS 3

### Servicios Externos
- **Mapas:** Google Maps API (Places, Geocoding)
- **Emails (Dev):** Mailtrap
- **Emails (Prod):** SendGrid / Amazon SES (configurar)

---

## 📖 Guías Rápidas

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

**Ver guías completas:** [MANUAL_USUARIO.md](MANUAL_USUARIO.md)

---

## 🧪 Testing

El proyecto incluye un checklist exhaustivo de pruebas:
- 500+ puntos de verificación
- Cobertura de frontend, backend, seguridad, UI/UX
- Estimado: 8-12 horas de testing completo

**Ver checklist:** [TESTING_CHECKLIST.md](TESTING_CHECKLIST.md)

---

## 📦 Estructura del Proyecto

```
petuniaplay/
├── backend/                 # API Laravel
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   ├── Middleware/
│   │   │   └── Resources/
│   │   ├── Models/
│   │   ├── Services/
│   │   ├── Mail/
│   │   └── Exports/
│   ├── database/
│   │   ├── migrations/
│   │   ├── seeders/
│   │   └── factories/
│   └── routes/
│       └── api.php
│
├── frontend/               # App Vue.js
│   ├── src/
│   │   ├── components/
│   │   ├── views/
│   │   ├── services/
│   │   ├── stores/
│   │   └── router/
│   └── public/
│
├── MANUAL_USUARIO.md       # Guía de usuario
├── DOCUMENTACION_TECNICA.md # Documentación técnica
├── PROYECTO_STATUS.md      # Estado del proyecto
├── TESTING_CHECKLIST.md    # Checklist de pruebas
└── README.md              # Este archivo
```

---

## 🔒 Seguridad

- ✅ Laravel Sanctum para autenticación API
- ✅ Middleware de roles (Customer, Manager, Admin)
- ✅ Validación de inputs en frontend y backend
- ✅ Protección CSRF
- ✅ Encriptación de tokens sensibles
- ✅ Rate limiting
- ✅ Passwords hasheados con bcrypt

---

## 📧 Sistema de Emails

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

## 🌍 Internacionalización

**Idioma actual:** Español (Colombia)

**Localización:**
- Formato de moneda: Peso Colombiano (COP)
- Impuesto: IVA 19%
- Formatos de fecha: DD/MM/YYYY

---

## 🤝 Contribuir

Este es un proyecto privado. Para sugerencias o reportes de bugs, contactar al desarrollador.

---

## 👨‍💻 Desarrollador

**David González**
- Portfolio: [https://robocop3pk.github.io/Portafolio/](https://robocop3pk.github.io/Portafolio/)
- Email: contacto@petuniaplay.com
- Full Stack Developer

---

## 📄 Licencia

Proyecto privado. Todos los derechos reservados © 2025 PetuniaPlay

---

## 📞 Soporte

**Para usuarios del sistema:**
- Email: soporte@petuniaplay.com
- Teléfono: +57 305 759 4088

**Para desarrolladores:**
- Ver: [DOCUMENTACION_TECNICA.md](DOCUMENTACION_TECNICA.md)
- Consultar API: `/api/v1/` + endpoint

---

## 🎯 Roadmap Futuro

### Fase 2 (Post-Lanzamiento)
- [ ] Integración con transportadoras (tracking automático)
- [ ] Sistema de notificaciones push (PWA)
- [ ] Programa de puntos y lealtad
- [ ] Multi-idioma (Inglés)
- [ ] App móvil (React Native)

### Fase 3 (Expansión)
- [ ] Marketplace multi-vendedor
- [ ] Sistema de suscripciones
- [ ] Chat en vivo
- [ ] Integración con redes sociales
- [ ] Análisis predictivo de inventario

---

## 🏆 Funcionalidades Destacadas

### 1. Selección de Dirección con Mapa
Permite a los clientes:
- Escribir dirección manualmente
- Seleccionar ubicación en mapa con pin
- Validación de área de cobertura (50 km de Bogotá)
- Reverse geocoding (coordenadas → dirección)
- Guardar ubicación exacta para mejor precisión de entrega

### 2. Control de Despachos
Dashboard especializado para logística:
- Vista de órdenes pendientes vs despachadas
- Alertas de órdenes urgentes (4+ días)
- Creación rápida de envíos con tracking
- Actualización en tiempo real de estados
- Estadísticas de envío

### 3. Sistema de Cupones Avanzado
- Descuentos por porcentaje o monto fijo
- Condiciones: monto mínimo, límite de usos, fechas
- Estadísticas de uso
- Activación/desactivación flexible

### 4. Emails con Cumplimiento Legal
- Cumplimiento total Ley 1581/2012 (Colombia)
- Información de derechos ARCO
- Sistema de unsubscribe con token único
- Preferencias granulares por tipo de email

---

## 💡 Tips para Desarrolladores

### Comandos Útiles

```bash
# Backend
php artisan migrate:fresh --seed  # Reset DB
php artisan route:list            # Ver todas las rutas
php artisan tinker                # REPL de Laravel
php artisan storage:link          # Link storage público

# Frontend
npm run build                     # Build para producción
npm run preview                   # Preview del build
```

### Debug

```php
// Backend (Laravel)
dd($variable);           // Dump and die
logger()->info($data);   // Log
\DB::enableQueryLog();  // Ver queries SQL
```

```javascript
// Frontend (Vue)
console.log(data)        // Log básico
debugger                 // Breakpoint
```

### Acceso Rápido API

```bash
# Test endpoints con curl
curl http://localhost:8000/api/v1/products

# Con autenticación
curl -H "Authorization: Bearer YOUR_TOKEN" \
     http://localhost:8000/api/v1/user
```

---

**¿Necesitas ayuda?** Consulta la documentación correspondiente:
- Usuarios → [MANUAL_USUARIO.md](MANUAL_USUARIO.md)
- Desarrolladores → [DOCUMENTACION_TECNICA.md](DOCUMENTACION_TECNICA.md)
- Testing → [TESTING_CHECKLIST.md](TESTING_CHECKLIST.md)
- Estado → [PROYECTO_STATUS.md](PROYECTO_STATUS.md)

---

<div align="center">
  <p>Hecho con ❤️ para las mascotas 🐶🐱</p>
  <p><strong>PetuniaPlay © 2025</strong></p>
</div>
