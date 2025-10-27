# 📦 Guía de Control de Despachos - PetuniaPlay

## Funciones Disponibles en el Backend

### 1. Ver Órdenes Pendientes de Despacho
```http
GET /api/v1/admin/orders/pending-shipment?per_page=20
```

**Retorna:** Órdenes pagadas que NO tienen envío creado
- Status: `pending` o `processing`
- Payment status: `paid`
- Ordenadas: Más antiguas primero (urgentes)

---

### 2. Ver Órdenes Ya Despachadas
```http
GET /api/v1/admin/orders/shipped?per_page=20
```

**Retorna:** Órdenes que YA tienen envío creado
- Incluye datos de `shipment` (tracking, carrier, status)
- Ordenadas: Más recientes primero

---

### 3. Estadísticas de Despacho
```http
GET /api/v1/admin/orders/shipping-stats
```

**Retorna:**
```json
{
  "pending_shipment": 15,        // Total pendientes
  "ready_to_ship": 8,             // Status processing
  "shipped": 45,                   // Total despachadas
  "in_transit": 12,                // En tránsito ahora
  "delivered": 33,                 // Entregadas
  "oldest_pending": [...]          // Top 5 más urgentes
}
```

---

## Cómo Crear un Envío

### Endpoint: Crear Shipment
```http
POST /api/v1/shipments
```

**Body:**
```json
{
  "order_id": 123,
  "tracking_number": "SERV-ABC123",
  "carrier": "Servientrega",
  "notes": "Envío express - Frágil"
}
```

**Resultado:**
- Se crea el registro de envío
- Orden cambia automáticamente a `processing`
- Cliente recibe email con tracking number

---

## Cómo Actualizar Estado de Envío

### Endpoint: Actualizar Shipment
```http
PUT /api/v1/shipments/{id}
```

**Body:**
```json
{
  "status": "in_transit"  // o "delivered", "failed", "returned"
}
```

**Estados disponibles:**
- `pending` - Pendiente de despacho
- `in_transit` - En tránsito
- `delivered` - Entregado
- `failed` - Falló el envío
- `returned` - Devuelto

**Automático:**
- Si cambias a `in_transit`: se marca `shipped_at`
- Si cambias a `delivered`: se marca `delivered_at` y orden → 'delivered'
- Cliente recibe email de notificación

---

## Ejemplo Frontend - AdminOrdersView.vue

