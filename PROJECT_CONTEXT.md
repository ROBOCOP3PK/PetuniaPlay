# 🐾 PetuniaPlay - Contexto del Proyecto

> **Última actualización:** 2025-10-30
> **Versión:** 1.0 - Sistema completo funcional en desarrollo

---

## 📋 Información Esencial

**Tipo:** E-commerce especializado en productos para mascotas
**Stack:** Laravel 12 (Backend API) + Vue.js 3 (Frontend SPA)
**Base de Datos:** MySQL/MariaDB
**Estado:** 100% funcional en desarrollo, listo para integración de pagos y deployment

**Repositorio:** Monorepo con backend y frontend separados
**Desarrollador:** David González
**País:** Colombia (Ley 1581/2012 - protección de datos)

---

## 🏗️ Arquitectura

```
PetuniaPlay/
├── backend/          # Laravel 12 API REST
├── frontend/         # Vue.js 3 SPA
├── docs/            # Documentación
└── PROJECT_CONTEXT.md  # Este archivo
```

**Comunicación:**
- Frontend → Backend: REST API (Axios)
- Autenticación: Laravel Sanctum (Bearer tokens)
- CORS configurado para desarrollo local

**URLs Desarrollo:**
- Backend: http://localhost:8000
- Frontend: http://localhost:5173
- API Base: http://localhost:8000/api/v1

---

## 🔑 Credenciales de Prueba

```
Admin:    admin@petuniaplay.com / 2025
Manager:  manager@petuniaplay.com / 2025
Clientes: (generados por seeder) / 2025
```

**Roles del sistema:**
- `customer`: Cliente normal (compras, wishlist, reseñas)
- `manager`: Gestor (panel admin excepto gestión de usuarios)
- `admin`: Administrador total (acceso completo)

---

## 📊 Base de Datos - Modelos Principales

### Core (8 modelos)
- **User** - Usuarios con roles, email_notifications, loyalty fields
- **Product** - Productos con brand, low_stock_threshold, imágenes múltiples
- **Category** - Categorías jerárquicas
- **ProductImage** - Galería de imágenes por producto
- **Order** - Órdenes con night_delivery, coupon_id
- **OrderItem** - Items de cada orden
- **Address** - Direcciones con lat/lng (Google Maps)
- **Payment** - Pagos (preparado para pasarelas reales)

### Funcionalidades Avanzadas (9 modelos)
- **Coupon** - Cupones con max_usage_per_customer
- **CouponRedemption** - Historial de uso de cupones
- **Review** - Reseñas con aprobación manual
- **WishlistItem** - Lista de deseos
- **Shipment** - Envíos con tracking_number
- **Notification** - Notificaciones in-app
- **ProductQuestion** - Preguntas de clientes con respuestas

### Sistema de Fidelidad (3 modelos)
- **LoyaltyProgram** - Programa global de fidelidad
- **LoyaltyReward** - Recompensas (permanent/campaign)
- **LoyaltyRedemption** - Historial de canjes

### Configuración (2 modelos)
- **ShippingConfig** - Horarios de envío gratis, tarifas
- **SiteConfig** - WhatsApp, configuraciones generales

### Blog Base (2 modelos)
- **BlogPost**, **BlogCategory** (no implementados en frontend)

**Total:** 22 modelos + 35 migraciones

---

## 🎯 Funcionalidades Implementadas

### ✅ Completadas al 100%

**1. Autenticación y Usuarios**
- Registro, login, logout (Sanctum)
- Recuperación de contraseña por email
- Perfiles con foto, documento, teléfono
- Gestión de usuarios (solo admin)
- Preferencias de notificaciones

**2. Catálogo de Productos**
- Listado con filtros avanzados (categoría, precio, marca)
- Búsqueda en tiempo real con autocompletado
- Vista detallada con galería de imágenes
- Stock disponible y alertas de bajo inventario
- Productos destacados (featured)
- Gestión completa CRUD (managers)

