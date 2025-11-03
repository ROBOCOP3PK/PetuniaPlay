# 🐾 PetuniaPlay - Contexto del Proyecto

> **Última actualización:** 2025-11-02
> **Versión:** 1.2 - Optimización frontend con composables avanzados

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

**Total:** 22 modelos + 37 migraciones + 31 tablas en BD

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

**Framework:** Vue.js 3.5.22 (Composition API con `<script setup>`)
**Router:** Vue Router 4.6.3 con guards de autenticación multinivel
**State:** Pinia 3.0.3 (stores modulares con persistencia)
**HTTP:** Axios 1.12.2 con interceptors (auth, error handling)
**UI:** PrimeVue 4.4.1 + Tailwind CSS 3.4.18 + PrimeIcons 7.0.0
**Maps:** @googlemaps/js-api-loader 2.0.1
**Notifications:** vue-toastification 2.0.0-rc.5
**Build:** Vite 7.1.11
**Node:** ^20.19.0 || >=22.12.0

**Estructura:**
- 33 vistas (views) - Incluye admin panel completo
- 28 componentes reutilizables
- 7 stores Pinia
- 17 servicios API
- 6 composables (reutilizables y optimizados)

### Stores Pinia (7)
- `authStore` - Usuario, token, roles (persistencia en localStorage)
- `cartStore` - Carrito (localStorage)
- `wishlistStore` - Lista de deseos (persistencia en localStorage)
- `productStore` - Productos, filtros, búsqueda
- `categoryStore` - Categorías jerárquicas
- `notificationStore` - Notificaciones in-app en tiempo real
- `counter` - Store demo (no usado en producción)

### Servicios API (17)
Todos en `frontend/src/services/`:
- `api.js` - Cliente base Axios con interceptors (auth, error handling)
- `authService` - Login, registro, recuperación de contraseña
- `productService` - CRUD productos, búsqueda, autocomplete, brands
- `orderService` - Crear órdenes, historial, tracking, exportar
- `categoryService` - Categorías jerárquicas
- `addressService` - CRUD direcciones, set default
- `wishlistService` - Agregar/remover wishlist, check status
- `reviewService` - CRUD reseñas, moderación, stats
- `couponService` - CRUD cupones, validar, toggle status, stats
- `shipmentService` - CRUD shipments, tracking público, stats
- `notificationService` - Listar, marcar leídas, eliminar
- `adminService` - Dashboard stats, usuarios, roles
- `contactService` - Envío formulario de contacto
- `loyaltyService` - Recompensas, redenciones, redimir
- `productQuestionService` - CRUD preguntas, responder, stats
- `shippingConfigService` - Get/Update configuración de envíos
- `siteConfigService` - Get/Update configuración del sitio

### Composables (6)
- `useTheme()` - Dark mode (localStorage persistente)
- `useConfirm()` - Diálogos de confirmación reutilizables
- `useNotification()` - Sistema de notificaciones toast unificado
- `usePerformance()` - Skeleton loaders y estados de carga optimizados
- `useFormat()` - Formateo de números, moneda, fechas
- `useLoading()` - Estados de carga centralizados

### Rutas Principales (Router)
**Públicas (14):**
- `/` - Home
- `/products` - Catálogo de productos
- `/product/:slug` - Detalle de producto
- `/category/:slug` - Productos por categoría
- `/cart` - Carrito de compras
- `/checkout` - Proceso de pago
- `/contact`, `/faq`, `/tracking` - Páginas de soporte
- `/about`, `/terms`, `/privacy`, `/returns`, `/shipping` - Información legal
- `/unsubscribe` - Cancelar suscripción de emails

**Auth (Guest only - 4):**
- `/login`, `/register` - Acceso y registro
- `/forgot-password`, `/reset-password` - Recuperación de contraseña

**Protegidas (requiresAuth - 3):**
- `/account` - Panel de usuario (perfil, órdenes, direcciones)
- `/wishlist` - Lista de deseos
- `/loyalty` - Programa de fidelidad del cliente

**Admin Panel (requiresManager - 9):**
- `/admin` - Dashboard con métricas
- `/admin/products` - Gestión de productos
- `/admin/categories` - Gestión de categorías
- `/admin/orders` - Gestión de órdenes y envíos
- `/admin/coupons` - Gestión de cupones
- `/admin/shipments` - Control de despachos
- `/admin/loyalty` - Gestión programa de fidelidad
- `/admin/questions` - Responder preguntas de clientes
- `/admin/shipping-config` - Configuración de envíos

**Admin Only (requiresAdmin - 2):**
- `/admin/users` - Gestión de usuarios y roles
- `/admin/site-config` - Configuración general del sitio

