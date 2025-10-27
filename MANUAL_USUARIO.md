# 📘 Manual de Usuario - PetuniaPlay

## Índice

1. [Introducción](#introducción)
2. [Roles y Permisos](#roles-y-permisos)
3. [Módulo Cliente](#módulo-cliente)
4. [Módulo Administración](#módulo-administración)
5. [Flujos de Trabajo](#flujos-de-trabajo)
6. [Preguntas Frecuentes](#preguntas-frecuentes)
7. [Solución de Problemas](#solución-de-problemas)

---

# Introducción

## ¿Qué es PetuniaPlay?

PetuniaPlay es una plataforma de e-commerce especializada en productos para mascotas. Permite a los clientes:
- Explorar y comprar productos
- Realizar seguimiento de pedidos
- Gestionar su cuenta y preferencias

Y a los administradores:
- Gestionar inventario y catálogo
- Procesar órdenes y envíos
- Administrar clientes y contenido

## Tecnologías Utilizadas

- **Frontend:** Vue.js 3 con Tailwind CSS
- **Backend:** Laravel 12
- **Base de datos:** MySQL/MariaDB
- **Mapas:** Google Maps API
- **Autenticación:** Laravel Sanctum

## Acceso al Sistema

**URL:** `http://localhost:5173` (desarrollo) o su dominio configurado

**Usuarios por Defecto:**

| Rol | Email | Contraseña |
|-----|-------|------------|
| Admin | admin@petuniaplay.com | password |
| Manager | manager@petuniaplay.com | password |
| Cliente | customer@petuniaplay.com | password |

---

# Roles y Permisos

## 1. Cliente (Customer)

**Permisos:**
- ✅ Ver catálogo de productos
- ✅ Agregar productos al carrito
- ✅ Realizar compras
- ✅ Ver historial de pedidos
- ✅ Gestionar direcciones guardadas
- ✅ Actualizar perfil
- ✅ Escribir reseñas de productos
- ✅ Gestionar lista de deseos (wishlist)
- ✅ Rastrear pedidos
- ❌ No tiene acceso al panel de administración

**¿Quién lo usa?**
Compradores finales, usuarios que compran productos para sus mascotas.

---

## 2. Manager (Gerente/Encargado)

**Permisos:**
- ✅ Todos los permisos de Cliente
- ✅ Acceso al panel de administración
- ✅ Gestionar productos (crear, editar, eliminar)
- ✅ Gestionar categorías
- ✅ Ver y actualizar órdenes
- ✅ Gestionar envíos y despachos
- ✅ Gestionar cupones de descuento
- ✅ Moderar reseñas (aprobar/rechazar)
- ✅ Ver reportes y estadísticas
- ✅ Exportar datos (Excel, PDF)
- ❌ No puede gestionar usuarios
- ❌ No puede cambiar roles

**¿Quién lo usa?**
Encargados de tienda, personal de inventario, coordinadores de logística.

---

## 3. Admin (Administrador)

**Permisos:**
- ✅ Todos los permisos de Manager
- ✅ Gestionar usuarios (crear, editar, eliminar)
- ✅ Cambiar roles de usuarios
- ✅ Activar/desactivar usuarios
- ✅ Acceso completo a toda la configuración
- ✅ Ver logs del sistema

**¿Quién lo usa?**
Propietarios del negocio, administradores de sistema, personal de IT.

---

# Módulo Cliente

## 1. Registro e Inicio de Sesión

### Crear una Cuenta Nueva

1. Ir a la página principal
2. Clic en **"Iniciar Sesión"** (esquina superior derecha)
3. Clic en **"¿No tienes cuenta? Regístrate"**
4. Llenar el formulario:
   - Nombre completo
   - Email (será tu usuario)
   - Contraseña (mínimo 8 caracteres)
   - Confirmar contraseña
5. Aceptar términos y condiciones
6. Clic en **"Registrarse"**

**Resultado:**
- Cuenta creada automáticamente
- Sesión iniciada
- Redirección a la página principal

### Iniciar Sesión

1. Clic en **"Iniciar Sesión"**
2. Ingresar email y contraseña
3. (Opcional) Marcar **"Recordarme"** para mantener sesión
4. Clic en **"Ingresar"**

### Recuperar Contraseña

1. En la página de login, clic en **"¿Olvidaste tu contraseña?"**
2. Ingresar tu email
3. Clic en **"Enviar enlace de recuperación"**
4. Revisar tu correo electrónico
5. Clic en el enlace recibido
6. Ingresar nueva contraseña
7. Confirmar nueva contraseña
8. Clic en **"Restablecer contraseña"**

---

## 2. Explorar Productos

### Ver Catálogo

**Ruta:** Inicio > Productos

**Funciones disponibles:**

1. **Filtrar por categoría:**
   - Sidebar izquierdo
   - Clic en categoría deseada
   - Se actualizan productos automáticamente

2. **Buscar productos:**
   - Barra de búsqueda superior
   - Escribir nombre o descripción
   - Resultados en tiempo real
   - Autocompletado

3. **Ordenar productos:**
   - Menú desplegable "Ordenar por:"
     - Más recientes
     - Precio: menor a mayor
     - Precio: mayor a menor
     - Nombre A-Z
     - Nombre Z-A
     - Más populares

4. **Ajustar rango de precio:**
   - Usar sliders de precio mínimo/máximo
   - Se filtran productos automáticamente

5. **Ver productos en oferta:**
   - Toggle "Solo en oferta"
   - Muestra productos con descuento

### Ver Detalle de Producto

**Acciones:**
1. Clic en cualquier producto
2. Se abre página de detalle con:
   - Galería de imágenes (clic para ampliar)
   - Nombre y descripción
   - Precio (y precio con descuento si aplica)
   - Stock disponible
   - SKU
   - Calificación promedio
   - Reseñas de clientes

**Funciones:**
- **Seleccionar cantidad:** Usar botones +/- o escribir número
- **Agregar al carrito:** Botón verde "Agregar al Carrito"
- **Agregar a lista de deseos:** Icono de corazón
- **Compartir:** Botones de redes sociales

---

## 3. Carrito de Compras

### Agregar Productos al Carrito

**Método 1: Desde el catálogo**
- Botón rápido "Agregar al Carrito" en cada tarjeta
- Se agrega 1 unidad

**Método 2: Desde el detalle**
- Seleccionar cantidad deseada
- Clic en "Agregar al Carrito"

**Resultado:**
- Notificación de confirmación
- Contador del carrito se actualiza
- Producto agregado

### Ver Carrito

**Ruta:** Icono de carrito > Ver Carrito

**Funciones:**

1. **Ver resumen:**
   - Lista de productos agregados
   - Cantidad de cada producto
   - Precio unitario
   - Subtotal por producto

2. **Modificar cantidad:**
   - Botones +/- junto a cada producto
   - Actualización automática de totales

3. **Eliminar producto:**
   - Botón de papelera/basura
   - Confirmación antes de eliminar

4. **Ver totales:**
   - Subtotal
   - Envío (calculado en checkout)
   - IVA (19%)
   - Total a pagar

5. **Aplicar cupón:**
   - Campo "Código de descuento"
   - Ingresar código
   - Clic en "Aplicar"
   - Descuento se refleja en total

### Continuar Comprando o Finalizar

- **"Seguir Comprando":** Vuelve al catálogo
- **"Proceder al Pago":** Ir a checkout

---

## 4. Proceso de Compra (Checkout)

### Paso 1: Información del Cliente

**Datos requeridos:**
- Nombre completo
- Email (para confirmación)
- Teléfono de contacto
- Documento de identidad

**Si estás registrado:**
- Datos se auto-completan
- Puedes editarlos si necesitas

**Si eres invitado (guest):**
- Llenar todos los campos manualmente

### Paso 2: Dirección de Envío

**Opción A: Escribir Dirección Manualmente**

1. Clic en **"✍️ Escribir Dirección"**
2. Llenar campos:
   - Dirección completa (Calle, número, apartamento)
   - Ciudad
   - Departamento/Estado
   - Código postal (opcional)
3. Notas adicionales (ej: "Apartamento 301, portero")

**Opción B: Seleccionar en Mapa**

1. Clic en **"📍 Seleccionar en Mapa"**
2. Opciones:
   - **Usar mi ubicación actual:** Botón de GPS
   - **Buscar dirección:** Barra de búsqueda en mapa
   - **Arrastrar pin:** Mover marcador al punto exacto
3. Sistema valida:
   - ✅ Si está dentro del área de cobertura (50 km de Bogotá)
   - ❌ Si está fuera (muestra advertencia pero permite continuar)
4. Dirección se auto-completa desde las coordenadas
5. Confirmar datos

**Si tienes direcciones guardadas:**
- Aparecen listadas
- Seleccionar una con un clic
- O crear una nueva

### Paso 3: Método de Pago

**Opciones disponibles:**

1. **💳 Tarjeta de crédito/débito**
   - Ingresar número de tarjeta
   - Fecha de vencimiento
   - CVV
   - Nombre en la tarjeta

2. **💰 Transferencia bancaria**
   - Se mostrará información de cuenta
   - Debes enviar comprobante

3. **📦 Pago contra entrega (COD)**
   - Pagas al recibir el pedido
   - Solo efectivo o datáfono

**Nota:** En producción se integrará pasarela real (Stripe/PayU).

### Paso 4: Revisión y Confirmación

**Revisar:**
- ✅ Productos y cantidades
- ✅ Dirección de envío correcta
- ✅ Método de pago seleccionado
- ✅ Totales (subtotal, IVA, envío, total)

**Términos:**
- ☑️ Marcar casilla "Acepto términos y condiciones"
- Enlaces a políticas disponibles

**Finalizar:**
- Clic en **"Realizar Pedido"**
- Esperar confirmación (loading)

### Confirmación de Pedido

**Pantalla de éxito:**
- ✅ Número de orden generado
- 📧 Email de confirmación enviado
- 📋 Resumen del pedido
- 🔗 Enlace de seguimiento

**Acciones disponibles:**
- Ver detalle completo del pedido
- Descargar factura (PDF)
- Seguir comprando
- Ir a "Mis Pedidos"

---

## 5. Mi Cuenta

### Acceder a Mi Cuenta

**Ruta:** Icono de usuario > Mi Cuenta

### Secciones Disponibles

#### 5.1. Perfil

**Información editable:**
- Nombre completo
- Email
- Teléfono
- Foto de perfil (opcional)

**Cambiar contraseña:**
1. Clic en "Cambiar contraseña"
2. Ingresar contraseña actual
3. Ingresar nueva contraseña
4. Confirmar nueva contraseña
5. Guardar cambios

#### 5.2. Mis Pedidos

**Vista de historial:**
- Lista de todos tus pedidos
- Más recientes primero

**Información visible:**
- Número de orden
- Fecha
- Estado (Pendiente, Procesando, Enviado, Entregado)
- Total
- Método de pago

**Acciones por pedido:**
- **Ver detalles:** Información completa
- **Rastrear:** Seguimiento de envío
- **Cancelar:** Si aún está en estado "Pendiente"
- **Descargar factura:** PDF

**Filtrar pedidos:**
- Por estado
- Por rango de fechas
- Por monto

#### 5.3. Direcciones Guardadas

**Ver direcciones:**
- Lista de todas las direcciones guardadas
- Una marcada como "Predeterminada"

**Acciones:**
- **Agregar nueva dirección:**
  1. Clic en "+ Nueva Dirección"
  2. Llenar formulario (igual que en checkout)
  3. Marcar si es predeterminada
  4. Guardar

- **Editar dirección:**
  1. Clic en "Editar" en la dirección
  2. Modificar campos necesarios
  3. Guardar cambios

- **Eliminar dirección:**
  1. Clic en "Eliminar"
  2. Confirmar acción
  3. No puedes eliminar si es la única

- **Marcar como predeterminada:**
  1. Clic en "Hacer predeterminada"
  2. Se usará automáticamente en próximas compras

#### 5.4. Lista de Deseos (Wishlist)

**Agregar productos:**
- Desde catálogo: clic en icono de corazón
- Desde detalle: botón "Agregar a lista de deseos"

**Gestionar wishlist:**
- Ver todos los productos guardados
- Mover al carrito con un clic
- Eliminar de la lista

**Compartir wishlist:**
- Generar enlace para compartir
- Útil para regalos o listas de cumpleaños

#### 5.5. Reseñas

**Ver tus reseñas:**
- Lista de productos que has calificado
- Texto de tu reseña
- Calificación (estrellas)
- Fecha

**Escribir reseña:**
1. Ir a detalle del producto
2. Scroll hasta sección "Reseñas"
3. Clic en "Escribir reseña"
4. Solo si has comprado el producto
5. Calificar de 1 a 5 estrellas
6. Escribir comentario (opcional)
7. Enviar

**Editar/Eliminar reseña:**
- Desde "Mis Reseñas"
- Modificar texto o calificación
- O eliminar completamente

#### 5.6. Preferencias de Notificaciones

**Configurar emails:**
- ☑️ Confirmaciones de pedidos (obligatorio)
- ☑️ Actualizaciones de envío (obligatorio)
- ☐ Ofertas y promociones (opcional)
- ☐ Nuevos productos (opcional)
- ☐ Newsletter semanal (opcional)

**Darse de baja:**
- Botón "Darme de baja de todos los emails"
- O desde enlace en cualquier email recibido

**Reactivar:**
- Volver a marcar casillas deseadas
- Guardar preferencias

---

## 6. Rastrear Pedido (Sin cuenta)

### Rastreo Público

**Ruta:** Footer > Rastrear Pedido

**Proceso:**
1. Ingresar número de orden (ej: ORD-20250001)
2. Ingresar email usado en la compra
3. Clic en "Rastrear"

**Información mostrada:**
- Estado actual del pedido
- Línea de tiempo:
  - ✅ Orden recibida
  - ⏳ Orden procesada
  - 📦 Orden despachada
  - 🚚 En tránsito
  - ✅ Entregada
- Número de tracking (si aplica)
- Transportadora
- Fecha estimada de entrega
- Dirección de envío

---

## 7. Páginas de Información

### Términos y Condiciones
**Ruta:** Footer > Términos y Condiciones

Información sobre:
- Uso del sitio
- Derechos y obligaciones
- Proceso de compra
- Garantías

### Política de Privacidad
**Ruta:** Footer > Política de Privacidad

Información sobre:
- Recolección de datos personales
- Uso de la información
- Derechos ARCO (Acceso, Rectificación, Cancelación, Oposición)
- Cumplimiento Ley 1581/2012 (Colombia)

### Política de Devoluciones
**Ruta:** Footer > Devoluciones y Reembolsos

Información sobre:
- Plazo para devoluciones (14 días)
- Condiciones del producto
- Proceso de devolución
- Reembolsos y tiempos

### Política de Envíos
**Ruta:** Footer > Política de Envíos

Información sobre:
- Áreas de cobertura
- Tiempos de entrega
- Costos de envío
- Transportadoras

### Preguntas Frecuentes (FAQ)
**Ruta:** Footer > Preguntas Frecuentes

Respuestas a:
- ¿Cómo comprar?
- ¿Métodos de pago?
- ¿Tiempos de entrega?
- ¿Cómo devolver?
- Y más...

### Sobre Nosotros
**Ruta:** Footer > Sobre Nosotros

Información sobre:
- Historia de PetuniaPlay
- Misión y visión
- Compromiso con las mascotas

### Contacto
**Ruta:** Footer > Contacto

Formulario de contacto:
- Nombre
- Email
- Asunto
- Mensaje

Información de contacto:
- 📧 Email: contacto@petuniaplay.com
- 📞 Teléfono: +57 305 759 4088
- 📍 Dirección: Bogotá, Colombia

---

# Módulo Administración

## Acceso al Panel de Administración

**Requisitos:**
- Tener rol de Manager o Admin
- Haber iniciado sesión

**Acceso:**
- Menú de usuario > "Panel de Administración"
- O directamente: `/admin`

---

## 1. Dashboard Principal

### Vista General

**Widgets de estadísticas:**

1. **💰 Ventas del Mes**
   - Total en dinero del mes actual
   - Comparación con mes anterior (% cambio)

2. **📦 Pedidos del Mes**
   - Cantidad de pedidos recibidos
   - Comparación con mes anterior

3. **👥 Nuevos Clientes**
   - Usuarios registrados este mes
   - Tendencia

4. **📊 Productos Activos**
   - Total de productos disponibles
   - Con stock y publicados

### Gráficos

**Ventas por Día (Últimos 30 días)**
- Gráfico de líneas
- Muestra tendencia de ventas
- Identifica días pico

**Productos Más Vendidos (Top 10)**
- Gráfico de barras
- Ordenados por cantidad vendida
- Útil para reabastecimiento

### Alertas y Acciones Rápidas

**🔴 Productos sin Stock**
- Lista de productos agotados
- Acción: Ir a editar producto

**🟡 Stock Bajo (< 10 unidades)**
- Lista de productos próximos a agotarse
- Acción: Reabastecer

**⏳ Pedidos Pendientes de Despacho**
- Órdenes pagadas sin envío creado
- Acción: Crear envío

**📝 Reseñas Pendientes de Aprobación**
- Reseñas esperando moderación
- Acción: Aprobar/rechazar

### Exportar Reportes

**Botones disponibles:**
- **📊 Exportar a Excel:** Descargar datos en .xlsx
- **📄 Exportar a PDF:** Descargar reporte en PDF

**Datos incluidos:**
- Ventas del período
- Productos vendidos
- Estadísticas generales

---

## 2. Gestión de Productos

### Ver Productos

**Ruta:** Admin > Productos

**Vista de tabla:**
- Imagen miniatura
- SKU
- Nombre
- Categoría
- Precio
- Stock
- Estado (Publicado/Borrador)
- Destacado (Sí/No)
- Acciones

**Filtros disponibles:**
- 🔍 Buscar por nombre o SKU
- 📁 Filtrar por categoría
- ⭐ Solo destacados
- 📦 Estado de stock (Todos, En stock, Stock bajo, Sin stock)
- 🎯 Estado (Publicados, Borradores)

**Ordenar por:**
- Más recientes
- Nombre A-Z
- Precio bajo-alto
- Stock bajo-alto

**Paginación:**
- 20 productos por página
- Navegación inferior

**Acciones masivas:**
- Seleccionar múltiples productos (checkboxes)
- Publicar/Despublicar en lote
- Destacar/No destacar
- Eliminar múltiples

### Crear Producto Nuevo

**Ruta:** Admin > Productos > + Nuevo Producto

**Paso 1: Información Básica**

1. **Nombre del producto** (requerido)
   - Mínimo 3 caracteres
   - Ejemplo: "Cama Ortopédica para Perros - Talla M"

2. **Slug** (auto-generado)
   - URL amigable
   - Se genera desde el nombre
   - Editable si necesitas

3. **SKU** (requerido)
   - Código único del producto
   - Ejemplo: "CAM-ORT-M-001"
   - No puede repetirse

4. **Descripción Corta** (requerida)
   - 2-3 líneas
   - Se muestra en catálogo
   - Máximo 200 caracteres

5. **Descripción Completa** (requerida)
   - Editor de texto enriquecido
   - Detalles, características, materiales
   - Beneficios del producto

**Paso 2: Categoría y Precios**

6. **Categoría** (requerida)
   - Seleccionar de lista desplegable
   - Una categoría por producto

7. **Precio** (requerido)
   - En pesos colombianos (COP)
   - Sin separadores de miles
   - Ejemplo: 45000

8. **Precio con Descuento** (opcional)
   - Si el producto está en oferta
   - Debe ser menor al precio normal
   - Ejemplo: 35000

**Paso 3: Inventario**

9. **Stock Actual** (requerido)
   - Cantidad disponible
   - Se descuenta automáticamente al vender
   - Ejemplo: 50

10. **Stock Mínimo** (opcional)
    - Para alertas de reabastecimiento
    - Por defecto: 10

11. **¿Gestionar stock?**
    - ✅ Sí: Sistema controla inventario
    - ❌ No: Stock ilimitado (productos digitales)

**Paso 4: Imágenes**

12. **Imagen Principal** (requerida)
    - Primera imagen del producto
    - Formatos: JPG, PNG, WebP
    - Tamaño recomendado: 800x800px
    - Máximo: 2MB

13. **Galería de Imágenes** (opcional)
    - Hasta 5 imágenes adicionales
    - Mismas especificaciones
    - Orden: arrastrar y soltar

**Cargar imágenes:**
- Clic en "Seleccionar imagen"
- Elegir archivo de tu computadora
- Esperar a que cargue
- Vista previa aparece

**Eliminar imagen:**
- Clic en X sobre la imagen
- Confirmación

**Paso 5: SEO y Visibilidad**

14. **Meta Título** (opcional)
    - Para motores de búsqueda
    - Por defecto: nombre del producto
    - Máximo: 60 caracteres

15. **Meta Descripción** (opcional)
    - Descripción para Google
    - Por defecto: descripción corta
    - Máximo: 160 caracteres

16. **¿Producto Destacado?**
    - ✅ Sí: Aparece en home y destacados
    - ❌ No: Solo en catálogo

17. **Estado**
    - 🟢 Publicado: Visible para clientes
    - ⚫ Borrador: Solo visible en admin

**Paso 6: Guardar**

- **"Guardar como Borrador":** Guarda sin publicar
- **"Publicar Producto":** Guarda y publica inmediatamente

### Editar Producto

**Acceso:**
- Desde tabla de productos > Clic en "Editar" (icono lápiz)
- Todos los campos editables
- Mismo formulario que crear

**Cambios se reflejan inmediatamente**

### Eliminar Producto

**Proceso:**
1. Clic en botón "Eliminar" (icono papelera)
2. Confirmación: "¿Estás seguro?"
3. Clic en "Sí, eliminar"

**⚠️ Advertencias:**
- Si el producto tiene pedidos asociados, no se puede eliminar
- Opción: Despublicar en su lugar
- La eliminación es permanente

### Gestión de Stock

**Actualizar stock rápidamente:**
1. Desde tabla de productos
2. Clic en número de stock
3. Ingresar nueva cantidad
4. Enter para guardar

**Historial de stock:**
- Ver cambios de inventario
- Fecha y hora
- Cantidad anterior/nueva
- Usuario que modificó

### Productos Destacados

**Marcar como destacado:**
- Checkbox en edición de producto
- O desde tabla con toggle rápido

**Dónde aparecen:**
- Sección "Destacados" en home
- Primer lugar en catálogo
- Búsquedas priorizadas

### Exportar Productos

**Formato Excel:**
- Todos los productos con sus datos
- Útil para:
  - Respaldos
  - Análisis externo
  - Actualización masiva

**Proceso:**
1. Clic en "Exportar a Excel"
2. Descarga automática
3. Archivo .xlsx con todas las columnas

---

## 3. Gestión de Categorías

### Ver Categorías

**Ruta:** Admin > Categorías

**Vista de tabla:**
- Imagen de categoría
- Nombre
- Slug
- Cantidad de productos
- Acciones

### Crear Categoría

**Ruta:** Admin > Categorías > + Nueva Categoría

**Campos:**

1. **Nombre** (requerido)
   - Ejemplo: "Alimentos para Perros"

2. **Slug** (auto-generado)
   - Ejemplo: "alimentos-para-perros"
   - Editable

3. **Descripción** (opcional)
   - Información sobre la categoría
   - Se muestra en página de categoría

4. **Imagen** (opcional)
   - Imagen representativa
   - 600x400px recomendado

5. **Orden** (opcional)
   - Número para ordenar categorías
   - Menor número = mayor prioridad

**Guardar:**
- Clic en "Guardar Categoría"

### Editar Categoría

- Similar a crear
- Actualiza automáticamente productos asociados

### Eliminar Categoría

**⚠️ Restricción:**
- No puedes eliminar si tiene productos asociados
- Opción 1: Reasignar productos a otra categoría
- Opción 2: Eliminar productos primero

---

## 4. Gestión de Órdenes

### Ver Órdenes

**Ruta:** Admin > Pedidos

**Vista de tabla:**
- Número de orden
- Cliente
- Fecha
- Total
- Estado de orden
- Estado de pago
- Acciones

**Estados de Orden:**
- 🟡 **Pending (Pendiente):** Recién creada
- 🔵 **Processing (Procesando):** Pagada, preparando
- 🟣 **Shipped (Enviada):** Despachada
- 🟢 **Delivered (Entregada):** Recibida por cliente
- 🔴 **Cancelled (Cancelada):** Orden cancelada

**Estados de Pago:**
- ⏳ **Pending:** Esperando pago
- ✅ **Paid:** Pagado
- ❌ **Failed:** Pago fallido
- 💰 **Refunded:** Reembolsado

**Filtros disponibles:**
- 📅 Rango de fechas (desde - hasta)
- 📊 Estado de orden
- 💳 Estado de pago
- 🔍 Buscar por número de orden o cliente

**Ordenar por:**
- Más recientes primero
- Más antiguas primero
- Mayor monto
- Menor monto

### Ver Detalle de Orden

**Acceso:**
- Clic en número de orden
- O botón "Ver" en acciones

**Información completa:**

**1. Datos del Cliente:**
- Nombre
- Email
- Teléfono
- Documento

**2. Dirección de Envío:**
- Dirección completa
- Ciudad, estado
- Código postal
- Coordenadas (si se usó mapa)
- Notas de entrega

**3. Productos Ordenados:**
- Tabla con:
  - Imagen del producto
  - Nombre
  - SKU
  - Cantidad
  - Precio unitario
  - Subtotal

**4. Resumen Financiero:**
- Subtotal
- IVA (19%)
- Costo de envío
- Descuento (si hay cupón)
- **Total**

**5. Información de Pago:**
- Método de pago usado
- Estado de pago
- Fecha de pago (si aplica)

**6. Información de Envío:**
- Número de tracking (si existe)
- Transportadora
- Estado de envío
- Fecha de despacho
- Fecha de entrega estimada/real

**7. Línea de Tiempo:**
- Historial de cambios de estado
- Fecha y hora de cada cambio
- Usuario que realizó el cambio

### Actualizar Estado de Orden

**Proceso:**
1. En detalle de orden
2. Sección "Estado de la Orden"
3. Seleccionar nuevo estado del dropdown
4. Clic en "Actualizar Estado"
5. Confirmación

**Flujo normal:**
```
Pending → Processing → Shipped → Delivered
```

**⚠️ Reglas:**
- No puedes cambiar de Delivered a otro estado
- Si cancelas, se devuelve el stock
- Cliente recibe email de notificación

### Cancelar Orden

**Restricciones:**
- Solo si estado es Pending o Processing
- No puedes cancelar si ya está Shipped

**Proceso:**
1. Botón "Cancelar Orden"
2. Ingresar motivo de cancelación
3. Confirmar

**Resultado:**
- Orden cambia a "Cancelled"
- Stock se devuelve al inventario
- Cliente recibe email de notificación
- Si estaba pagada, proceder con reembolso manual

### Exportar Órdenes

**Botones:**
- **Excel:** Todas las órdenes filtradas
- **PDF:** Reporte detallado

**Datos incluidos:**
- Número de orden
- Cliente
- Fecha
- Productos
- Totales
- Estados

---

## 5. Control de Despachos

### Vista de Despachos

**Ruta:** Admin > Envíos > Control de Despachos

**Estadísticas en Dashboard:**
- ⏳ **Por Despachar:** Órdenes pagadas sin envío creado
- 📦 **Listas para Enviar:** En estado Processing
- 🚚 **Despachadas:** Total con envío creado
- 🛣️ **En Tránsito:** Actualmente en camino
- ✅ **Entregadas:** Completadas

### Pestañas Disponibles

#### 1. Pendientes de Despacho

**Muestra:**
- Órdenes pagadas (Payment Status = Paid)
- Sin envío creado aún
- Estados: Pending o Processing

**Vista de tabla:**
- Número de orden
- Cliente
- Total
- Días de espera (alerta si > 3 días)
- Dirección de envío
- Acción: "📦 Crear Envío"

**Alertas de urgencia:**
- 🟢 0-1 días: Normal
- 🟡 2-3 días: Atención
- 🔴 4+ días: Urgente (resaltado en rojo)

**Crear Envío Rápido:**
1. Clic en "📦 Crear Envío"
2. Modal se abre con datos pre-cargados:
   - Orden ya seleccionada
   - Cliente y dirección visible
3. Llenar:
   - **Tracking Number:** Ejemplo: SERV-123456
   - **Transportadora:** Servientrega, Coordinadora, Deprisa, etc.
   - **Notas:** Información adicional
4. Clic en "Crear Envío"

**Resultado:**
- Envío creado
- Orden cambia a "Processing" automáticamente
- Cliente recibe email con tracking
- Orden desaparece de "Pendientes"
- Aparece en "Ya Despachadas"

#### 2. Ya Despachadas

**Muestra:**
- Órdenes que ya tienen envío creado
- Cualquier estado de envío

**Vista de tabla:**
- Número de orden
- Cliente
- Tracking Number
- Transportadora
- Estado de envío
- Fecha de despacho
- Acciones

**Acciones disponibles:**
- **Ver detalles:** Información completa
- **Actualizar estado:** Cambiar estado de envío
- **Editar:** Modificar tracking o transportadora

#### 3. Todos los Envíos

**Muestra:**
- Lista completa de shipments
- Filtros combinados

**Filtros:**
- Por estado de envío
- Por transportadora
- Por rango de fechas
- Por número de tracking

### Actualizar Estado de Envío

**Estados disponibles:**
- ⏳ **Pending:** Creado pero no despachado
- 🚚 **In Transit:** En camino al cliente
- ✅ **Delivered:** Entregado exitosamente
- ❌ **Failed:** Falló la entrega
- 🔙 **Returned:** Devuelto al remitente

**Proceso:**
1. Desde "Ya Despachadas" o "Todos los Envíos"
2. Clic en "Actualizar Estado"
3. Seleccionar nuevo estado
4. (Opcional) Agregar notas
5. Guardar

**Automatizaciones:**
- `In Transit` → Se marca `shipped_at` (fecha de despacho)
- `Delivered` → Se marca `delivered_at` y orden → "Delivered"
- Cliente recibe email automático con cada cambio

### Ver Detalles de Envío

**Información mostrada:**
- Número de orden asociada
- Cliente y dirección
- Tracking number
- Transportadora
- Estado actual
- Fechas:
  - Creación del envío
  - Despacho
  - Entrega estimada
  - Entrega real
- Notas
- Historial de cambios de estado

### Editar Envío

**Campos editables:**
- Tracking number (si hubo error)
- Transportadora
- Notas
- Estado

**⚠️ No editable:**
- Orden asociada
- Fechas automáticas

### Órdenes Urgentes - Alerta Especial

**Banner rojo en la parte superior:**
- Aparece si hay órdenes con 4+ días de espera
- Lista las 5 más urgentes
- Botón "Crear Envío" directo
- Priorizar estas órdenes

**Ejemplo:**
```
¡ÓRDENES URGENTES! - 3 órdenes esperando despacho

ORD-20250045 • Cliente: Juan Pérez • 5 días • [Crear Envío]
ORD-20250038 • Cliente: María López • 4 días • [Crear Envío]
ORD-20250041 • Cliente: Carlos Ruiz • 4 días • [Crear Envío]
```

---

## 6. Gestión de Cupones

### Ver Cupones

**Ruta:** Admin > Cupones

**Vista de tabla:**
- Código del cupón
- Tipo (Porcentaje / Monto Fijo)
- Valor
- Usos (actuales / máximos)
- Válido desde/hasta
- Estado (Activo/Inactivo)
- Acciones

### Crear Cupón

**Ruta:** Admin > Cupones > + Nuevo Cupón

**Campos:**

1. **Código** (requerido)
   - Texto único
   - Solo letras, números, guiones
   - Ejemplo: "BIENVENIDA10", "VERANO2025"
   - Máximo 20 caracteres

2. **Descripción** (opcional)
   - Nota interna sobre el cupón
   - No visible para clientes
   - Ejemplo: "Cupón de bienvenida nuevos clientes"

3. **Tipo** (requerido)
   - 📊 **Porcentaje:** Descuento del X%
   - 💵 **Monto Fijo:** Descuento de $X pesos

4. **Valor** (requerido)
   - Si es porcentaje: 1-100
   - Si es monto fijo: cantidad en pesos
   - Ejemplo Porcentaje: 15 (15% de descuento)
   - Ejemplo Fijo: 10000 ($10,000 de descuento)

5. **Monto Mínimo de Compra** (opcional)
   - Compra mínima para usar el cupón
   - Ejemplo: 50000 (cupón válido si compra ≥ $50,000)
   - Dejar vacío: sin mínimo

6. **Límite de Usos** (opcional)
   - Número máximo de veces que se puede usar
   - Ejemplo: 100 (solo 100 personas pueden usarlo)
   - Dejar vacío: usos ilimitados

7. **Fecha de Inicio** (requerido)
   - Desde cuándo es válido
   - Formato: YYYY-MM-DD HH:MM
   - Ejemplo: 2025-01-01 00:00

8. **Fecha de Expiración** (requerido)
   - Hasta cuándo es válido
   - Formato: YYYY-MM-DD HH:MM
   - Ejemplo: 2025-12-31 23:59

9. **Estado** (requerido)
   - ✅ **Activo:** Puede ser usado
   - ❌ **Inactivo:** No puede ser usado

**Guardar:**
- Clic en "Crear Cupón"

### Editar Cupón

**Proceso:**
- Similar a crear
- Todos los campos editables excepto código
- Cambios se aplican inmediatamente

**⚠️ Consideración:**
- Si hay usos registrados, ten cuidado al editar
- Cambiar el valor afecta futuros usos, no pasados

### Desactivar/Activar Cupón

**Toggle rápido:**
- Desde tabla de cupones
- Switch de Activo/Inactivo
- Inmediato

**Uso:**
- Desactivar temporalmente sin eliminar
- Reactivar cuando necesites

### Eliminar Cupón

**Restricción:**
- No puedes eliminar si tiene usos registrados
- Opción: Desactivar en su lugar

### Estadísticas de Cupones

**Métricas visibles:**
- Veces usado / Límite
- Total descontado (dinero)
- Órdenes con este cupón
- Última vez usado

**Cupones más usados:**
- Ranking de popularidad
- Útil para estrategia de marketing

---

## 7. Gestión de Usuarios (Solo Admin)

**⚠️ Requiere rol de ADMIN**

### Ver Usuarios

**Ruta:** Admin > Usuarios

**Vista de tabla:**
- Foto de perfil
- Nombre
- Email
- Rol (Customer, Manager, Admin)
- Estado (Activo/Inactivo)
- Fecha de registro
- Acciones

**Filtros:**
- Por rol
- Por estado (activo/inactivo)
- Buscar por nombre o email

### Ver Detalle de Usuario

**Información:**
- Datos personales
- Fecha de registro
- Última actividad
- Total de pedidos
- Total gastado
- Direcciones guardadas
- Reseñas escritas

### Cambiar Rol de Usuario

**Roles disponibles:**
- 👤 **Customer:** Cliente normal
- 👨‍💼 **Manager:** Acceso a panel admin (limitado)
- 👨‍💻 **Admin:** Acceso completo

**Proceso:**
1. Ver detalle del usuario
2. Sección "Rol"
3. Seleccionar nuevo rol
4. Clic en "Actualizar Rol"
5. Confirmación

**⚠️ Cuidado:**
- Solo admins pueden cambiar roles
- No puedes cambiar tu propio rol
- Otorgar admin con precaución

### Activar/Desactivar Usuario

**Desactivar:**
- Usuario no puede iniciar sesión
- Mantiene sus datos
- No se eliminan sus pedidos
- Puede reactivarse después

**Proceso:**
1. Ver detalle del usuario
2. Toggle "Estado"
3. Confirmar

**Casos de uso:**
- Usuarios problemáticos
- Cuentas fraudulentas
- Suspensión temporal

### Crear Usuario Manualmente

**Ruta:** Admin > Usuarios > + Nuevo Usuario

**Campos:**
- Nombre
- Email (será el username)
- Contraseña
- Rol
- Estado (Activo por defecto)

**Guardar:**
- Usuario recibe email de bienvenida
- Puede iniciar sesión inmediatamente

### Eliminar Usuario

**⚠️ Restricción:**
- No puedes eliminar si tiene pedidos
- Opción: Desactivar en su lugar

---

## 8. Gestión de Reseñas

### Ver Reseñas

**Ruta:** Admin > Reseñas

**Vista de tabla:**
- Producto
- Cliente
- Calificación (⭐ estrellas)
- Comentario (primeras palabras)
- Fecha
- Estado (Aprobado/Pendiente)
- Acciones

**Filtros:**
- Por producto
- Por calificación (1-5 estrellas)
- Por estado (aprobado/pendiente)
- Buscar por cliente o comentario

### Aprobar Reseña

**Proceso:**
1. Reseña entra como "Pendiente"
2. Admin revisa contenido
3. Si es apropiada: Clic en "Aprobar"
4. Reseña se hace pública

**Criterios para aprobar:**
- ✅ Lenguaje apropiado
- ✅ Relacionado con el producto
- ✅ No spam
- ✅ No información personal sensible

### Rechazar Reseña

**Proceso:**
1. Clic en "Rechazar"
2. (Opcional) Ingresar motivo
3. Confirmar

**Resultado:**
- Reseña no se publica
- Cliente NO es notificado
- Reseña archivada

**Motivos comunes:**
- Lenguaje inapropiado
- Spam
- Contenido ofensivo
- No relacionado con el producto

### Eliminar Reseña

**Diferencia con rechazar:**
- Eliminar: Borra permanentemente
- Rechazar: Guarda pero no publica

**Proceso:**
1. Clic en "Eliminar"
2. Confirmación
3. Permanente

### Ver Detalle de Reseña

**Información:**
- Producto completo
- Cliente (con historial de compras)
- Calificación detallada
- Comentario completo
- Fecha y hora
- ¿Cliente compró el producto? (verificado)

### Estadísticas de Reseñas

**Métricas:**
- Total de reseñas
- Pendientes de moderación
- Calificación promedio del sitio
- Productos más reseñados
- Clientes más activos

---

## 9. Reportes y Exportaciones

### Tipos de Reportes

#### 1. Reporte de Ventas

**Incluye:**
- Período seleccionado (día, semana, mes, año, personalizado)
- Total de ventas
- Número de órdenes
- Ticket promedio
- Comparación con período anterior

**Formatos:**
- 📊 **Excel:** Datos tabulares para análisis
- 📄 **PDF:** Documento profesional para imprimir

#### 2. Reporte de Productos

**Incluye:**
- Lista completa de productos
- Stock actual
- Valor del inventario
- Productos más vendidos
- Productos sin movimiento

**Formatos:**
- 📊 Excel
- 📄 PDF

#### 3. Reporte de Clientes

**Incluye:**
- Lista de clientes
- Total gastado por cliente
- Frecuencia de compra
- Clientes nuevos vs recurrentes

**Formatos:**
- 📊 Excel

#### 4. Reporte de Órdenes

**Incluye:**
- Todas las órdenes del período
- Estados
- Métodos de pago
- Análisis por estado

**Formatos:**
- 📊 Excel
- 📄 PDF

### Generar Reporte Personalizado

**Proceso:**
1. Seleccionar tipo de reporte
2. Seleccionar rango de fechas:
   - Hoy
   - Última semana
   - Último mes
   - Último año
   - Personalizado (desde - hasta)
3. (Opcional) Aplicar filtros adicionales
4. Elegir formato (Excel/PDF)
5. Clic en "Generar Reporte"
6. Descarga automática

### Programar Reportes (Futuro)

**Funcionalidad planeada:**
- Reportes automáticos semanales/mensuales
- Envío por email
- Dashboards en tiempo real

---

# Flujos de Trabajo

## Flujo 1: Cliente Realiza una Compra

```
1. Cliente navega catálogo
   ↓
2. Busca/filtra productos
   ↓
3. Ve detalle del producto
   ↓
4. Agrega al carrito (cantidad deseada)
   ↓
5. Continúa comprando o va al carrito
   ↓
6. Revisa carrito
   ↓
7. Aplica cupón (si tiene)
   ↓
8. Clic en "Proceder al Pago"
   ↓
9. Llena datos de cliente (o auto-completa si está registrado)
   ↓
10. Selecciona/escribe dirección de envío
   ↓
11. Selecciona método de pago
   ↓
12. Revisa resumen
   ↓
13. Acepta términos
   ↓
14. Clic en "Realizar Pedido"
   ↓
15. Orden creada (Estado: Pending, Payment: Pending)
   ↓
16. Pago procesado
   ↓
17. Si pago exitoso:
    - Payment Status → Paid
    - Order Status → Processing
    - Email de confirmación enviado
    - Stock descontado
   ↓
18. Cliente recibe email con número de orden y tracking link
```

---

## Flujo 2: Admin Procesa una Orden

```
1. Admin accede al panel
   ↓
2. Ve alerta: "Pedidos Pendientes de Despacho"
   ↓
3. Va a Admin > Envíos > Control de Despachos
   ↓
4. Pestaña "Pendientes de Despacho"
   ↓
5. Ve lista de órdenes pagadas sin envío
   ↓
6. Identifica órdenes urgentes (4+ días)
   ↓
7. Selecciona una orden
   ↓
8. Clic en "📦 Crear Envío"
   ↓
9. Modal se abre con datos de la orden
   ↓
10. Admin prepara el paquete físicamente
   ↓
11. Contacta/programa recogida con transportadora
   ↓
12. Transportadora proporciona tracking number
   ↓
13. Admin ingresa en modal:
    - Tracking Number: "SERV-ABC123"
    - Transportadora: "Servientrega"
    - Notas: "Frágil - Contiene alimento"
   ↓
14. Clic en "Crear Envío"
   ↓
15. Sistema:
    - Crea registro de shipment
    - Order Status → Processing (si estaba Pending)
    - Shipment Status → Pending
    - Email enviado al cliente con tracking
   ↓
16. Cliente recibe email: "Tu pedido ha sido despachado"
   ↓
17. Transportadora recoge el paquete
   ↓
18. Admin actualiza estado de envío:
    - Va a "Ya Despachadas"
    - Encuentra la orden
    - Clic en "Actualizar Estado"
    - Selecciona "In Transit"
    - Guarda
   ↓
19. Sistema:
    - Shipment Status → In Transit
    - Marca shipped_at (fecha actual)
    - Order Status → Shipped
    - Email enviado: "Tu pedido está en camino"
   ↓
20. Cliente recibe paquete
   ↓
21. Admin actualiza estado:
    - Selecciona "Delivered"
    - Guarda
   ↓
22. Sistema:
    - Shipment Status → Delivered
    - Marca delivered_at
    - Order Status → Delivered
    - Email enviado: "Tu pedido ha sido entregado"
   ↓
23. Proceso completado ✅
```

---

## Flujo 3: Cliente Cancela una Orden

```
1. Cliente va a "Mi Cuenta" > "Mis Pedidos"
   ↓
2. Encuentra la orden reciente
   ↓
3. Estado debe ser "Pending"
   ↓
4. Clic en "Cancelar Pedido"
   ↓
5. Sistema pregunta: "¿Motivo de cancelación?"
   ↓
6. Cliente selecciona/escribe motivo
   ↓
7. Confirma cancelación
   ↓
8. Sistema:
    - Order Status → Cancelled
    - Devuelve stock al inventario
    - Si estaba pagado: marca para reembolso manual
    - Email de confirmación
   ↓
9. Admin recibe notificación (dashboard)
   ↓
10. Admin procesa reembolso manualmente (si aplica)
```

---

## Flujo 4: Manager Agrega Nuevo Producto

```
1. Manager accede al panel
   ↓
2. Admin > Productos
   ↓
3. Clic en "+ Nuevo Producto"
   ↓
4. Llena información básica:
    - Nombre: "Collar Antipulgas Natural - Talla M"
    - SKU: "COL-ANT-M-001"
    - Descripción corta y completa
   ↓
5. Selecciona categoría: "Accesorios > Collares"
   ↓
6. Ingresa precios:
    - Precio: $35,000
    - Precio con descuento: $28,000 (20% off)
   ↓
7. Ingresa inventario:
    - Stock: 100 unidades
    - Stock mínimo: 10
   ↓
8. Sube imágenes:
    - Imagen principal: collar-frontal.jpg
    - Galería: collar-lado.jpg, collar-detalle.jpg
   ↓
9. Configura SEO:
    - Meta título: "Collar Antipulgas Natural para Perros | PetuniaPlay"
    - Meta descripción: "Protege a tu perro con nuestro collar antipulgas 100% natural..."
   ↓
10. Marca como "Destacado": ✅
   ↓
11. Estado: "Publicado"
   ↓
12. Clic en "Publicar Producto"
   ↓
13. Sistema:
    - Producto guardado en base de datos
    - Imágenes optimizadas y almacenadas
    - Aparece inmediatamente en catálogo
    - Visible en "Destacados" del home
   ↓
14. Manager verifica en frontend
   ↓
15. Producto disponible para compra ✅
```

---

## Flujo 5: Admin Modera una Reseña

```
1. Cliente compra producto
   ↓
2. Recibe el producto
   ↓
3. Cliente va a "Mis Pedidos" > Ver detalle
   ↓
4. Clic en producto > "Escribir reseña"
   ↓
5. Califica 5 estrellas
   ↓
6. Escribe: "Excelente calidad, mi perro lo adora!"
   ↓
7. Envía reseña
   ↓
8. Sistema:
    - Reseña guardada con estado "Pendiente"
    - No visible públicamente aún
   ↓
9. Admin ve notificación: "1 reseña pendiente"
   ↓
10. Admin > Reseñas
   ↓
11. Ve la nueva reseña
   ↓
12. Admin revisa:
    - ✅ Lenguaje apropiado
    - ✅ Relacionado con el producto
    - ✅ Cliente verificado (compró el producto)
   ↓
13. Clic en "Aprobar"
   ↓
14. Sistema:
    - Reseña Status → Aprobado
    - Visible públicamente
    - Calificación promedio del producto se actualiza
   ↓
15. Otros clientes pueden ver la reseña ✅
```

---

## Flujo 6: Cliente Usa Cupón de Descuento

```
1. Admin crea cupón:
    - Código: "VERANO25"
    - Tipo: Porcentaje
    - Valor: 25%
    - Mínimo: $50,000
    - Válido hasta: 31/03/2025
   ↓
2. Admin activa cupón
   ↓
3. (Opcional) Admin envía email promocional con el código
   ↓
4. Cliente agrega productos al carrito
   ↓
5. Subtotal: $80,000
   ↓
6. Cliente va al carrito
   ↓
7. Ve campo "Código de descuento"
   ↓
8. Ingresa: "VERANO25"
   ↓
9. Clic en "Aplicar"
   ↓
10. Sistema valida:
    - ✅ Código existe
    - ✅ Está activo
    - ✅ Dentro de fechas válidas
    - ✅ Cumple mínimo ($80,000 ≥ $50,000)
    - ✅ No ha excedido límite de usos
   ↓
11. Sistema aplica descuento:
    - Subtotal: $80,000
    - Descuento 25%: -$20,000
    - Nuevo subtotal: $60,000
    - IVA 19%: $11,400
    - Envío: $5,000
    - TOTAL: $76,400
   ↓
12. Cliente ve descuento aplicado (verde)
   ↓
13. Continúa al checkout
   ↓
14. Completa compra
   ↓
15. Sistema:
    - Incrementa contador de usos del cupón
    - Asocia cupón con la orden
   ↓
16. Admin ve estadística: "VERANO25 usado 1 vez"
```

---

## Flujo 7: Cliente Rastrea su Pedido (Sin cuenta)

```
1. Cliente recibe email de confirmación
   ↓
2. Email contiene:
    - Número de orden: ORD-20250156
    - Link de rastreo
   ↓
3. Cliente pierde el email
   ↓
4. Cliente va al sitio web
   ↓
5. Footer > "Rastrear Pedido"
   ↓
6. Página de rastreo público
   ↓
7. Cliente ingresa:
    - Número de orden: ORD-20250156
    - Email: cliente@email.com
   ↓
8. Clic en "Rastrear"
   ↓
9. Sistema valida datos
   ↓
10. Muestra información:
    - Estado actual: "En Tránsito"
    - Línea de tiempo:
      ✅ 24/10/2025 - Orden recibida
      ✅ 25/10/2025 - Pago confirmado
      ✅ 25/10/2025 - Orden procesada
      ✅ 26/10/2025 - Despachada (SERV-ABC123)
      🚚 27/10/2025 - En tránsito
      ⏳ 28/10/2025 - Entrega estimada
    - Tracking: SERV-ABC123
    - Transportadora: Servientrega
    - Dirección: [Su dirección]
   ↓
11. Cliente tiene visibilidad completa ✅
```

---

# Preguntas Frecuentes

## Para Clientes

**¿Cómo creo una cuenta?**
- Clic en "Iniciar Sesión" > "Regístrate" > Llenar formulario > Confirmar email.

**¿Puedo comprar sin registrarme?**
- Sí, como "invitado". Pero con cuenta tienes historial y seguimiento.

**¿Cómo aplico un cupón?**
- En el carrito, ingresa el código en "Código de descuento" y clic en "Aplicar".

**¿Puedo cancelar mi pedido?**
- Sí, si está en estado "Pendiente". Ve a "Mis Pedidos" > Cancelar.

**¿Cuánto demora el envío?**
- Bogotá y alrededores: 2-4 días hábiles. Otras ciudades: 5-8 días.

**¿Cómo rastrea mi pedido?**
- Desde "Mis Pedidos" o en Footer > "Rastrear Pedido" con número de orden y email.

**¿Aceptan devoluciones?**
- Sí, 14 días desde la recepción. Ver "Política de Devoluciones".

**¿Qué métodos de pago aceptan?**
- Tarjeta crédito/débito, transferencia, pago contra entrega.

**¿Cómo cambio mi contraseña?**
- "Mi Cuenta" > "Perfil" > "Cambiar contraseña".

**¿Puedo guardar varias direcciones?**
- Sí, en "Mi Cuenta" > "Direcciones Guardadas" > "+ Nueva Dirección".

---

## Para Administradores

**¿Cómo agrego un producto?**
- Admin > Productos > + Nuevo Producto > Llenar formulario > Publicar.

**¿Cómo sé qué órdenes despachar?**
- Admin > Envíos > Control de Despachos > Pestaña "Pendientes de Despacho".

**¿Cómo creo un envío?**
- Desde "Pendientes de Despacho" > Clic en "📦 Crear Envío" > Ingresar tracking y transportadora.

**¿Cómo actualizo el stock?**
- Editar producto > Cambiar "Stock Actual" > Guardar. O clic directo en número desde tabla.

**¿Cómo creo un cupón?**
- Admin > Cupones > + Nuevo Cupón > Configurar > Guardar.

**¿Puedo exportar datos?**
- Sí, botones de "Exportar Excel/PDF" en Dashboard, Productos, Órdenes.

**¿Cómo modero reseñas?**
- Admin > Reseñas > Revisar pendientes > Aprobar/Rechazar.

**¿Cómo cambio el rol de un usuario?**
- (Solo Admin) Admin > Usuarios > Ver usuario > Cambiar rol > Guardar.

**¿Qué hago si un pago falla?**
- Ver orden > Verificar estado de pago > Contactar cliente si es necesario.

**¿Puedo editar una orden ya creada?**
- Solo puedes cambiar el estado, no los productos. Si necesitas modificar, cancela y crea nueva.

---

# Solución de Problemas

## Problemas Comunes - Clientes

### No puedo iniciar sesión

**Posibles causas:**
1. Contraseña incorrecta
   - Solución: Usar "¿Olvidaste tu contraseña?" para resetear

2. Email incorrecto
   - Solución: Verificar email de registro, probar variantes

3. Cuenta no existe
   - Solución: Registrarse primero

4. Cuenta desactivada (por admin)
   - Solución: Contactar soporte

### El cupón no se aplica

**Validar:**
1. ✅ Código escrito correctamente (mayúsculas/minúsculas)
2. ✅ Cupón está activo
3. ✅ Está dentro de fechas válidas
4. ✅ Cumple monto mínimo
5. ✅ No ha excedido límite de usos

**Solución:**
- Revisar condiciones del cupón
- Contactar soporte si persiste

### No recibí email de confirmación

**Revisar:**
1. Carpeta de spam/correo no deseado
2. Email escrito correctamente en orden

**Solución:**
- Esperar 10-15 minutos
- Buscar en spam
- Contactar soporte con número de orden

### Producto sin stock pero quiero comprarlo

**Solución:**
- Agregar a lista de deseos
- Activar notificación de disponibilidad (si existe)
- Contactar soporte para consultar reabastecimiento

### No puedo rastrear mi pedido

**Validar:**
1. Número de orden correcto (formato: ORD-XXXXXXXX)
2. Email correcto

**Solución:**
- Revisar email de confirmación
- Copiar/pegar número de orden
- Verificar email

---

## Problemas Comunes - Administradores

### No puedo acceder al panel de administración

**Validar:**
1. Usuario tiene rol Manager o Admin
2. Sesión iniciada correctamente

**Solución:**
- Verificar rol en base de datos
- Admin debe otorgar permisos si es necesario

### Producto no aparece en el catálogo

**Validar:**
1. Estado = "Publicado" (no Borrador)
2. Stock > 0 (si gestiona stock)
3. Categoría asignada

**Solución:**
- Editar producto > Cambiar estado a Publicado > Guardar
- Verificar stock
- Refrescar catálogo

### No puedo crear envío para una orden

**Validar:**
1. Orden está en estado Pending o Processing
2. Payment Status = Paid
3. No tiene envío creado previamente

**Solución:**
- Verificar estado de pago
- Si ya tiene envío, editar el existente
- Contactar soporte técnico si persiste

### Stock no se descuenta al vender

**Posibles causas:**
1. Producto configurado como "No gestionar stock"

**Solución:**
- Editar producto > Marcar "Gestionar stock" > Guardar
- Ajustar stock manualmente

### No puedo eliminar un producto

**Causa:**
- Producto tiene órdenes asociadas

**Solución:**
- Despublicar en lugar de eliminar
- Cambiar estado a "Borrador"
- O contactar admin para eliminación forzada (no recomendado)

### Imágenes no se cargan

**Validar:**
1. Tamaño < 2MB
2. Formato permitido (JPG, PNG, WebP)
3. Permisos de escritura en storage/

**Solución:**
- Redimensionar imagen
- Convertir a formato válido
- Verificar permisos del servidor

### Reporte no se genera

**Posibles causas:**
1. Rango de fechas inválido
2. Sin datos en el período

**Solución:**
- Verificar fechas (desde < hasta)
- Ampliar rango de fechas
- Verificar que hay datos

---

## Contacto de Soporte

**Para asistencia:**

📧 Email: soporte@petuniaplay.com
📞 Teléfono: +57 305 759 4088
💬 Chat: Disponible en el sitio (horario: Lun-Vie 9am-6pm)

**Antes de contactar:**
1. Revisar esta documentación
2. Verificar sección de Preguntas Frecuentes
3. Intentar soluciones propuestas

**Al contactar, proporcionar:**
- Descripción del problema
- Número de orden (si aplica)
- Capturas de pantalla
- Navegador y dispositivo usado

---

# Glosario

**SKU:** Stock Keeping Unit - Código único del producto

**Slug:** URL amigable del producto/categoría

**Checkout:** Proceso de finalización de compra

**Wishlist:** Lista de deseos

**Guest:** Usuario invitado (sin cuenta)

**Tracking Number:** Número de seguimiento del envío

**Shipment:** Envío/despacho

**Order:** Pedido/Orden

**COD:** Cash On Delivery (Pago contra entrega)

**IVA:** Impuesto al Valor Agregado (19% en Colombia)

**CRUD:** Create, Read, Update, Delete (operaciones básicas)

**API:** Application Programming Interface

**Admin:** Administrador

**Manager:** Gerente/Encargado

**Customer:** Cliente

**Stock:** Inventario disponible

---

# Actualizaciones del Manual

**Versión:** 1.0
**Fecha:** 26 de Octubre 2025
**Autor:** David González

**Próximas actualizaciones incluirán:**
- Integración de pasarela de pagos
- Sistema de notificaciones push
- Multi-idioma
- Programa de puntos/lealtad

---

**¿Tienes sugerencias para mejorar este manual?**
Contacta a: contacto@petuniaplay.com
