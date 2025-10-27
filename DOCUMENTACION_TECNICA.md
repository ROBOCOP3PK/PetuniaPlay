# 📚 Documentación Técnica - PetuniaPlay

## Índice

1. [Arquitectura del Sistema](#arquitectura-del-sistema)
2. [Stack Tecnológico](#stack-tecnológico)
3. [Estructura de Directorios](#estructura-de-directorios)
4. [Base de Datos](#base-de-datos)
5. [API Endpoints](#api-endpoints)
6. [Autenticación y Autorización](#autenticación-y-autorización)
7. [Servicios y Lógica de Negocio](#servicios-y-lógica-de-negocio)
8. [Frontend - Componentes Vue](#frontend---componentes-vue)
9. [Sistema de Emails](#sistema-de-emails)
10. [Configuración y Deployment](#configuración-y-deployment)

---

# Arquitectura del Sistema

## Visión General

PetuniaPlay es una aplicación full-stack con arquitectura separada:

```
┌─────────────────────────────────────────────────────┐
│                    FRONTEND                         │
│              Vue.js 3 + Vite                        │
│              Port: 5173 (dev)                       │
│                                                     │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐         │
│  │ Views    │  │Components│  │ Services │         │
│  └──────────┘  └──────────┘  └──────────┘         │
└──────────────────┬──────────────────────────────────┘
                   │ HTTP/AJAX (Axios)
                   │
┌──────────────────▼──────────────────────────────────┐
│                  BACKEND API                        │
│              Laravel 12                             │
│              Port: 8000 (dev)                       │
│                                                     │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐         │
│  │Controllers│ │ Services │  │ Models   │         │
│  └──────────┘  └──────────┘  └──────────┘         │
└──────────────────┬──────────────────────────────────┘
                   │ Eloquent ORM
                   │
┌──────────────────▼──────────────────────────────────┐
│               BASE DE DATOS                         │
│            MySQL / MariaDB                          │
│              Port: 3306                             │
└─────────────────────────────────────────────────────┘
```

## Patrón de Arquitectura

### Backend: MVC + Service Layer

**Model-View-Controller con capa de servicios:**

```
Request → Router → Middleware → Controller → Service → Model → Database
                                     ↓
                                 Resource
                                     ↓
                                 Response
```

**Responsabilidades:**

1. **Routes:** Definir endpoints y asignar middlewares
2. **Middleware:** Autenticación, autorización, validación
3. **Controllers:** Recibir requests, validar inputs, invocar servicios
4. **Services:** Lógica de negocio compleja, transacciones
5. **Models:** Mapeo de base de datos, relaciones
6. **Resources:** Transformar datos para respuesta JSON

### Frontend: Component-Based Architecture

```
App.vue
├── Router
│   ├── Public Pages
│   │   ├── HomeView
│   │   ├── ProductsView
│   │   ├── ProductDetailView
│   │   └── CheckoutView
│   └── Admin Pages
│       ├── AdminDashboard
│       ├── AdminProductsView
│       └── AdminOrdersView
└── Layout Components
    ├── TheHeader
    ├── TheFooter
    └── AdminLayout
```

---

# Stack Tecnológico

## Backend

| Componente | Tecnología | Versión |
|------------|------------|---------|
| Framework | Laravel | 12.x |
| Lenguaje | PHP | 8.2+ |
| Base de Datos | MySQL/MariaDB | 8.0+ / 10.6+ |
| Autenticación | Laravel Sanctum | 4.x |
| Validación | Laravel Validation | Built-in |
| ORM | Eloquent | Built-in |

## Frontend

| Componente | Tecnología | Versión |
|------------|------------|---------|
| Framework | Vue.js | 3.x |
| Build Tool | Vite | 5.x |
| Router | Vue Router | 4.x |
| State Management | Pinia | 2.x |
| HTTP Client | Axios | 1.x |
| CSS Framework | Tailwind CSS | 3.x |
| Iconos | SVG inline | - |

## Servicios Externos

| Servicio | Propósito |
|----------|-----------|
| Google Maps API | Selección de ubicación, geocoding |
| SMTP (Producción) | Envío de emails (SendGrid, SES, etc.) |
| Mailtrap (Dev) | Testing de emails |

---

# Estructura de Directorios

## Backend (`/backend`)

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       ├── AuthController.php
│   │   │       ├── ProductController.php
│   │   │       ├── OrderController.php
│   │   │       ├── ShipmentController.php
│   │   │       ├── CouponController.php
│   │   │       ├── ReviewController.php
│   │   │       ├── UserController.php
│   │   │       └── CategoryController.php
│   │   ├── Middleware/
│   │   │   ├── ManagerMiddleware.php
│   │   │   └── AdminMiddleware.php
│   │   └── Resources/
│   │       ├── ProductResource.php
│   │       ├── OrderResource.php
│   │       ├── UserResource.php
│   │       └── ...
│   ├── Models/
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Shipment.php
│   │   ├── Address.php
│   │   ├── Coupon.php
│   │   ├── Review.php
│   │   └── Wishlist.php
│   ├── Services/
│   │   ├── OrderService.php
│   │   ├── ShipmentService.php
│   │   └── ProductService.php
│   ├── Mail/
│   │   ├── OrderConfirmation.php
│   │   └── ShipmentUpdate.php
│   └── Exports/
│       ├── OrdersExport.php
│       └── ProductsExport.php
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── routes/
│   ├── api.php
│   └── web.php
├── storage/
│   ├── app/
│   │   └── public/
│   │       └── products/  (Imágenes)
│   └── logs/
├── .env
└── composer.json
```

## Frontend (`/frontend`)

```
frontend/
├── src/
│   ├── assets/
│   │   └── styles/
│   │       └── main.css
│   ├── components/
│   │   ├── layout/
│   │   │   ├── TheHeader.vue
│   │   │   ├── TheFooter.vue
│   │   │   └── AdminLayout.vue
│   │   ├── products/
│   │   │   ├── ProductCard.vue
│   │   │   ├── ProductGrid.vue
│   │   │   └── ProductFilters.vue
│   │   ├── cart/
│   │   │   ├── CartItem.vue
│   │   │   └── CartSummary.vue
│   │   ├── AddressMapPicker.vue
│   │   ├── OrderStatusBadge.vue
│   │   └── StarRating.vue
│   ├── views/
│   │   ├── public/
│   │   │   ├── HomeView.vue
│   │   │   ├── ProductsView.vue
│   │   │   ├── ProductDetailView.vue
│   │   │   ├── CartView.vue
│   │   │   ├── CheckoutView.vue
│   │   │   ├── LoginView.vue
│   │   │   ├── RegisterView.vue
│   │   │   ├── AccountView.vue
│   │   │   └── TrackingView.vue
│   │   └── admin/
│   │       ├── AdminDashboard.vue
│   │       ├── AdminProductsView.vue
│   │       ├── AdminOrdersView.vue
│   │       ├── AdminShipmentsView.vue
│   │       ├── AdminCouponsView.vue
│   │       ├── AdminUsersView.vue
│   │       └── AdminReviewsView.vue
│   ├── services/
│   │   ├── api.js
│   │   ├── authService.js
│   │   ├── productService.js
│   │   ├── orderService.js
│   │   ├── cartService.js
│   │   └── shipmentService.js
│   ├── stores/
│   │   ├── auth.js
│   │   ├── cart.js
│   │   └── products.js
│   ├── router/
│   │   └── index.js
│   ├── App.vue
│   └── main.js
├── public/
├── .env
├── .env.example
├── package.json
├── vite.config.js
└── tailwind.config.js
```

---

# Base de Datos

## Diagrama de Relaciones (ERD)

```
┌─────────────┐       ┌──────────────┐       ┌─────────────┐
│   users     │       │   orders     │       │ order_items │
├─────────────┤       ├──────────────┤       ├─────────────┤
│ id          │───┐   │ id           │───┐   │ id          │
│ name        │   │   │ user_id      │   │   │ order_id    │
│ email       │   │   │ order_number │   └───│ product_id  │
│ password    │   │   │ total        │       │ quantity    │
│ role        │   │   │ status       │       │ price       │
│ is_active   │   │   │ payment_*    │       │ subtotal    │
└─────────────┘   │   └──────────────┘       └─────────────┘
                  │            │
                  │            │
┌─────────────┐   │   ┌──────────────┐       ┌─────────────┐
│  addresses  │   │   │  shipments   │       │  products   │
├─────────────┤   │   ├──────────────┤       ├─────────────┤
│ id          │   │   │ id           │       │ id          │
│ user_id     │───┘   │ order_id     │       │ name        │
│ full_name   │       │ tracking_*   │       │ sku         │
│ phone       │       │ carrier      │       │ price       │
│ address_*   │       │ status       │       │ stock       │
│ latitude    │       │ shipped_at   │       │ category_id │
│ longitude   │       │ delivered_at │       │ is_featured │
└─────────────┘       └──────────────┘       └─────────────┘
                                                     │
┌─────────────┐       ┌──────────────┐              │
│   reviews   │       │  categories  │──────────────┘
├─────────────┤       ├──────────────┤
│ id          │       │ id           │
│ user_id     │       │ name         │
│ product_id  │       │ slug         │
│ rating      │       │ description  │
│ comment     │       │ image        │
│ is_approved │       └──────────────┘
└─────────────┘
                      ┌──────────────┐
                      │   coupons    │
                      ├──────────────┤
                      │ id           │
                      │ code         │
                      │ type         │
                      │ value        │
                      │ min_amount   │
                      │ max_uses     │
                      │ valid_from   │
                      │ valid_until  │
                      │ is_active    │
                      └──────────────┘
```

## Tablas Principales

### users

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'manager', 'admin') DEFAULT 'customer',
    is_active BOOLEAN DEFAULT true,
    email_preferences JSON NULL,
    unsubscribe_token VARCHAR(255) NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

**Roles:**
- `customer`: Cliente regular
- `manager`: Acceso al panel admin (limitado)
- `admin`: Acceso completo

### products

```sql
CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    sku VARCHAR(100) UNIQUE NOT NULL,
    description TEXT NOT NULL,
    short_description VARCHAR(500) NULL,
    price DECIMAL(10, 2) NOT NULL,
    sale_price DECIMAL(10, 2) NULL,
    stock INT NOT NULL DEFAULT 0,
    low_stock_threshold INT DEFAULT 10,
    manage_stock BOOLEAN DEFAULT true,
    image VARCHAR(255) NULL,
    images JSON NULL,
    is_featured BOOLEAN DEFAULT false,
    is_published BOOLEAN DEFAULT true,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);
```

### orders

```sql
CREATE TABLE orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    order_number VARCHAR(255) UNIQUE NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    payment_method VARCHAR(50) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    tax DECIMAL(10, 2) NOT NULL DEFAULT 0,
    shipping_cost DECIMAL(10, 2) NOT NULL DEFAULT 0,
    discount DECIMAL(10, 2) NOT NULL DEFAULT 0,
    total DECIMAL(10, 2) NOT NULL,
    coupon_id BIGINT UNSIGNED NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(50) NOT NULL,
    customer_document VARCHAR(50) NULL,
    shipping_address_id BIGINT UNSIGNED NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (coupon_id) REFERENCES coupons(id) ON DELETE SET NULL,
    FOREIGN KEY (shipping_address_id) REFERENCES addresses(id) ON DELETE SET NULL
);
```

**Estados de orden:**
- `pending`: Creada, esperando pago
- `processing`: Pagada, preparando envío
- `shipped`: Despachada
- `delivered`: Entregada
- `cancelled`: Cancelada

**Estados de pago:**
- `pending`: Esperando pago
- `paid`: Pagado
- `failed`: Pago fallido
- `refunded`: Reembolsado

### order_items

```sql
CREATE TABLE order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NULL,
    product_name VARCHAR(255) NOT NULL,
    product_sku VARCHAR(100) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);
```

### shipments

```sql
CREATE TABLE shipments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    tracking_number VARCHAR(255) UNIQUE NOT NULL,
    carrier VARCHAR(100) NOT NULL,
    status ENUM('pending', 'in_transit', 'delivered', 'failed', 'returned') DEFAULT 'pending',
    shipped_at TIMESTAMP NULL,
    delivered_at TIMESTAMP NULL,
    estimated_delivery TIMESTAMP NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);
```

### addresses

```sql
CREATE TABLE addresses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    full_name VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    address_line_1 VARCHAR(255) NOT NULL,
    address_line_2 VARCHAR(255) NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    postal_code VARCHAR(20) NULL,
    country VARCHAR(100) DEFAULT 'Colombia',
    latitude DECIMAL(10, 7) NULL,
    longitude DECIMAL(10, 7) NULL,
    type ENUM('billing', 'shipping') DEFAULT 'shipping',
    is_default BOOLEAN DEFAULT false,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### coupons

```sql
CREATE TABLE coupons (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    description TEXT NULL,
    type ENUM('percentage', 'fixed') NOT NULL,
    value DECIMAL(10, 2) NOT NULL,
    min_amount DECIMAL(10, 2) NULL,
    max_uses INT NULL,
    times_used INT DEFAULT 0,
    valid_from TIMESTAMP NOT NULL,
    valid_until TIMESTAMP NOT NULL,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### reviews

```sql
CREATE TABLE reviews (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT NULL,
    is_approved BOOLEAN DEFAULT false,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product (user_id, product_id)
);
```

### wishlists

```sql
CREATE TABLE wishlists (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product (user_id, product_id)
);
```

---

# API Endpoints

## Base URL

**Desarrollo:** `http://localhost:8000/api/v1`
**Producción:** `https://your-domain.com/api/v1`

## Autenticación

Todos los endpoints protegidos requieren header:

```
Authorization: Bearer {token}
```

## Endpoints Públicos

### Auth

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| POST | `/register` | Registrar usuario |
| POST | `/login` | Iniciar sesión |
| POST | `/logout` | Cerrar sesión |
| POST | `/forgot-password` | Solicitar reset |
| POST | `/reset-password` | Resetear contraseña |

### Products

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/products` | Listar productos |
| GET | `/products/{id}` | Ver detalle |
| GET | `/products/slug/{slug}` | Ver por slug |
| GET | `/products/featured` | Productos destacados |

### Categories

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/categories` | Listar categorías |
| GET | `/categories/{id}` | Ver detalle |

### Orders

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| POST | `/orders` | Crear orden |
| GET | `/orders/track` | Rastrear (público) |

## Endpoints Autenticados (Customer)

### User Account

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/user` | Obtener usuario actual |
| PUT | `/user/profile` | Actualizar perfil |
| PUT | `/user/password` | Cambiar contraseña |
| PUT | `/user/preferences` | Actualizar preferencias |

### User Orders

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/user/orders` | Mis pedidos |
| GET | `/user/orders/{id}` | Ver mi pedido |
| PUT | `/user/orders/{id}/cancel` | Cancelar pedido |

### Addresses

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/addresses` | Mis direcciones |
| POST | `/addresses` | Crear dirección |
| PUT | `/addresses/{id}` | Actualizar dirección |
| DELETE | `/addresses/{id}` | Eliminar dirección |
| PUT | `/addresses/{id}/default` | Marcar como predeterminada |

### Wishlist

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/wishlist` | Mi lista de deseos |
| POST | `/wishlist` | Agregar producto |
| DELETE | `/wishlist/{productId}` | Eliminar producto |

### Reviews

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/user/reviews` | Mis reseñas |
| POST | `/reviews` | Crear reseña |
| PUT | `/reviews/{id}` | Actualizar reseña |
| DELETE | `/reviews/{id}` | Eliminar reseña |

## Endpoints Admin/Manager

### Dashboard

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/admin/dashboard/stats` | Estadísticas generales |
| GET | `/admin/dashboard/sales` | Datos de ventas |
| GET | `/admin/dashboard/low-stock` | Stock bajo |

### Products Management

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/admin/products` | Listar (admin) |
| POST | `/admin/products` | Crear producto |
| PUT | `/admin/products/{id}` | Actualizar |
| DELETE | `/admin/products/{id}` | Eliminar |
| POST | `/admin/products/{id}/images` | Subir imágenes |

### Orders Management

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/admin/orders` | Listar todas las órdenes |
| GET | `/admin/orders/{id}` | Ver detalle |
| PUT | `/admin/orders/{id}/status` | Actualizar estado |
| GET | `/admin/orders/pending-shipment` | Por despachar |
| GET | `/admin/orders/shipped` | Despachadas |
| GET | `/admin/orders/shipping-stats` | Estadísticas de envío |
| GET | `/admin/orders/export` | Exportar Excel/PDF |

### Shipments

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/shipments` | Listar envíos |
| POST | `/shipments` | Crear envío |
| PUT | `/shipments/{id}` | Actualizar envío |
| PUT | `/shipments/{id}/status` | Actualizar estado |

### Coupons

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/admin/coupons` | Listar cupones |
| POST | `/admin/coupons` | Crear cupón |
| PUT | `/admin/coupons/{id}` | Actualizar |
| DELETE | `/admin/coupons/{id}` | Eliminar |
| PUT | `/admin/coupons/{id}/toggle` | Activar/desactivar |

### Reviews Moderation

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/admin/reviews` | Todas las reseñas |
| PUT | `/admin/reviews/{id}/approve` | Aprobar |
| PUT | `/admin/reviews/{id}/reject` | Rechazar |
| DELETE | `/admin/reviews/{id}` | Eliminar |

## Endpoints Solo Admin

### Users Management

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/admin/users` | Listar usuarios |
| GET | `/admin/users/{id}` | Ver detalle |
| POST | `/admin/users` | Crear usuario |
| PUT | `/admin/users/{id}/role` | Cambiar rol |
| PUT | `/admin/users/{id}/toggle` | Activar/desactivar |

---

# Autenticación y Autorización

## Laravel Sanctum

### Flujo de Autenticación

```
1. Cliente envía credenciales
   POST /api/v1/login
   {
     "email": "user@example.com",
     "password": "password"
   }

2. Backend valida credenciales

3. Si son válidas:
   - Genera token Sanctum
   - Retorna token + datos de usuario
   {
     "token": "1|abc123...",
     "user": {
       "id": 1,
       "name": "Juan",
       "email": "user@example.com",
       "role": "customer"
     }
   }

4. Cliente guarda token en localStorage

5. Subsecuentes requests incluyen header:
   Authorization: Bearer 1|abc123...

6. Backend valida token en cada request
```

### Configuración Frontend (Axios)

```javascript
// src/services/api.js
import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL + '/api/v1',
  withCredentials: true
})

// Interceptor para agregar token
api.interceptors.request.use(config => {
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

// Interceptor para manejar 401
api.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      router.push('/login')
    }
    return Promise.reject(error)
  }
)

export default api
```

## Middleware de Roles

### ManagerMiddleware.php

```php
public function handle(Request $request, Closure $next): Response
{
    $user = $request->user();

    if (!$user || !in_array($user->role, ['manager', 'admin'])) {
        return response()->json([
            'message' => 'No autorizado. Requiere rol de Manager o Admin.'
        ], 403);
    }

    return $next($request);
}
```

### AdminMiddleware.php

```php
public function handle(Request $request, Closure $next): Response
{
    $user = $request->user();

    if (!$user || $user->role !== 'admin') {
        return response()->json([
            'message' => 'No autorizado. Requiere rol de Admin.'
        ], 403);
    }

    return $next($request);
}
```

### Uso en Rutas

```php
// routes/api.php

Route::middleware('auth:sanctum')->group(function () {
    // Endpoints autenticados (cualquier rol)
    Route::get('/user', [UserController::class, 'show']);

    // Endpoints Manager+
    Route::middleware('manager')->group(function () {
        Route::apiResource('admin/products', ProductController::class);
        Route::apiResource('admin/orders', OrderController::class);
    });

    // Endpoints solo Admin
    Route::middleware('admin')->group(function () {
        Route::apiResource('admin/users', UserController::class);
    });
});
```

---

# Servicios y Lógica de Negocio

## OrderService.php

**Responsabilidad:** Gestión completa de órdenes

### Métodos Principales

#### `create(array $orderData): Order`

Crea una orden completa con transacción:

```php
public function create(array $orderData): Order
{
    return DB::transaction(function () use ($orderData) {
        // 1. Extraer user_id si está autenticado
        $userId = auth()->id();

        // 2. Crear dirección de envío
        $shippingAddress = Address::create([
            'user_id' => $userId,
            'full_name' => $orderData['customer']['name'],
            'phone' => $orderData['customer']['phone'],
            'address_line_1' => $orderData['shipping']['address'],
            'city' => $orderData['shipping']['city'],
            'state' => $orderData['shipping']['state'],
            'latitude' => $orderData['shipping']['latitude'] ?? null,
            'longitude' => $orderData['shipping']['longitude'] ?? null,
            'type' => 'shipping',
        ]);

        // 3. Validar y aplicar cupón
        $couponId = null;
        $discount = 0;
        if (!empty($orderData['couponCode'])) {
            $coupon = $this->validateCoupon(
                $orderData['couponCode'],
                $orderData['subtotal']
            );
            if ($coupon) {
                $couponId = $coupon->id;
                $discount = $this->calculateDiscount($coupon, $orderData['subtotal']);
            }
        }

        // 4. Calcular totales
        $subtotal = $orderData['subtotal'];
        $tax = $subtotal * 0.19; // 19% IVA
        $shippingCost = $orderData['shippingCost'] ?? 5000;
        $total = $subtotal + $tax + $shippingCost - $discount;

        // 5. Crear orden
        $order = Order::create([
            'user_id' => $userId,
            'order_number' => $this->generateOrderNumber(),
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $orderData['paymentMethod'],
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping_cost' => $shippingCost,
            'discount' => $discount,
            'total' => $total,
            'coupon_id' => $couponId,
            'customer_name' => $orderData['customer']['name'],
            'customer_email' => $orderData['customer']['email'],
            'customer_phone' => $orderData['customer']['phone'],
            'customer_document' => $orderData['customer']['document'] ?? null,
            'shipping_address_id' => $shippingAddress->id,
            'notes' => $orderData['shipping']['notes'] ?? null,
        ]);

        // 6. Crear items y descontar stock
        foreach ($orderData['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);

            // Verificar stock
            if ($product->manage_stock && $product->stock < $item['quantity']) {
                throw new \Exception("Stock insuficiente para {$product->name}");
            }

            // Crear order item
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'quantity' => $item['quantity'],
                'price' => $product->sale_price ?? $product->price,
                'subtotal' => ($product->sale_price ?? $product->price) * $item['quantity'],
            ]);

            // Descontar stock
            if ($product->manage_stock) {
                $product->decrement('stock', $item['quantity']);
            }
        }

        // 7. Incrementar usos del cupón
        if ($couponId) {
            Coupon::find($couponId)->increment('times_used');
        }

        // 8. Simular pago (en producción integrar pasarela)
        $this->processPayment($order);

        // 9. Enviar email de confirmación
        if ($this->shouldSendEmail($userId, 'order_confirmations')) {
            Mail::to($order->customer_email)->queue(new OrderConfirmation($order));
        }

        return $order->load(['items.product', 'shippingAddress']);
    });
}
```

#### `cancel(Order $order): bool`

Cancela una orden y devuelve stock:

```php
public function cancel(Order $order): bool
{
    if (!in_array($order->status, ['pending', 'processing'])) {
        throw new \Exception('Solo se pueden cancelar órdenes pendientes o en proceso');
    }

    return DB::transaction(function () use ($order) {
        // Devolver stock
        foreach ($order->items as $item) {
            if ($item->product && $item->product->manage_stock) {
                $item->product->increment('stock', $item->quantity);
            }
        }

        // Actualizar orden
        $order->update(['status' => 'cancelled']);

        return true;
    });
}
```

## ShipmentService.php

**Responsabilidad:** Gestión de envíos

### Métodos Principales

#### `create(array $data): Shipment`

```php
public function create(array $data): Shipment
{
    return DB::transaction(function () use ($data) {
        $order = Order::findOrFail($data['order_id']);

        // Validar que no tenga envío ya
        if ($order->shipment) {
            throw new \Exception('Esta orden ya tiene un envío creado');
        }

        // Crear shipment
        $shipment = Shipment::create([
            'order_id' => $order->id,
            'tracking_number' => $data['tracking_number'],
            'carrier' => $data['carrier'],
            'status' => 'pending',
            'notes' => $data['notes'] ?? null,
        ]);

        // Actualizar orden a Processing
        if ($order->status === 'pending') {
            $order->update(['status' => 'processing']);
        }

        // Enviar email al cliente
        if ($this->shouldSendEmail($order->user_id, 'shipping_updates')) {
            Mail::to($order->customer_email)->queue(
                new ShipmentUpdate($order, $shipment, 'created')
            );
        }

        return $shipment;
    });
}
```

#### `updateStatus(Shipment $shipment, string $status): Shipment`

```php
public function updateStatus(Shipment $shipment, string $status): Shipment
{
    $shipment->update(['status' => $status]);

    // Marcar fechas automáticas
    if ($status === 'in_transit' && !$shipment->shipped_at) {
        $shipment->update(['shipped_at' => now()]);
        $shipment->order->update(['status' => 'shipped']);
    }

    if ($status === 'delivered') {
        $shipment->update(['delivered_at' => now()]);
        $shipment->order->update(['status' => 'delivered']);
    }

    // Enviar email
    if ($this->shouldSendEmail($shipment->order->user_id, 'shipping_updates')) {
        Mail::to($shipment->order->customer_email)->queue(
            new ShipmentUpdate($shipment->order, $shipment, $status)
        );
    }

    return $shipment;
}
```

---

# Frontend - Componentes Vue

## Composición de Componentes

### Componente Base: ProductCard.vue

```vue
<template>
  <div class="product-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
    <router-link :to="`/products/${product.slug}`">
      <!-- Imagen -->
      <div class="relative h-64 bg-gray-200">
        <img
          :src="productImage"
          :alt="product.name"
          class="w-full h-full object-cover"
        />

        <!-- Badge de descuento -->
        <div v-if="hasDiscount" class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded">
          -{{ discountPercent }}%
        </div>

        <!-- Badge sin stock -->
        <div v-if="!product.in_stock" class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
          <span class="text-white font-bold">AGOTADO</span>
        </div>
      </div>

      <!-- Información -->
      <div class="p-4">
        <h3 class="font-bold text-lg mb-2 truncate">{{ product.name }}</h3>
        <p class="text-gray-600 text-sm mb-3 line-clamp-2">
          {{ product.short_description }}
        </p>

        <!-- Precio -->
        <div class="flex items-center gap-2 mb-3">
          <span v-if="hasDiscount" class="text-gray-400 line-through text-sm">
            ${{ formatPrice(product.price) }}
          </span>
          <span class="text-primary font-bold text-xl">
            ${{ formatPrice(displayPrice) }}
          </span>
        </div>

        <!-- Rating -->
        <div class="flex items-center gap-2 mb-3">
          <StarRating :rating="product.average_rating || 0" :readonly="true" />
          <span class="text-sm text-gray-500">({{ product.reviews_count || 0 }})</span>
        </div>
      </div>
    </router-link>

    <!-- Acciones -->
    <div class="p-4 pt-0 flex gap-2">
      <button
        @click="addToCart"
        :disabled="!product.in_stock"
        class="flex-1 bg-primary text-white py-2 rounded-lg hover:bg-primary-dark transition disabled:bg-gray-300"
      >
        🛒 Agregar
      </button>

      <button
        @click="toggleWishlist"
        class="p-2 border rounded-lg hover:bg-gray-50"
        :class="{ 'text-red-500': isInWishlist }"
      >
        {{ isInWishlist ? '❤️' : '🤍' }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useCartStore } from '@/stores/cart'
import { useAuthStore } from '@/stores/auth'
import StarRating from './StarRating.vue'

const props = defineProps({
  product: {
    type: Object,
    required: true
  }
})

const cartStore = useCartStore()
const authStore = useAuthStore()

const isInWishlist = ref(false) // Cargar desde API

const productImage = computed(() => {
  return props.product.image || '/placeholder.jpg'
})

const hasDiscount = computed(() => {
  return props.product.sale_price && props.product.sale_price < props.product.price
})

const displayPrice = computed(() => {
  return props.product.sale_price || props.product.price
})

const discountPercent = computed(() => {
  if (!hasDiscount.value) return 0
  return Math.round((1 - props.product.sale_price / props.product.price) * 100)
})

const formatPrice = (price) => {
  return new Intl.NumberFormat('es-CO').format(price)
}

const addToCart = () => {
  cartStore.addItem(props.product, 1)
}

const toggleWishlist = async () => {
  if (!authStore.isAuthenticated) {
    router.push('/login')
    return
  }

  // Llamar API
  isInWishlist.value = !isInWishlist.value
}
</script>
```

## Stores (Pinia)

### authStore

```javascript
// src/stores/auth.js
import { defineStore } from 'pinia'
import authService from '@/services/authService'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('user') || 'null'),
    token: localStorage.getItem('token'),
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    isAdmin: (state) => state.user?.role === 'admin',
    isManager: (state) => ['manager', 'admin'].includes(state.user?.role),
    isCustomer: (state) => state.user?.role === 'customer',
  },

  actions: {
    async login(credentials) {
      const response = await authService.login(credentials)
      this.user = response.user
      this.token = response.token
      localStorage.setItem('user', JSON.stringify(response.user))
      localStorage.setItem('token', response.token)
    },

    async logout() {
      await authService.logout()
      this.user = null
      this.token = null
      localStorage.removeItem('user')
      localStorage.removeItem('token')
    },

    async register(userData) {
      const response = await authService.register(userData)
      this.user = response.user
      this.token = response.token
      localStorage.setItem('user', JSON.stringify(response.user))
      localStorage.setItem('token', response.token)
    },
  },
})
```

### cartStore

```javascript
// src/stores/cart.js
import { defineStore } from 'pinia'

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: JSON.parse(localStorage.getItem('cart') || '[]'),
  }),

  getters: {
    itemCount: (state) => state.items.reduce((sum, item) => sum + item.quantity, 0),

    subtotal: (state) => {
      return state.items.reduce((sum, item) => {
        const price = item.product.sale_price || item.product.price
        return sum + (price * item.quantity)
      }, 0)
    },

    tax: (state) => state.subtotal * 0.19,

    total(state) {
      return this.subtotal + this.tax + 5000 // 5000 = envío fijo por ahora
    },
  },

  actions: {
    addItem(product, quantity = 1) {
      const existingItem = this.items.find(item => item.product.id === product.id)

      if (existingItem) {
        existingItem.quantity += quantity
      } else {
        this.items.push({ product, quantity })
      }

      this.saveToLocalStorage()
    },

    updateQuantity(productId, quantity) {
      const item = this.items.find(item => item.product.id === productId)
      if (item) {
        item.quantity = quantity
        this.saveToLocalStorage()
      }
    },

    removeItem(productId) {
      this.items = this.items.filter(item => item.product.id !== productId)
      this.saveToLocalStorage()
    },

    clear() {
      this.items = []
      this.saveToLocalStorage()
    },

    saveToLocalStorage() {
      localStorage.setItem('cart', JSON.stringify(this.items))
    },
  },
})
```

---

# Sistema de Emails

## Configuración

### .env

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io  # Desarrollo
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@petuniaplay.com
MAIL_FROM_NAME="PetuniaPlay"
```

## Mailables

### OrderConfirmation.php

```php
namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this
            ->subject("Confirmación de Pedido #{$this->order->order_number}")
            ->view('emails.order-confirmation')
            ->with([
                'order' => $this->order,
                'trackingUrl' => route('tracking.show', [
                    'orderNumber' => $this->order->order_number
                ]),
            ]);
    }
}
```

### ShipmentUpdate.php

```php
namespace App\Mail;

use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShipmentUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $shipment;
    public $updateType;

    public function __construct(Order $order, Shipment $shipment, string $updateType)
    {
        $this->order = $order;
        $this->shipment = $shipment;
        $this->updateType = $updateType;
    }

    public function build()
    {
        $subjects = [
            'created' => '📦 Tu pedido ha sido despachado',
            'in_transit' => '🚚 Tu pedido está en camino',
            'delivered' => '✅ Tu pedido ha sido entregado',
            'failed' => '❌ Problema con la entrega de tu pedido',
        ];

        return $this
            ->subject($subjects[$this->updateType] ?? 'Actualización de tu pedido')
            ->view('emails.shipment-update')
            ->with([
                'order' => $this->order,
                'shipment' => $this->shipment,
                'updateType' => $this->updateType,
                'trackingUrl' => route('tracking.show', [
                    'orderNumber' => $this->order->order_number
                ]),
                'unsubscribeUrl' => $this->getUnsubscribeUrl(),
            ]);
    }

    private function getUnsubscribeUrl()
    {
        if ($this->order->user) {
            return route('user.unsubscribe', [
                'token' => $this->order->user->unsubscribe_token
            ]);
        }
        return null;
    }
}
```

## Plantillas Blade

### order-confirmation.blade.php

```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 0 auto; }
        .header { background: #8B4513; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .order-details { background: #f5f5f5; padding: 15px; margin: 20px 0; }
        .footer { background: #333; color: white; padding: 20px; text-align: center; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>¡Gracias por tu compra!</h1>
        </div>

        <div class="content">
            <p>Hola {{ $order->customer_name }},</p>

            <p>Tu pedido ha sido recibido y está siendo procesado.</p>

            <div class="order-details">
                <h2>Detalles del Pedido</h2>
                <p><strong>Número de Pedido:</strong> {{ $order->order_number }}</p>
                <p><strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Total:</strong> ${{ number_format($order->total, 0) }}</p>
            </div>

            <h3>Productos</h3>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->price, 0) }}</td>
                        <td>${{ number_format($item->subtotal, 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <h3>Dirección de Envío</h3>
            <p>
                {{ $order->shippingAddress->address_line_1 }}<br>
                {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }}<br>
                Teléfono: {{ $order->shippingAddress->phone }}
            </p>

            <p style="margin-top: 30px;">
                <a href="{{ $trackingUrl }}" style="background: #8B4513; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                    Rastrear mi Pedido
                </a>
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} PetuniaPlay. Todos los derechos reservados.</p>
            <p>Bogotá, Colombia | +57 305 759 4088</p>
        </div>
    </div>
</body>
</html>
```

---

# Configuración y Deployment

## Configuración Local

### Backend

```bash
# 1. Clonar repositorio
git clone https://github.com/your-repo/petuniaplay.git
cd petuniaplay/backend

# 2. Instalar dependencias
composer install

# 3. Copiar .env
cp .env.example .env

# 4. Generar key
php artisan key:generate

# 5. Configurar base de datos en .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=petuniaplay
DB_USERNAME=root
DB_PASSWORD=

# 6. Migrar y seedear
php artisan migrate --seed

# 7. Link storage
php artisan storage:link

# 8. Iniciar servidor
php artisan serve
```

### Frontend

```bash
cd frontend

# 1. Instalar dependencias
npm install

# 2. Configurar .env
cp .env.example .env

# Editar .env:
VITE_API_URL=http://localhost:8000
VITE_GOOGLE_MAPS_API_KEY=your_key_here

# 3. Iniciar dev server
npm run dev
```

## Deployment a Producción

### Checklist Pre-Deploy

- [ ] Variables de entorno configuradas
- [ ] Base de datos de producción creada
- [ ] Dominio apuntando al servidor
- [ ] Certificado SSL instalado
- [ ] Pasarela de pagos integrada
- [ ] SMTP configurado
- [ ] Google Maps API key con restricciones
- [ ] Almacenamiento de imágenes (S3/Cloudinary)
- [ ] Backups automatizados configurados

### Deploy Backend (Laravel)

```bash
# 1. Optimizaciones
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 2. Migraciones en producción
php artisan migrate --force

# 3. Permisos
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Nginx Config

```nginx
server {
    listen 80;
    server_name api.petuniaplay.com;
    root /var/www/petuniaplay/backend/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Deploy Frontend (Vue)

```bash
# 1. Build
npm run build

# 2. Subir dist/ al servidor
# Configurar Nginx para servir archivos estáticos
```

### Nginx Config (Frontend)

```nginx
server {
    listen 80;
    server_name petuniaplay.com www.petuniaplay.com;
    root /var/www/petuniaplay/frontend/dist;

    index index.html;

    location / {
        try_files $uri $uri/ /index.html;
    }

    # Cache assets
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

---

**Versión:** 1.0
**Última actualización:** 26 de Octubre 2025
**Desarrollador:** David González