**Navigation Guards:**
- `requiresAuth` → redirecciona a /login si no autenticado
- `requiresManager` → valida role=manager o admin
- `requiresAdmin` → valida role=admin exclusivamente
- `guest` → redirecciona a /account si ya autenticado
- Scroll to top en cada navegación

---

## 🔧 Backend - Stack y Arquitectura

**Framework:** Laravel 12.0
**PHP:** 8.2+
**Auth:** Laravel Sanctum 4.2 (SPA authentication)
**ORM:** Eloquent con scopes y accessors
**Queue:** Database driver (jobs table)
**Cache:** Database driver (cache table)
**Mail:** Mailtrap (dev) / configurar SMTP (prod)
**PDF:** barryvdh/laravel-dompdf 3.1
**Excel:** maatwebsite/excel 3.1
**Database:** MySQL 8.0.43 (31 tablas)

### Controladores API (22)
Todos en `backend/app/Http/Controllers/Api/`:

**Públicos (8):**
- `ProductController` - index, show, featured, search, autocomplete, brands
- `CategoryController` - index, show (categorías jerárquicas)
- `CartController` - index, add, update, remove, clear (stateless)
- `CouponController` - validate (validación de cupones)
- `ShipmentController` - trackByNumber (tracking público)
- `ContactController` - send (formulario de contacto)
- `UnsubscribeController` - unsubscribe, resubscribe (emails)
- `AuthController` - register, login, forgotPassword, resetPassword

**Autenticados (8):**
- `AuthController` - logout, user, updateProfile, changePassword, updateNotificationPreferences
- `OrderController` - index, show, store, cancel (historial de pedidos)
- `AddressController` - CRUD completo, setDefault (direcciones usuario)
- `WishlistController` - index, store, destroy, clear, check, getProductIds
- `ReviewController` - store, update, destroy (reseñas de clientes)
- `ProductQuestionController` - store (preguntas de clientes)
- `LoyaltyController` - myRewards, myRedemptions, redeem (fidelidad cliente)
- `NotificationController` - index, unreadCount, markAsRead, markAllAsRead, destroy, deleteRead

**Manager/Admin (6):**
- `AdminController` - dashboard, salesStats, lowStockProducts, outOfStockProducts, users, updateUserRole, toggleUserStatus
- `FileUploadController` - uploadImage, uploadMultiple, deleteImage, deleteImageByUrl
- `ProductController` - store, update, destroy (gestión de productos)
- `CategoryController` - store, update, destroy (gestión de categorías)
- `OrderController` - adminIndex, updateStatus, pendingShipment, shipped, shippingStats, exportExcel, exportPdf
- `ReviewController` - adminIndex, toggleApproval (moderación)
- `ProductQuestionController` - index, stats, answer, destroy (responder preguntas)
- `CouponController` - index, store, show, update, destroy, toggleStatus, stats
- `ShipmentController` - index, store, show, update, destroy, stats
- `ShippingConfigController` - index, update (configuración de envíos)
- `SiteConfigController` - index, update (configuración del sitio, solo admin)
- `ExportController` - salesReport (reportes avanzados)

**Admin Loyalty (3):**
- `LoyaltyProgramController` - index, store, activate, statistics
- `LoyaltyRewardController` - apiResource (CRUD), toggle
- `LoyaltyRedemptionController` - index, show, process

### Middleware Custom
- `AdminMiddleware` - Solo role=admin
- `ManagerMiddleware` - role=manager o admin

### Endpoints API Destacados
**Prefijo:** `/api/v1/`

**Autenticación (throttle 5/min):**
- POST `/register`, `/login`, `/forgot-password`, `/reset-password`
- POST `/logout`, GET `/user` (requiere auth)

**Productos:**
- GET `/products` - Listado con filtros (category, brand, price_min/max, search, featured)
- GET `/products/featured` - Productos destacados
- GET `/products/search` - Búsqueda avanzada
- GET `/products/autocomplete` - Sugerencias de búsqueda
- GET `/products/brands` - Listado de marcas
- GET `/products/{slug}` - Detalle de producto

**Órdenes:**
- POST `/orders` (público, throttle 3/min) - Crear orden (guest checkout)
- GET `/orders` (auth) - Historial de órdenes del usuario
- GET `/orders/{orderNumber}` (auth) - Detalle de orden
- PUT `/orders/{id}/cancel` (auth) - Cancelar orden
- GET `/admin/orders` (manager) - Todas las órdenes
- PUT `/orders/{id}/status` (manager) - Actualizar estado
- GET `/admin/orders/pending-shipment` (manager) - Pendientes de despacho
- GET `/admin/export/orders/excel` (manager, throttle 10/hora) - Exportar Excel
- GET `/admin/export/orders/pdf` (manager, throttle 10/hora) - Exportar PDF