**3. Carrito y Checkout**
- Carrito en localStorage (persistencia)
- Validación de cupones en tiempo real
- Selección de dirección con Google Maps (pin arrastrable)
- Cálculo automático de envío (gratis según horario/monto)
- Envío nocturno opcional (cargo extra)
- Validación de área de cobertura (50 km desde Bogotá)

**4. Sistema de Órdenes**
- Creación de órdenes (guest y autenticado)
- Historial de pedidos por usuario
- Tracking público con número de orden + email
- Estados: pending, processing, shipped, delivered, cancelled
- Emails automáticos de confirmación

**5. Control de Despachos**
- Dashboard de pendientes vs despachados
- Alertas de órdenes antiguas (4+ días)
- Creación de shipments con tracking
- Actualización de estados de envío
- Emails de notificación de envío
- Estadísticas de logística

**6. Sistema de Cupones**
- Tipos: porcentaje o monto fijo
- Condiciones: monto mínimo, fechas válidas
- Límites: usos totales, usos por cliente
- Validación en tiempo real (API)
- Estadísticas de uso
- Activación/desactivación

**7. Reseñas y Calificaciones**
- Clientes reseñan productos comprados
- Estrellas 1-5 + comentario
- Moderación manual (aprobación)
- Estadísticas por producto
- Filtrado de reseñas no aprobadas en frontend público

**8. Programa de Fidelidad**
- Recompensas permanentes: 1, 5, 10, 20 compras
- Campañas temporales con fechas
- Audiencias: new_only (solo nuevos) o all
- Productos gratuitos como premio
- Panel de canje para clientes
- Gestión completa (managers)

**9. Preguntas y Respuestas**
- Clientes preguntan sobre productos
- Managers responden desde panel admin
- Notificación por email al cliente (desactivable)
- Preguntas visibles públicamente en detalle de producto

**10. Google Maps Integration**
- Selector de dirección con mapa interactivo
- Pin arrastrable con geocoding inverso
- Validación de zona de cobertura (radio desde Bogotá)
- Guardado de coordenadas lat/lng

**11. Panel de Administración**
- Dashboard con métricas en tiempo real
- Gestión de productos, categorías, órdenes
- Control de cupones, envíos
- Moderación de reseñas
- Respuesta a preguntas
- Reportes exportables (Excel/PDF)
- Gestión de usuarios (solo admin)
- Configuraciones del sitio

**12. Dark Mode**
- Toggle persistente (localStorage)
- Paleta de colores custom (inspirada en Beagle)
- Transiciones suaves
- Iconografía adaptada
- Default: modo claro

**13. Configuraciones Parametrizables**
- **Envíos:** Horarios de envío gratis, tarifas dentro/fuera Bogotá
- **Sitio:** Número de WhatsApp, configuraciones globales
- **WhatsApp Button:** Botón flotante con número administrable

**14. Sistema de Notificaciones**
- Notificaciones in-app (campana en header)
- Emails transaccionales
- Preferencias granulares por tipo
- Unsubscribe con token único
- Cumplimiento Ley 1581/2012 (Colombia)

**15. Exportación de Datos**
- Órdenes a Excel/PDF
- Productos a Excel
- Reportes de ventas
- Rate limiting (10 exportes/hora)

---

## 🚫 NO Implementado (Pendiente)

- Pasarela de pagos real (solo simulación)
- Blog público (modelos creados, vistas no)
- Chat en vivo
- Notificaciones push
- Multi-idioma (solo español)
- App móvil

---

## 🎨 Frontend - Stack y Arquitectura

**Framework:** Vue.js 3.5.22 (Composition API)
**Router:** Vue Router 4 con guards de autenticación
**State:** Pinia 2 (stores modulares)
**HTTP:** Axios con interceptors
**UI:** PrimeVue 4 + Tailwind CSS 3
**Build:** Vite 7

### Stores Pinia (7)
- `authStore` - Usuario, token, roles
- `cartStore` - Carrito (localStorage)
- `wishlistStore` - Lista de deseos
- `productStore` - Productos, filtros
- `categoryStore` - Categorías
- `notificationStore` - Notificaciones in-app