```vue
<script setup>
import { ref, onMounted } from 'vue'
import api from '@/services/api'

const pendingOrders = ref([])
const shippedOrders = ref([])
const stats = ref(null)
const activeTab = ref('pending')

const loadPendingOrders = async () => {
  const response = await api.get('/api/v1/admin/orders/pending-shipment')
  pendingOrders.value = response.data.data
}

const loadShippedOrders = async () => {
  const response = await api.get('/api/v1/admin/orders/shipped')
  shippedOrders.value = response.data.data
}

const loadStats = async () => {
  const response = await api.get('/api/v1/admin/orders/shipping-stats')
  stats.value = response.data
}

const createShipment = async (orderId) => {
  try {
    const tracking = prompt('Número de tracking:')
    const carrier = prompt('Transportadora (ej: Servientrega):')

    await api.post('/api/v1/shipments', {
      order_id: orderId,
      tracking_number: tracking,
      carrier: carrier
    })

    alert('Envío creado exitosamente')
    loadPendingOrders()
    loadStats()
  } catch (error) {
    alert('Error al crear envío')
  }
}

onMounted(() => {
  loadPendingOrders()
  loadStats()
})
</script>

<template>
  <!-- Dashboard Stats -->
  <div v-if="stats" class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-yellow-100 p-4 rounded">
      <h3>⏳ Pendientes</h3>
      <p class="text-2xl font-bold">{{ stats.pending_shipment }}</p>
    </div>
    <div class="bg-blue-100 p-4 rounded">
      <h3>🚚 En Tránsito</h3>
      <p class="text-2xl font-bold">{{ stats.in_transit }}</p>
    </div>
    <div class="bg-green-100 p-4 rounded">
      <h3>✅ Entregadas</h3>
      <p class="text-2xl font-bold">{{ stats.delivered }}</p>
    </div>
    <div class="bg-gray-100 p-4 rounded">
      <h3>📦 Total Despachadas</h3>
      <p class="text-2xl font-bold">{{ stats.shipped }}</p>
    </div>
  </div>

  <!-- Tabs -->
  <div class="flex gap-4 mb-6">
    <button
      @click="activeTab = 'pending'; loadPendingOrders()"
      :class="activeTab === 'pending' ? 'btn-primary' : 'btn-secondary'"
    >
      Pendientes de Despacho ({{ stats?.pending_shipment || 0 }})
    </button>
    <button
      @click="activeTab = 'shipped'; loadShippedOrders()"
      :class="activeTab === 'shipped' ? 'btn-primary' : 'btn-secondary'"
    >
      Ya Despachadas
    </button>
  </div>

  <!-- Pending Orders -->
  <div v-if="activeTab === 'pending'">
    <div v-for="order in pendingOrders" :key="order.id" class="border p-4 mb-4">
      <h3>Orden #{{ order.order_number }}</h3>
      <p>Cliente: {{ order.user.name }}</p>
      <p>Total: ${{ order.total }}</p>
      <p>Hace: {{ Math.floor((Date.now() - new Date(order.created_at)) / (1000 * 60 * 60 * 24)) }} días</p>

      <button @click="createShipment(order.id)" class="btn-primary mt-2">
        📦 Crear Envío
      </button>
    </div>
  </div>

  <!-- Shipped Orders -->
  <div v-if="activeTab === 'shipped'">
    <div v-for="order in shippedOrders" :key="order.id" class="border p-4 mb-4">
      <h3>Orden #{{ order.order_number }}</h3>
      <p>Tracking: {{ order.shipment?.tracking_number }}</p>
      <p>Transportadora: {{ order.shipment?.carrier }}</p>
      <p>Estado: {{ order.shipment?.status }}</p>
    </div>
  </div>
</template>
```

---

## Estados de Orden

| Estado | Descripción | Tiene Shipment? |
|--------|-------------|-----------------|
| `pending` | Recién creada | ❌ No |
| `processing` | Pagada, lista para despachar | ❌ No (o Sí si ya se creó) |
| `shipped` | Despachada | ✅ Sí |
| `delivered` | Entregada | ✅ Sí |
| `cancelled` | Cancelada | ❌ No |

---

## Consultas Útiles desde MySQL

```sql
-- Ver órdenes pendientes de despacho
SELECT o.order_number, o.status, o.payment_status, o.created_at
FROM orders o
LEFT JOIN shipments s ON s.order_id = o.id
WHERE o.payment_status = 'paid'
  AND o.status IN ('pending', 'processing')
  AND s.id IS NULL;

-- Ver órdenes con envíos
SELECT o.order_number, s.tracking_number, s.carrier, s.status
FROM orders o
INNER JOIN shipments s ON s.order_id = o.id;

-- Contar por estado
SELECT
  COUNT(CASE WHEN s.id IS NULL AND o.payment_status = 'paid' THEN 1 END) as pendientes,
  COUNT(CASE WHEN s.status = 'in_transit' THEN 1 END) as en_transito,
  COUNT(CASE WHEN s.status = 'delivered' THEN 1 END) as entregados
FROM orders o
LEFT JOIN shipments s ON s.order_id = o.id;
```

---

## Resumen Rápido

**Para ver qué despachar:**
```bash
GET /api/v1/admin/orders/pending-shipment
```

**Para crear envío:**
```bash
POST /api/v1/shipments
{
  "order_id": 123,
  "tracking_number": "ABC123",
  "carrier": "Servientrega"
}
```

**Para actualizar estado:**
```bash
PUT /api/v1/shipments/{id}
{
  "status": "in_transit"
}
```

**Para ver estadísticas:**
```bash
GET /api/v1/admin/orders/shipping-stats
```