**Cupones:**
- POST `/coupons/validate` (público, throttle 10/min) - Validar cupón
- GET/POST/PUT/DELETE `/coupons/*` (manager) - CRUD completo
- GET `/admin/coupons/stats` (manager) - Estadísticas de uso

**Tracking:**
- GET `/shipments/track/{trackingNumber}` (público) - Rastreo de envíos

**Fidelidad:**
- GET `/loyalty/my-rewards` (auth) - Recompensas disponibles
- GET `/loyalty/my-redemptions` (auth) - Historial de canjes
- POST `/loyalty/redeem` (auth) - Canjear recompensa
- `/admin/loyalty/*` (manager) - Gestión completa

**Notificaciones:**
- GET `/notifications` (auth) - Listar notificaciones
- GET `/notifications/unread-count` (auth) - Contador de no leídas
- PUT `/notifications/{id}/read` (auth) - Marcar como leída
- PUT `/notifications/mark-all-read` (auth) - Marcar todas como leídas

### Rate Limiting por Grupo
- **Auth:** 5 req/min (prevenir brute force)
- **Cupones validate:** 10 req/min (prevenir abuso)
- **Contacto:** 5 req/hora (prevenir spam)
- **Checkout:** 3 req/min (prevenir órdenes duplicadas)
- **Exportes:** 10 req/hora (prevenir sobrecarga del servidor)

### Emails (4 Mailables)
Todos en `backend/app/Mail/`:
- `OrderConfirmation` - Confirmación al crear orden (con detalles de productos)
- `ShipmentNotification` - Notificación al actualizar estado de envío
- `PasswordResetMail` - Token para recuperar contraseña
- `ContactMail` - Envío de formulario de contacto al admin

### Seeders (7)
Todos en `backend/database/seeders/`:
- `DatabaseSeeder` - Seeder principal (orquesta todos)
- `UserSeeder` - Admin, manager, 20 clientes
- `CategorySeeder` - Categorías de productos para mascotas
- `ProductSeeder` - ~50 productos con imágenes
- `CouponSeeder` - Cupones de ejemplo
- `LoyaltySeeder` - Programa de fidelidad con recompensas
- `BlogCategorySeeder` - Categorías de blog (no usado en frontend)

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

**Últimos commits (git log):**
- `d094c12` - Se optimiza frontend con composables reutilizables y componentes skeleton
  - Agregados 4 composables nuevos: useNotification, usePerformance, useFormat, useLoading
  - Implementados skeleton loaders para mejorar UX durante cargas
  - Refactorización de lógica común en composables reutilizables
  - Reducción de código duplicado en componentes
- `e50de32` - Optimización del sistema general
- `8e76c27` - Se optimiza documentación con PROJECT_CONTEXT.md unificado
  - Consolidados 4 archivos MD en PROJECT_CONTEXT.md único
  - Agregados comandos slash /apc y /cm para automatización
  - Actualizado PROJECT_CONTEXT con estadísticas completas
  - Eliminados archivos redundantes de documentación técnica
- `cb09b20` - Se crea PROJECT_CONTEXT.md para mantener contexto completo del proyecto

**Funcionalidades añadidas últimamente:**
- Sistema de cupones con límite por cliente (max_usage_per_customer)
- Tabla `coupon_redemptions` para tracking de usos
- WhatsApp button flotante parametrizable desde SiteConfig
- Horarios de envío gratis configurables desde ShippingConfig
- Sistema de preguntas y respuestas de productos
- Notificaciones de preguntas desactivables por usuario
- Envío nocturno opcional (night_delivery flag)
- Índices de performance para optimización de queries

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

### Comandos Slash de Claude (Proyecto)
- `/apc` - Actualiza exhaustivamente PROJECT_CONTEXT.md analizando todo el proyecto
- `/cm` - Genera mensaje inteligente de commit analizando cambios (git)

### Backend (Laravel)
```bash
cd backend
php artisan migrate:fresh --seed  # Reset DB completo con datos de prueba
php artisan storage:link          # Link storage público
php artisan serve                 # Servidor dev (puerto 8000)
php artisan queue:work            # Procesar trabajos en cola
php artisan db:show               # Ver información de la BD
```

### Frontend (Vue.js)
```bash
cd frontend
npm install                       # Instalar dependencias
npm run dev                       # Servidor dev (puerto 5173)
npm run build                     # Build producción
```

### Desarrollo Full Stack
```bash
# En backend/ ejecutar (requiere concurrently):
composer dev  # Inicia servidor + queue + logs + vite simultáneamente
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

**Versión:** 1.2 Development
**Última actualización:** 2025-11-02
**Estado:** ✅ Funcional al 100% en desarrollo

**Base de datos (estado real):**
- 27 tablas activas (de 31 disponibles)
- 27 usuarios registrados
- 13 productos en catálogo
- 12 categorías activas
- 0 órdenes (sistema nuevo)

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