### Servicios API (18)
Todos en `frontend/src/services/`:
- api.js (cliente base con interceptors)
- authService, productService, orderService
- categoryService, addressService, wishlistService
- reviewService, couponService, shipmentService
- notificationService, adminService, contactService
- loyaltyService, productQuestionService
- shippingConfigService, siteConfigService

### Composables (2)
- `useTheme()` - Dark mode (localStorage)
- `useConfirm()` - Diálogos de confirmación

### Rutas Importantes
- `/admin/*` - Panel admin (requiresManager)
- `/admin/users` - Gestión usuarios (requiresAdmin)
- `/account` - Cuenta del usuario (requiresAuth)
- `/loyalty` - Programa de fidelidad (requiresAuth)

**Guards:**
- `requiresAuth` → redirecciona a /login
- `requiresManager` → solo manager/admin
- `requiresAdmin` → solo admin
- `guest` → solo no autenticados

---

## 🔧 Backend - Stack y Arquitectura

**Framework:** Laravel 12
**PHP:** 8.2+
**Auth:** Laravel Sanctum (SPA authentication)
**ORM:** Eloquent
**Queue:** Database driver
**Mail:** Mailtrap (dev) / configurar SMTP (prod)

### Controladores API (24)
Todos en `backend/app/Http/Controllers/Api/`:

**Públicos:**
- ProductController, CategoryController, CartController
- CouponController (validate), ShipmentController (track)
- ContactController, UnsubscribeController

**Autenticados:**
- AuthController, OrderController, AddressController
- WishlistController, ReviewController
- NotificationController, ProductQuestionController
- LoyaltyController

**Manager/Admin:**
- AdminController (dashboard, stats)
- FileUploadController (imágenes)
- ExportController (reportes)
- ShippingConfigController, SiteConfigController

**Admin Loyalty:**
- LoyaltyProgramController, LoyaltyRewardController, LoyaltyRedemptionController

### Middleware Custom
- `AdminMiddleware` - Solo role=admin
- `ManagerMiddleware` - role=manager o admin

### Rate Limiting
- Auth endpoints: 5 req/min
- Cupones validate: 10 req/min
- Contacto: 5 req/hora
- Checkout: 3 req/min
- Exportes: 10 req/hora

### Emails (4 Mailables)
- `OrderConfirmation` - Al crear orden
- `ShipmentNotification` - Al actualizar envío
- `PasswordResetMail` - Recuperar contraseña
- `ContactMail` - Formulario de contacto

---

## 🔐 Seguridad

- ✅ Laravel Sanctum (Bearer tokens)
- ✅ Middleware de roles (admin, manager, customer)
- ✅ Rate limiting en endpoints críticos
- ✅ Validación doble (frontend + backend)
- ✅ Passwords hasheados (bcrypt, 12 rounds)
- ✅ CSRF protection
- ✅ Tokens encriptados (unsubscribe)
- ✅ Sanitización de inputs

---

## 📈 Optimizaciones Recientes

**Commit 1061b2c - Optimizaciones generales:**
- Índices de performance en tablas principales
- Ajuste de iconografía
- Modo oscuro optimizado
- Ajuste del seeder de fidelidad
- Mejoras en rutas y estilos visuales

**Funcionalidades añadidas últimamente:**
- WhatsApp button flotante parametrizable
- Horarios de envío gratis configurables
- Lógica de envío gratis ajustada (fuera de Bogotá siempre paga)
- Sistema de preguntas y respuestas
- Notificaciones de preguntas desactivables
- Mejoras en diálogos de confirmación
- Reposicionamiento de toasts

---

## 🎨 Tema y Diseño

**Inspiración:** Colores Beagle (café grisáceo, tonos cálidos)

**Paleta (Tailwind custom):**
```
primary: #6B5D54 (gris café oscuro)
dark: #2B2826 (negro cálido)
accent: #9B8B7E (café grisáceo)
cream: #F8F4EC (fondo claro)
```

**Dark Mode:**
- Activación: clase `dark` en `<html>`
- Storage: localStorage key `petunia-theme`
- Default: light (solo dark si usuario lo activa)
- Composable: `useTheme()` en `frontend/src/composables/`

---

## 🗂️ Decisiones de Arquitectura

### Por qué Sanctum y no JWT
- SPA oficial de Laravel
- Cookies httpOnly más seguras
- Soporte CSRF nativo
- Menor complejidad

### Por qué Pinia y no Vuex
- API más simple (Composition API)
- TypeScript friendly
- Recomendación oficial de Vue 3

### Por qué localStorage para cart
- UX: carrito persiste entre sesiones
- Performance: no requiere auth para agregar items
- Sync con backend: solo al checkout

### Por qué PrimeVue
- Componentes enterprise listos
- Theming flexible
- Accesibilidad built-in

### Estructura de rutas API
- Prefijo `/api/v1/` para versionado
- Rutas públicas sin auth
- Rutas protegidas con `auth:sanctum`
- Middleware de roles anidados

---

## 📝 Convenciones del Proyecto

### Código
- **Backend:** PSR-12, camelCase para métodos
- **Frontend:** Composition API, `<script setup>`
- **Componentes Vue:** PascalCase (TheHeader.vue)
- **Stores:** camelCase con sufijo Store (authStore.js)
- **Services:** camelCase con sufijo Service

### Commits
- Español
- Formato: "se implementa/ajusta/crea [descripción]"
- Ejemplo: "Se implementan optimizaciones a nivel general del sistema"

### Base de Datos
- snake_case para tablas y columnas
- Migraciones timestamped
- Seeders por modelo
- Soft deletes donde aplica

### API Responses
```json
{
  "success": true,
  "data": {...},
  "message": "Mensaje descriptivo"
}
```

---

## 🚀 Comandos Útiles

### Backend
```bash
cd backend
php artisan migrate:fresh --seed  # Reset DB
php artisan storage:link          # Link storage público
php artisan serve                 # Servidor dev (puerto 8000)
```

### Frontend
```bash
cd frontend
npm install                       # Instalar deps
npm run dev                       # Servidor dev (puerto 5173)
npm run build                     # Build producción
```

---

## 🐛 Problemas Conocidos / Notas

- Google Maps API key debe configurarse en `frontend/.env`
- Storage público requiere `php artisan storage:link`
- CORS configurado solo para localhost (ajustar en producción)
- Emails van a Mailtrap en desarrollo (configurar SMTP real en prod)
- Pasarela de pagos es simulación (implementar Stripe/PayU/MercadoPago)

---

## 📍 Estado Actual

**Versión:** 1.0 Development
**Última actualización:** 2025-10-30
**Estado:** ✅ Funcional al 100% en desarrollo

**Próximos pasos para producción:**
1. Integrar pasarela de pagos real
2. Configurar SMTP producción (SendGrid/SES)
3. CDN para imágenes (Cloudinary/S3)
4. Deploy backend (Digital Ocean/AWS)
5. Deploy frontend (Vercel/Netlify)
6. Configurar SSL
7. Google Maps API con restricciones
8. Monitoreo (Sentry)
9. Backups automatizados

---

## 💡 Notas para Claude

**Al iniciar una nueva sesión:**
1. Lee este archivo primero
2. Valida que el contexto siga vigente preguntando por cambios recientes
3. Si hay dudas arquitectónicas, referencia las "Decisiones de Arquitectura"

**Al hacer cambios importantes:**
1. Actualiza este archivo si cambia arquitectura, modelos, o funcionalidades
2. Actualiza la fecha de "Última actualización"
3. Mantén las secciones organizadas

**Convenciones de respuesta:**
- Usa rutas absolutas cuando referencias archivos
- Formato: `backend/app/Models/User.php:107`
- Menciona el rol requerido para funcionalidades admin

---

**Fin del contexto del proyecto**
