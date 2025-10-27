# ✅ Checklist Completa de Pruebas - PetuniaPlay E-commerce

## 📋 Instrucciones de Uso

- [ ] Marcar con `[x]` cuando la prueba pase exitosamente
- [ ] Anotar cualquier bug encontrado con el número de la prueba
- [ ] Probar en navegadores: Chrome, Firefox, Safari, Edge
- [ ] Probar en dispositivos: Desktop, Tablet, Móvil

**Fecha de inicio:** ___________
**Testeador:** ___________
**Navegador principal:** ___________

---

## 🏠 FRONTEND - PÁGINAS PÚBLICAS

### 1. Página de Inicio (HomeView)

- [ ] 1.1 La página carga correctamente
- [ ] 1.2 El logo de Petunia Play se muestra
- [ ] 1.3 La navegación principal funciona (Inicio, Productos, Categorías, Contacto)
- [ ] 1.4 El banner principal se muestra correctamente
- [ ] 1.5 Los productos destacados se muestran (si hay productos)
- [ ] 1.6 Las categorías se muestran con sus imágenes
- [ ] 1.7 El botón de dark mode funciona correctamente
- [ ] 1.8 El footer se muestra con toda la información
- [ ] 1.9 La sección del desarrollador aparece en el footer
- [ ] 1.10 El link al portafolio de GitHub funciona y abre en nueva pestaña
- [ ] 1.11 El diseño es responsive (probar en móvil)
- [ ] 1.12 No hay errores en la consola del navegador

### 2. Catálogo de Productos (ProductsView)

**Carga y Visualización:**
- [ ] 2.1 La página de productos carga correctamente
- [ ] 2.2 Se muestran todos los productos disponibles
- [ ] 2.3 Las imágenes de productos cargan correctamente
- [ ] 2.4 Los precios se muestran correctamente formateados
- [ ] 2.5 Los descuentos se muestran cuando aplica
- [ ] 2.6 El precio tachado aparece cuando hay descuento
- [ ] 2.7 La badge de "Agotado" aparece cuando stock = 0
- [ ] 2.8 La badge de "Destacado" aparece en productos destacados

**Filtros:**
- [ ] 2.9 El filtro por categoría funciona correctamente
- [ ] 2.10 El filtro por marca funciona correctamente
- [ ] 2.11 El filtro por rango de precios funciona correctamente
- [ ] 2.12 El filtro de "Solo en stock" funciona correctamente
- [ ] 2.13 El filtro de "Solo en oferta" funciona correctamente
- [ ] 2.14 Los filtros se pueden combinar correctamente
- [ ] 2.15 El botón "Limpiar filtros" funciona

**Ordenamiento:**
- [ ] 2.16 Ordenar por "Más recientes" funciona
- [ ] 2.17 Ordenar por "Precio: menor a mayor" funciona
- [ ] 2.18 Ordenar por "Precio: mayor a menor" funciona
- [ ] 2.19 Ordenar por "Nombre A-Z" funciona
- [ ] 2.20 Ordenar por "Nombre Z-A" funciona
- [ ] 2.21 Ordenar por "Más vendidos" funciona

**Búsqueda:**
- [ ] 2.22 La barra de búsqueda funciona
- [ ] 2.23 El autocompletado muestra resultados mientras escribes
- [ ] 2.24 Click en un resultado del autocompletado lleva al producto
- [ ] 2.25 La búsqueda filtra correctamente los productos
- [ ] 2.26 Mensaje "No se encontraron productos" aparece cuando no hay resultados

**Paginación:**
- [ ] 2.27 La paginación funciona correctamente
- [ ] 2.28 Los botones de página anterior/siguiente funcionan
- [ ] 2.29 El número de productos por página es correcto

**Wishlist y Carrito:**
- [ ] 2.30 El icono de corazón (wishlist) funciona
- [ ] 2.31 El botón "Agregar al carrito" funciona
- [ ] 2.32 Toast notification aparece al agregar al carrito
- [ ] 2.33 Toast notification aparece al agregar a wishlist
- [ ] 2.34 El contador del carrito se actualiza

### 3. Detalle de Producto (ProductDetailView)

**Información del Producto:**
- [ ] 3.1 La página carga correctamente al click en un producto
- [ ] 3.2 El nombre del producto se muestra
- [ ] 3.3 El SKU se muestra
- [ ] 3.4 El precio se muestra correctamente
- [ ] 3.5 El descuento se muestra si aplica
- [ ] 3.6 La descripción completa se muestra
- [ ] 3.7 Las especificaciones se muestran
- [ ] 3.8 El stock disponible se muestra
- [ ] 3.9 La categoría se muestra y es clickeable
- [ ] 3.10 La marca se muestra

**Galería de Imágenes:**
- [ ] 3.11 La imagen principal se muestra
- [ ] 3.12 Las miniaturas se muestran si hay múltiples imágenes
- [ ] 3.13 Click en miniatura cambia la imagen principal
- [ ] 3.14 La galería es responsive

**Acciones:**
- [ ] 3.15 Selector de cantidad funciona
- [ ] 3.16 No se puede seleccionar más cantidad que el stock disponible
- [ ] 3.17 Botón "Agregar al carrito" funciona
- [ ] 3.18 Botón "Agregar a favoritos" funciona
- [ ] 3.19 Botón deshabilitado cuando stock = 0
- [ ] 3.20 Toast notification al agregar al carrito
- [ ] 3.21 Contador del carrito se actualiza

**Reseñas:**
- [ ] 3.22 La sección de reseñas se muestra
- [ ] 3.23 Las reseñas existentes se muestran (si hay)
- [ ] 3.24 La calificación promedio se muestra con estrellas
- [ ] 3.25 El número de reseñas se muestra
- [ ] 3.26 El formulario para dejar reseña aparece (si está autenticado)
- [ ] 3.27 Se puede seleccionar calificación de 1-5 estrellas
- [ ] 3.28 Se puede escribir un comentario
- [ ] 3.29 Botón "Publicar reseña" funciona
- [ ] 3.30 Mensaje de éxito al publicar reseña
- [ ] 3.31 Mensaje "Inicia sesión para dejar una reseña" si no está autenticado

**Productos Relacionados:**
- [ ] 3.32 Sección de productos relacionados se muestra
- [ ] 3.33 Se muestran productos de la misma categoría
- [ ] 3.34 Links a productos relacionados funcionan

### 4. Categorías (CategoryView)

- [ ] 4.1 La página de categoría carga correctamente
- [ ] 4.2 El nombre de la categoría se muestra
- [ ] 4.3 La descripción de la categoría se muestra
- [ ] 4.4 Los productos de la categoría se filtran correctamente
- [ ] 4.5 Los filtros funcionan igual que en productos
- [ ] 4.6 La navegación breadcrumb funciona

### 5. Carrito de Compras (CartView)

**Visualización:**
- [ ] 5.1 La página del carrito carga correctamente
- [ ] 5.2 Los productos en el carrito se muestran
- [ ] 5.3 Las imágenes de productos se muestran
- [ ] 5.4 Los nombres de productos se muestran
- [ ] 5.5 Los precios unitarios se muestran
- [ ] 5.6 Las cantidades se muestran
- [ ] 5.7 Los subtotales por producto se muestran

**Cálculos:**
- [ ] 5.8 El subtotal se calcula correctamente
- [ ] 5.9 El IVA (19%) se calcula correctamente
- [ ] 5.10 El costo de envío se muestra
- [ ] 5.11 El total se calcula correctamente
- [ ] 5.12 Los totales se actualizan al cambiar cantidades

**Acciones:**
- [ ] 5.13 El selector de cantidad funciona para cada producto
- [ ] 5.14 No se puede seleccionar más que el stock disponible
- [ ] 5.15 Botón "Actualizar" actualiza la cantidad
- [ ] 5.16 Botón "Eliminar" quita el producto del carrito
- [ ] 5.17 Confirmación antes de eliminar
- [ ] 5.18 Botón "Vaciar carrito" funciona
- [ ] 5.19 Confirmación antes de vaciar carrito
- [ ] 5.20 Contador del carrito se actualiza con cada cambio

**Cupones:**
- [ ] 5.21 El campo de cupón se muestra
- [ ] 5.22 Se puede ingresar un código de cupón
- [ ] 5.23 Botón "Aplicar" valida el cupón
- [ ] 5.24 Mensaje de éxito cuando el cupón es válido
- [ ] 5.25 Mensaje de error cuando el cupón es inválido
- [ ] 5.26 El descuento se aplica correctamente
- [ ] 5.27 El total se recalcula con el descuento
- [ ] 5.28 Botón "Quitar" elimina el cupón
- [ ] 5.29 Badge verde muestra el cupón aplicado

**Navegación:**
- [ ] 5.30 Botón "Continuar comprando" lleva a productos
- [ ] 5.31 Botón "Proceder al pago" lleva al checkout
- [ ] 5.32 Mensaje "Tu carrito está vacío" cuando no hay productos
- [ ] 5.33 Botón "Explorar productos" cuando carrito vacío

### 6. Checkout (CheckoutView)

**Acceso:**
- [ ] 6.1 La página carga correctamente
- [ ] 6.2 Redirección a productos si el carrito está vacío
- [ ] 6.3 Los productos del carrito se muestran en el resumen

**Información Personal:**
- [ ] 6.4 Campo "Nombre completo" funciona
- [ ] 6.5 Campo "Email" funciona
- [ ] 6.6 Campo "Teléfono" funciona
- [ ] 6.7 Campo "Documento de identidad" funciona
- [ ] 6.8 Validación de campos obligatorios funciona
- [ ] 6.9 Validación de formato de email funciona

**Dirección de Envío:**

_Modo Manual:_
- [ ] 6.10 Tab "Escribir Dirección" funciona
- [ ] 6.11 Campo "Dirección completa" funciona
- [ ] 6.12 Campo "Ciudad" funciona
- [ ] 6.13 Campo "Departamento" funciona
- [ ] 6.14 Campo "Código postal" funciona

_Modo Mapa:_
- [ ] 6.15 Tab "Seleccionar en Mapa" funciona
- [ ] 6.16 El mapa de Google Maps carga correctamente
- [ ] 6.17 El marcador rojo se muestra en Bogotá (por defecto)
- [ ] 6.18 Se puede arrastrar el marcador
- [ ] 6.19 Se puede hacer click en el mapa para mover el marcador
- [ ] 6.20 La geolocalización automática funciona (si se permite)
- [ ] 6.21 La geocodificación inversa funciona (coordenadas → dirección)
- [ ] 6.22 La dirección obtenida se muestra en el panel verde
- [ ] 6.23 Las coordenadas se muestran
- [ ] 6.24 Validación de área de entrega funciona (50km Bogotá)
- [ ] 6.25 Alerta naranja aparece si está fuera del área
- [ ] 6.26 Loading spinner se muestra mientras geocodifica

**Notas de Entrega:**
- [ ] 6.27 Campo de notas funciona
- [ ] 6.28 Se pueden escribir instrucciones especiales

**Método de Pago:**
- [ ] 6.29 Radio button "Tarjeta de crédito/débito" funciona
- [ ] 6.30 Radio button "PSE" funciona
- [ ] 6.31 Radio button "Contra entrega" funciona
- [ ] 6.32 Solo se muestra un método seleccionado a la vez

_Tarjeta de Crédito:_
- [ ] 6.33 Campos de tarjeta aparecen al seleccionar
- [ ] 6.34 Campo "Número de tarjeta" funciona
- [ ] 6.35 Formato automático del número (espacios cada 4 dígitos)
- [ ] 6.36 Campo "Vencimiento" funciona
- [ ] 6.37 Formato automático MM/AA
- [ ] 6.38 Campo "CVV" funciona
- [ ] 6.39 Máximo 4 dígitos en CVV
- [ ] 6.40 Campo "Nombre en la tarjeta" funciona

**Resumen del Pedido:**
- [ ] 6.41 Los productos se listan correctamente
- [ ] 6.42 Las imágenes de productos se muestran
- [ ] 6.43 Las cantidades son correctas
- [ ] 6.44 Los precios son correctos
- [ ] 6.45 El subtotal es correcto
- [ ] 6.46 El IVA (19%) es correcto
- [ ] 6.47 El envío se muestra
- [ ] 6.48 El descuento del cupón se muestra (si aplica)
- [ ] 6.49 El total es correcto

**Términos y Condiciones:**
- [ ] 6.50 Checkbox de términos y condiciones se muestra
- [ ] 6.51 Links a términos y privacidad funcionan
- [ ] 6.52 No se puede proceder sin aceptar términos

**Finalizar Compra:**
- [ ] 6.53 Botón "Realizar pedido" está habilitado cuando todo está completo
- [ ] 6.54 Botón deshabilitado si faltan datos
- [ ] 6.55 Validación completa antes de enviar
- [ ] 6.56 Mensajes de error específicos si falta información
- [ ] 6.57 Loading state durante el procesamiento
- [ ] 6.58 Orden se crea correctamente en la base de datos
- [ ] 6.59 Stock se reduce correctamente
- [ ] 6.60 Carrito se limpia después de la orden
- [ ] 6.61 Mensaje de éxito se muestra
- [ ] 6.62 Número de orden se muestra en el mensaje
- [ ] 6.63 Redirección a página principal después del éxito

### 7. Wishlist (WishlistView)

- [ ] 7.1 La página requiere autenticación
- [ ] 7.2 La página carga correctamente
- [ ] 7.3 Los productos en wishlist se muestran
- [ ] 7.4 Las imágenes se muestran
- [ ] 7.5 Los precios se muestran
- [ ] 7.6 Botón "Eliminar" quita productos de wishlist
- [ ] 7.7 Botón "Agregar al carrito" funciona
- [ ] 7.8 Mensaje cuando wishlist está vacía
- [ ] 7.9 Botón "Explorar productos" cuando vacía

### 8. Tracking de Pedidos (TrackingView)

- [ ] 8.1 La página carga correctamente
- [ ] 8.2 Campo de número de tracking se muestra
- [ ] 8.3 Se puede ingresar un tracking number
- [ ] 8.4 Botón "Rastrear" funciona
- [ ] 8.5 Loading state durante la búsqueda
- [ ] 8.6 Información del envío se muestra si existe
- [ ] 8.7 Estado del envío se muestra con color
- [ ] 8.8 Transportadora se muestra
- [ ] 8.9 Fechas se muestran correctamente
- [ ] 8.10 Dirección de envío se muestra
- [ ] 8.11 Mensaje de error si tracking no existe

### 9. Contacto (ContactView)

- [ ] 9.1 La página carga correctamente
- [ ] 9.2 Campo "Nombre" funciona
- [ ] 9.3 Campo "Email" funciona
- [ ] 9.4 Campo "Asunto" funciona
- [ ] 9.5 Campo "Mensaje" funciona
- [ ] 9.6 Validación de campos obligatorios
- [ ] 9.7 Validación de formato de email
- [ ] 9.8 Botón "Enviar" funciona
- [ ] 9.9 Loading state durante envío
- [ ] 9.10 Mensaje de éxito al enviar
- [ ] 9.11 Formulario se limpia después de enviar
- [ ] 9.12 Información de contacto se muestra (email, teléfono, dirección)

### 10. Páginas de Contenido

**About (Sobre Nosotros):**
- [ ] 10.1 La página carga correctamente
- [ ] 10.2 El contenido se muestra
- [ ] 10.3 No hay errores en la consola

**FAQ (Preguntas Frecuentes):**
- [ ] 10.4 La página carga correctamente
- [ ] 10.5 Las preguntas se muestran
- [ ] 10.6 El acordeón funciona (expandir/colapsar)
- [ ] 10.7 Solo una pregunta abierta a la vez

**Términos y Condiciones:**
- [ ] 10.8 La página carga correctamente
- [ ] 10.9 El contenido legal se muestra

**Política de Privacidad:**
- [ ] 10.10 La página carga correctamente
- [ ] 10.11 El contenido legal se muestra
- [ ] 10.12 Mención de Ley 1581 de 2012

**Política de Devoluciones:**
- [ ] 10.13 La página carga correctamente
- [ ] 10.14 El contenido se muestra

**Política de Envíos:**
- [ ] 10.15 La página carga correctamente
- [ ] 10.16 El contenido se muestra

---

## 🔐 AUTENTICACIÓN Y CUENTA

### 11. Registro (RegisterView)

- [ ] 11.1 La página carga correctamente
- [ ] 11.2 Campo "Nombre completo" funciona
- [ ] 11.3 Campo "Email" funciona
- [ ] 11.4 Campo "Teléfono" funciona
- [ ] 11.5 Campo "Contraseña" funciona
- [ ] 11.6 Campo "Confirmar contraseña" funciona
- [ ] 11.7 Validación: todos los campos obligatorios
- [ ] 11.8 Validación: formato de email
- [ ] 11.9 Validación: contraseña mínimo 8 caracteres
- [ ] 11.10 Validación: contraseñas coinciden
- [ ] 11.11 Icono de ojo muestra/oculta contraseña
- [ ] 11.12 Botón "Registrarse" funciona
- [ ] 11.13 Loading state durante registro
- [ ] 11.14 Error si el email ya existe
- [ ] 11.15 Usuario se crea en la base de datos
- [ ] 11.16 Token de autenticación se genera
- [ ] 11.17 Usuario queda autenticado automáticamente
- [ ] 11.18 Redirección a home después del registro
- [ ] 11.19 Link "¿Ya tienes cuenta? Inicia sesión" funciona

### 12. Login (LoginView)

- [ ] 12.1 La página carga correctamente
- [ ] 12.2 Campo "Email" funciona
- [ ] 12.3 Campo "Contraseña" funciona
- [ ] 12.4 Checkbox "Recordarme" funciona
- [ ] 12.5 Icono de ojo muestra/oculta contraseña
- [ ] 12.6 Validación de campos obligatorios
- [ ] 12.7 Botón "Iniciar sesión" funciona
- [ ] 12.8 Loading state durante login
- [ ] 12.9 Error si credenciales incorrectas
- [ ] 12.10 Token se guarda en localStorage
- [ ] 12.11 Usuario queda autenticado
- [ ] 12.12 Header se actualiza (muestra nombre del usuario)
- [ ] 12.13 Redirección a account después del login
- [ ] 12.14 Link "¿Olvidaste tu contraseña?" funciona
- [ ] 12.15 Link "¿No tienes cuenta? Regístrate" funciona

### 13. Recuperación de Contraseña (ForgotPasswordView)

- [ ] 13.1 La página carga correctamente
- [ ] 13.2 Campo "Email" funciona
- [ ] 13.3 Validación de formato de email
- [ ] 13.4 Botón "Enviar enlace" funciona
- [ ] 13.5 Loading state durante envío
- [ ] 13.6 Mensaje de éxito al enviar
- [ ] 13.7 Email se envía correctamente (verificar en logs)
- [ ] 13.8 Link de reset incluido en el email
- [ ] 13.9 Link "Volver al login" funciona

### 14. Reset de Contraseña (ResetPasswordView)

- [ ] 14.1 La página carga con el token de la URL
- [ ] 14.2 Campo "Nueva contraseña" funciona
- [ ] 14.3 Campo "Confirmar contraseña" funciona
- [ ] 14.4 Validación: mínimo 8 caracteres
- [ ] 14.5 Validación: contraseñas coinciden
- [ ] 14.6 Icono de ojo funciona
- [ ] 14.7 Botón "Restablecer contraseña" funciona
- [ ] 14.8 Loading state durante proceso
- [ ] 14.9 Contraseña se actualiza en la base de datos
- [ ] 14.10 Mensaje de éxito
- [ ] 14.11 Redirección a login después del éxito
- [ ] 14.12 Error si el token es inválido o expirado

### 15. Mi Cuenta (AccountView)

**Acceso:**
- [ ] 15.1 Requiere autenticación
- [ ] 15.2 Redirección a login si no autenticado
- [ ] 15.3 La página carga correctamente

**Perfil:**
- [ ] 15.4 Tab "Perfil" funciona
- [ ] 15.5 Datos del usuario se cargan
- [ ] 15.6 Campo "Nombre" se puede editar
- [ ] 15.7 Campo "Email" se puede editar
- [ ] 15.8 Campo "Teléfono" se puede editar
- [ ] 15.9 Botón "Guardar cambios" funciona
- [ ] 15.10 Datos se actualizan en la base de datos
- [ ] 15.11 Mensaje de éxito al actualizar
- [ ] 15.12 Checkbox de notificaciones por email funciona
- [ ] 15.13 Preferencia se guarda correctamente

**Cambiar Contraseña:**
- [ ] 15.14 Tab "Cambiar contraseña" funciona (si existe)
- [ ] 15.15 Campo "Contraseña actual" funciona
- [ ] 15.16 Campo "Nueva contraseña" funciona
- [ ] 15.17 Campo "Confirmar nueva contraseña" funciona
- [ ] 15.18 Validación de contraseña actual
- [ ] 15.19 Validación de mínimo 8 caracteres
- [ ] 15.20 Validación de coincidencia
- [ ] 15.21 Botón "Cambiar" funciona
- [ ] 15.22 Contraseña se actualiza en la base de datos

**Mis Pedidos:**
- [ ] 15.23 Tab "Mis pedidos" funciona
- [ ] 15.24 Lista de pedidos se muestra
- [ ] 15.25 Número de orden se muestra
- [ ] 15.26 Fecha de cada pedido se muestra
- [ ] 15.27 Total de cada pedido se muestra
- [ ] 15.28 Estado de cada pedido se muestra con color
- [ ] 15.29 Link para ver detalles funciona
- [ ] 15.30 Detalles muestran productos comprados
- [ ] 15.31 Dirección de envío se muestra
- [ ] 15.32 Estado de pago se muestra
- [ ] 15.33 Información de tracking se muestra (si existe)
- [ ] 15.34 Mensaje cuando no hay pedidos

**Direcciones:**
- [ ] 15.35 Tab "Direcciones" funciona
- [ ] 15.36 Lista de direcciones guardadas se muestra
- [ ] 15.37 Botón "Agregar dirección" funciona
- [ ] 15.38 Modal de nueva dirección se abre
- [ ] 15.39 Todos los campos del formulario funcionan
- [ ] 15.40 Se puede marcar como predeterminada
- [ ] 15.41 Botón "Guardar" crea la dirección
- [ ] 15.42 Dirección se guarda en la base de datos
- [ ] 15.43 Botón "Editar" funciona para cada dirección
- [ ] 15.44 Botón "Eliminar" funciona
- [ ] 15.45 Confirmación antes de eliminar
- [ ] 15.46 Badge "Predeterminada" se muestra

**Cerrar Sesión:**
- [ ] 15.47 Botón "Cerrar sesión" funciona
- [ ] 15.48 Token se elimina de localStorage
- [ ] 15.49 Usuario se desautentica
- [ ] 15.50 Redirección a home
- [ ] 15.51 Header se actualiza (muestra "Iniciar sesión")

### 16. Unsubscribe (UnsubscribeView)

- [ ] 16.1 La página carga con el token de la URL
- [ ] 16.2 Loading spinner se muestra inicialmente
- [ ] 16.3 Llamada a API se hace automáticamente
- [ ] 16.4 Estado de éxito se muestra si el token es válido
- [ ] 16.5 Mensaje de confirmación se muestra
- [ ] 16.6 Información legal (Ley 1581) se muestra
- [ ] 16.7 Botón "Volver al inicio" funciona
- [ ] 16.8 Botón "Ir a mi cuenta" funciona
- [ ] 16.9 Estado de error se muestra si el token es inválido
- [ ] 16.10 Mensaje de error apropiado
- [ ] 16.11 Botón "Contactar soporte" funciona
- [ ] 16.12 Campo email_notifications se actualiza en DB

---

## 👨‍💼 PANEL DE ADMINISTRACIÓN

### 17. Acceso al Admin

- [ ] 17.1 Solo usuarios con rol manager/admin pueden acceder
- [ ] 17.2 Redirección a home si no tiene permisos
- [ ] 17.3 Link "Admin" aparece en header para managers/admins
- [ ] 17.4 Link no aparece para usuarios customer

### 18. Dashboard Admin (AdminDashboardView)

**Estadísticas Principales:**
- [ ] 18.1 La página carga correctamente
- [ ] 18.2 Total de ventas se muestra
- [ ] 18.3 Número de pedidos se muestra
- [ ] 18.4 Número de productos se muestra
- [ ] 18.5 Número de usuarios se muestra
- [ ] 18.6 Las estadísticas son correctas

**Ventas Recientes:**
- [ ] 18.7 Tabla de ventas recientes se muestra
- [ ] 18.8 Las 10 ventas más recientes aparecen
- [ ] 18.9 Número de orden se muestra
- [ ] 18.10 Cliente se muestra
- [ ] 18.11 Total se muestra
- [ ] 18.12 Estado se muestra con color

**Productos Destacados:**
- [ ] 18.13 Sección de stock bajo se muestra
- [ ] 18.14 Productos con stock <= threshold aparecen
- [ ] 18.15 Stock actual se muestra en rojo
- [ ] 18.16 Sección de sin stock se muestra
- [ ] 18.17 Productos con stock = 0 aparecen

**Reportes:**
- [ ] 18.18 Sección de exportar reportes se muestra
- [ ] 18.19 Selector de fechas funciona
- [ ] 18.20 Selector de agrupación (día/semana/mes) funciona
- [ ] 18.21 Botón "Exportar a Excel" funciona
- [ ] 18.22 Archivo Excel se descarga correctamente
- [ ] 18.23 Datos en Excel son correctos
- [ ] 18.24 Formato de Excel es apropiado

### 19. Gestión de Productos (AdminProductsView)

**Lista de Productos:**
- [ ] 19.1 La página carga correctamente
- [ ] 19.2 Tabla de productos se muestra
- [ ] 19.3 Imagen, nombre, SKU, precio, stock, estado se muestran
- [ ] 19.4 Paginación funciona
- [ ] 19.5 Botón "Nuevo producto" se muestra

**Filtros:**
- [ ] 19.6 Filtro por categoría funciona
- [ ] 19.7 Filtro por marca funciona
- [ ] 19.8 Filtro "Solo stock bajo" funciona
- [ ] 19.9 Búsqueda por nombre/SKU funciona

**Exportar:**
- [ ] 19.10 Botón "Exportar a Excel" funciona
- [ ] 19.11 Archivo se descarga con los filtros aplicados
- [ ] 19.12 Datos son correctos

**Crear Producto:**
- [ ] 19.13 Click en "Nuevo producto" abre modal
- [ ] 19.14 Todos los campos del formulario funcionan:
  - [ ] 19.14.1 Nombre
  - [ ] 19.14.2 SKU
  - [ ] 19.14.3 Descripción
  - [ ] 19.14.4 Categoría (dropdown)
  - [ ] 19.14.5 Marca
  - [ ] 19.14.6 Precio
  - [ ] 19.14.7 Precio de descuento (opcional)
  - [ ] 19.14.8 Stock
  - [ ] 19.14.9 Umbral de stock bajo
  - [ ] 19.14.10 Peso (opcional)
  - [ ] 19.14.11 Dimensiones (opcional)
  - [ ] 19.14.12 Destacado (checkbox)
  - [ ] 19.14.13 Activo (checkbox)
- [ ] 19.15 Subida de imágenes funciona
- [ ] 19.16 Se pueden subir múltiples imágenes
- [ ] 19.17 Preview de imágenes se muestra
- [ ] 19.18 Se puede eliminar una imagen
- [ ] 19.19 Se puede marcar una imagen como principal
- [ ] 19.20 Validación de campos obligatorios
- [ ] 19.21 Botón "Guardar" crea el producto
- [ ] 19.22 Producto se guarda en la base de datos
- [ ] 19.23 Slug se genera automáticamente
- [ ] 19.24 Mensaje de éxito se muestra
- [ ] 19.25 Modal se cierra
- [ ] 19.26 Tabla se actualiza con el nuevo producto

**Editar Producto:**
- [ ] 19.27 Botón "Editar" abre modal con datos
- [ ] 19.28 Todos los campos se llenan correctamente
- [ ] 19.29 Imágenes existentes se muestran
- [ ] 19.30 Se pueden modificar todos los campos
- [ ] 19.31 Se pueden agregar más imágenes
- [ ] 19.32 Se pueden eliminar imágenes existentes
- [ ] 19.33 Botón "Guardar" actualiza el producto
- [ ] 19.34 Cambios se guardan en la base de datos
- [ ] 19.35 Mensaje de éxito se muestra

**Eliminar Producto:**
- [ ] 19.36 Botón "Eliminar" funciona
- [ ] 19.37 Confirmación antes de eliminar
- [ ] 19.38 Producto se elimina de la base de datos
- [ ] 19.39 Imágenes se eliminan del storage
- [ ] 19.40 Mensaje de éxito se muestra
- [ ] 19.41 Tabla se actualiza

### 20. Gestión de Categorías (AdminCategoriesView)

**Lista:**
- [ ] 20.1 La página carga correctamente
- [ ] 20.2 Tabla de categorías se muestra
- [ ] 20.3 Imagen, nombre, slug, número de productos se muestran
- [ ] 20.4 Botón "Nueva categoría" se muestra

**Crear Categoría:**
- [ ] 20.5 Modal de nueva categoría se abre
- [ ] 20.6 Campo "Nombre" funciona
- [ ] 20.7 Campo "Descripción" funciona
- [ ] 20.8 Subida de imagen funciona
- [ ] 20.9 Preview de imagen se muestra
- [ ] 20.10 Checkbox "Activo" funciona
- [ ] 20.11 Botón "Guardar" crea la categoría
- [ ] 20.12 Categoría se guarda en la base de datos
- [ ] 20.13 Slug se genera automáticamente
- [ ] 20.14 Mensaje de éxito

**Editar Categoría:**
- [ ] 20.15 Botón "Editar" abre modal
- [ ] 20.16 Datos se cargan correctamente
- [ ] 20.17 Se pueden modificar los campos
- [ ] 20.18 Se puede cambiar la imagen
- [ ] 20.19 Cambios se guardan correctamente
- [ ] 20.20 Mensaje de éxito

**Eliminar Categoría:**
- [ ] 20.21 Botón "Eliminar" funciona
- [ ] 20.22 Confirmación se muestra
- [ ] 20.23 Categoría se elimina
- [ ] 20.24 Productos de esa categoría quedan sin categoría
- [ ] 20.25 Mensaje de éxito

### 21. Gestión de Órdenes (AdminOrdersView)

**Lista de Órdenes:**
- [ ] 21.1 La página carga correctamente
- [ ] 21.2 Tabla de órdenes se muestra
- [ ] 21.3 Número de orden, cliente, fecha, total, estado se muestran
- [ ] 21.4 Estados tienen colores apropiados
- [ ] 21.5 Paginación funciona

**Filtros:**
- [ ] 21.6 Filtro por estado funciona
- [ ] 21.7 Filtro por fecha funciona
- [ ] 21.8 Filtro por estado de pago funciona
- [ ] 21.9 Búsqueda por número de orden funciona
- [ ] 21.10 Botón "Limpiar filtros" funciona

**Exportar:**
- [ ] 21.11 Botón "Exportar a Excel" funciona
- [ ] 21.12 Botón "Exportar a PDF" funciona
- [ ] 21.13 Archivos se descargan con filtros aplicados
- [ ] 21.14 Datos son correctos en ambos formatos

**Ver Detalles:**
- [ ] 21.15 Click en una orden abre modal de detalles
- [ ] 21.16 Número de orden se muestra
- [ ] 21.17 Información del cliente se muestra
- [ ] 21.18 Dirección de envío se muestra
- [ ] 21.19 Lista de productos se muestra con cantidades y precios
- [ ] 21.20 Subtotal, IVA, envío, descuento, total se muestran
- [ ] 21.21 Estado de orden y pago se muestran
- [ ] 21.22 Información de envío se muestra (si existe)
- [ ] 21.23 Notas del cliente se muestran (si existen)

**Actualizar Estado:**
- [ ] 21.24 Dropdown de estado funciona
- [ ] 21.25 Se puede cambiar a: pending, processing, shipped, delivered, cancelled
- [ ] 21.26 Confirmación antes de cambiar
- [ ] 21.27 Estado se actualiza en la base de datos
- [ ] 21.28 Mensaje de éxito

**Crear Envío:**
- [ ] 21.29 Botón "Crear envío" aparece si no hay envío
- [ ] 21.30 Click abre modal de crear envío
- [ ] 21.31 Información de la orden se muestra
- [ ] 21.32 Campo "Tracking number" funciona
- [ ] 21.33 Selector de transportadora funciona
- [ ] 21.34 Campo de notas funciona
- [ ] 21.35 Botón "Crear" guarda el envío
- [ ] 21.36 Envío se crea en la base de datos
- [ ] 21.37 Estado de orden cambia a "processing"
- [ ] 21.38 Email se envía al cliente (verificar logs)
- [ ] 21.39 Mensaje de éxito

### 22. Gestión de Envíos (AdminShipmentsView)

**Estadísticas:**
- [ ] 22.1 La página carga correctamente
- [ ] 22.2 5 tarjetas de estadísticas se muestran:
  - [ ] 22.2.1 Por despachar (naranja)
  - [ ] 22.2.2 Listas (amarillo)
  - [ ] 22.2.3 En tránsito (azul)
  - [ ] 22.2.4 Entregados (verde)
  - [ ] 22.2.5 Total despachados (morado)
- [ ] 22.3 Los números son correctos

**Alertas de Órdenes Urgentes:**
- [ ] 22.4 Banner rojo aparece si hay órdenes urgentes (3+ días)
- [ ] 22.5 Las 5 órdenes más antiguas se listan
- [ ] 22.6 Número de orden, cliente, total se muestran
- [ ] 22.7 Días de espera se muestran
- [ ] 22.8 Botón "Crear envío" funciona para cada orden
- [ ] 22.9 Banner no aparece si no hay órdenes urgentes

**Tabs:**
- [ ] 22.10 Tab "Pendientes de Despacho" funciona
- [ ] 22.11 Tab "Ya Despachadas" funciona
- [ ] 22.12 Tab "Todos los Envíos" funciona
- [ ] 22.13 Contador en cada tab es correcto

**Pendientes de Despacho (Tab 1):**
- [ ] 22.14 Tabla muestra órdenes SIN envío
- [ ] 22.15 Número de orden, cliente, total, dirección, fecha, días se muestran
- [ ] 22.16 Días de espera tienen colores:
  - [ ] 22.16.1 Verde: 0-1 días
  - [ ] 22.16.2 Amarillo: 2 días
  - [ ] 22.16.3 Rojo: 3+ días
- [ ] 22.17 Botón "Crear envío" funciona
- [ ] 22.18 Modal se abre con datos de la orden
- [ ] 22.19 Mensaje celebratorio si no hay pendientes

**Ya Despachadas (Tab 2):**
- [ ] 22.20 Tabla muestra órdenes CON envío
- [ ] 22.21 Tracking, transportadora, estado se muestran
- [ ] 22.22 Se puede ver detalles del envío
- [ ] 22.23 Se puede editar el envío
- [ ] 22.24 Estados tienen colores apropiados

**Todos los Envíos (Tab 3):**
- [ ] 22.25 Filtros se muestran (búsqueda, estado, transportadora)
- [ ] 22.26 Filtro por tracking/orden funciona
- [ ] 22.27 Filtro por estado funciona
- [ ] 22.28 Filtro por transportadora funciona
- [ ] 22.29 Tabla completa de envíos se muestra
- [ ] 22.30 Paginación funciona

**Crear Envío (Modal):**
- [ ] 22.31 Modal se abre correctamente
- [ ] 22.32 Título "Crear Envío" se muestra
- [ ] 22.33 Información del pedido se muestra (orden, cliente, email, total)
- [ ] 22.34 Dirección de envío se muestra
- [ ] 22.35 Campo "Tracking number" funciona (requerido)
- [ ] 22.36 Selector de transportadora funciona (requerido)
- [ ] 22.37 Selector de estado funciona
- [ ] 22.38 Campo de notas funciona (opcional)
- [ ] 22.39 Placeholder se muestra en cada campo
- [ ] 22.40 Validación de campos requeridos
- [ ] 22.41 Botón "Crear Envío" funciona
- [ ] 22.42 Loading state durante creación
- [ ] 22.43 Envío se crea en la base de datos
- [ ] 22.44 Orden desaparece de "Pendientes"
- [ ] 22.45 Orden aparece en "Ya Despachadas"
- [ ] 22.46 Estadísticas se actualizan
- [ ] 22.47 Email se envía al cliente (verificar logs)
- [ ] 22.48 Mensaje de éxito
- [ ] 22.49 Modal se cierra
- [ ] 22.50 Botón "Cancelar" cierra el modal sin guardar

**Editar Envío:**
- [ ] 22.51 Botón "Editar" abre modal
- [ ] 22.52 Título "Editar Envío" se muestra
- [ ] 22.53 Datos actuales se cargan en el formulario
- [ ] 22.54 Se puede cambiar tracking number
- [ ] 22.55 Se puede cambiar transportadora
- [ ] 22.56 Se puede cambiar estado
- [ ] 22.57 Se pueden agregar/editar notas
- [ ] 22.58 Botón "Guardar Cambios" funciona
- [ ] 22.59 Cambios se guardan en la base de datos
- [ ] 22.60 Si cambia a "in_transit": se marca shipped_at
- [ ] 22.61 Si cambia a "delivered": se marca delivered_at
- [ ] 22.62 Si cambia a "delivered": orden cambia a "delivered"
- [ ] 22.63 Email se envía si cambia a in_transit o delivered
- [ ] 22.64 Estadísticas se actualizan
- [ ] 22.65 Mensaje de éxito

**Ver Detalles:**
- [ ] 22.66 Botón "Ver" abre modal en modo lectura
- [ ] 22.67 Título "Detalles del Envío" se muestra
- [ ] 22.68 Información completa se muestra
- [ ] 22.69 No se pueden editar campos
- [ ] 22.70 Botón "Cerrar" funciona

**Eliminar Envío:**
- [ ] 22.71 Botón "Eliminar" solo aparece si NO ha sido enviado
- [ ] 22.72 Confirmación antes de eliminar
- [ ] 22.73 Envío se elimina de la base de datos
- [ ] 22.74 Mensaje de éxito
- [ ] 22.75 Tabla se actualiza
- [ ] 22.76 Mensaje de error si ya fue despachado

### 23. Gestión de Cupones (AdminCouponsView)

**Lista:**
- [ ] 23.1 La página carga correctamente
- [ ] 23.2 Tabla de cupones se muestra
- [ ] 23.3 Código, tipo, descuento, fechas, usos se muestran
- [ ] 23.4 Estado activo/inactivo se muestra
- [ ] 23.5 Botón "Nuevo cupón" se muestra

**Estadísticas:**
- [ ] 23.6 Sección de estadísticas se muestra
- [ ] 23.7 Total de cupones
- [ ] 23.8 Cupones activos
- [ ] 23.9 Usos totales
- [ ] 23.10 Descuento total otorgado

**Crear Cupón:**
- [ ] 23.11 Modal se abre
- [ ] 23.12 Campo "Código" funciona
- [ ] 23.13 Botón "Generar código" crea código aleatorio
- [ ] 23.14 Selector de tipo (porcentaje/fijo) funciona
- [ ] 23.15 Campo "Valor" funciona
- [ ] 23.16 Campo "Descripción" funciona
- [ ] 23.17 Campo "Fecha inicio" funciona (date picker)
- [ ] 23.18 Campo "Fecha fin" funciona (date picker)
- [ ] 23.19 Campo "Monto mínimo" funciona (opcional)
- [ ] 23.20 Campo "Límite de usos" funciona (opcional)
- [ ] 23.21 Checkbox "Activo" funciona
- [ ] 23.22 Validación de campos
- [ ] 23.23 Botón "Guardar" crea el cupón
- [ ] 23.24 Cupón se guarda con código en MAYÚSCULAS
- [ ] 23.25 Mensaje de éxito

**Editar Cupón:**
- [ ] 23.26 Botón "Editar" abre modal
- [ ] 23.27 Datos se cargan
- [ ] 23.28 Se pueden modificar los campos
- [ ] 23.29 Cambios se guardan
- [ ] 23.30 Mensaje de éxito

**Activar/Desactivar:**
- [ ] 23.31 Toggle de activo/inactivo funciona
- [ ] 23.32 Estado se actualiza sin recargar
- [ ] 23.33 Cambio se guarda en la base de datos

**Eliminar Cupón:**
- [ ] 23.34 Botón "Eliminar" funciona
- [ ] 23.35 Confirmación se muestra
- [ ] 23.36 Cupón se elimina
- [ ] 23.37 Mensaje de éxito

### 24. Gestión de Reseñas (Admin)

- [ ] 24.1 La página carga correctamente
- [ ] 24.2 Tabla de reseñas se muestra
- [ ] 24.3 Producto, usuario, calificación, comentario, fecha se muestran
- [ ] 24.4 Estado aprobado/pendiente se muestra
- [ ] 24.5 Botón "Aprobar/Desaprobar" funciona
- [ ] 24.6 Estado cambia en la base de datos
- [ ] 24.7 Solo reseñas aprobadas se muestran en el frontend
- [ ] 24.8 Botón "Eliminar" funciona
- [ ] 24.9 Confirmación antes de eliminar
- [ ] 24.10 Reseña se elimina de la base de datos

### 25. Gestión de Usuarios (Solo Admin)

**Acceso:**
- [ ] 25.1 Solo usuarios con rol "admin" pueden acceder
- [ ] 25.2 Managers NO tienen acceso
- [ ] 25.3 Redirección si no tiene permisos

**Lista de Usuarios:**
- [ ] 25.4 La página carga correctamente
- [ ] 25.5 Tabla de usuarios se muestra
- [ ] 25.6 Nombre, email, rol, fecha de registro se muestran
- [ ] 25.7 Estado activo/inactivo se muestra
- [ ] 25.8 Paginación funciona

**Cambiar Rol:**
- [ ] 25.9 Dropdown de rol funciona
- [ ] 25.10 Opciones: customer, manager, admin
- [ ] 25.11 Confirmación antes de cambiar
- [ ] 25.12 Rol se actualiza en la base de datos
- [ ] 25.13 Permisos del usuario se actualizan
- [ ] 25.14 Mensaje de éxito

**Activar/Desactivar:**
- [ ] 25.15 Toggle activo/inactivo funciona
- [ ] 25.16 Estado se actualiza
- [ ] 25.17 Usuarios inactivos no pueden iniciar sesión
- [ ] 25.18 Mensaje apropiado

---

## 📧 SISTEMA DE EMAILS

### 26. Email de Confirmación de Orden

**Trigger:**
- [ ] 26.1 Email se envía automáticamente al crear una orden
- [ ] 26.2 Solo se envía si usuario tiene email_notifications = true
- [ ] 26.3 Email aparece en los logs de Laravel

**Contenido:**
- [ ] 26.4 Subject es apropiado
- [ ] 26.5 Logo/header de Petunia Play se muestra
- [ ] 26.6 Saludo personalizado con nombre del cliente
- [ ] 26.7 Número de orden se muestra
- [ ] 26.8 Lista de productos comprados se muestra
- [ ] 26.9 Cantidades y precios son correctos
- [ ] 26.10 Subtotal, IVA, envío, total son correctos
- [ ] 26.11 Dirección de envío se muestra
- [ ] 26.12 Método de pago se muestra
- [ ] 26.13 Información de contacto se incluye
- [ ] 26.14 Footer legal se muestra:
  - [ ] 26.14.1 Mención Ley 1581 de 2012
  - [ ] 26.14.2 Derechos RACS
  - [ ] 26.14.3 Responsable del tratamiento
  - [ ] 26.14.4 Datos de contacto
- [ ] 26.15 Link de unsubscribe funciona
- [ ] 26.16 Token de unsubscribe está encriptado
- [ ] 26.17 Diseño HTML es responsive
- [ ] 26.18 Colores y estilos son consistentes con la marca

### 27. Email de Actualización de Envío

**Trigger:**
- [ ] 27.1 Email se envía al crear un envío
- [ ] 27.2 Email se envía al actualizar a "in_transit"
- [ ] 27.3 Email se envía al actualizar a "delivered"
- [ ] 27.4 Solo se envía si usuario tiene email_notifications = true

**Contenido:**
- [ ] 27.5 Subject cambia según el estado:
  - [ ] 27.5.1 "Tu pedido ha sido procesado" (pending)
  - [ ] 27.5.2 "Tu pedido está en camino" (in_transit)
  - [ ] 27.5.3 "Tu pedido ha sido entregado" (delivered)
- [ ] 27.6 Emoji apropiado en el header
- [ ] 27.7 Número de orden se muestra
- [ ] 27.8 Número de tracking se muestra destacado
- [ ] 27.9 Transportadora se muestra
- [ ] 27.10 Estado del envío se muestra con color
- [ ] 27.11 Fechas (envío/entrega) se muestran cuando aplica
- [ ] 27.12 Dirección de envío se muestra
- [ ] 27.13 Notas adicionales se muestran (si existen)
- [ ] 27.14 Link "Ver mis pedidos" funciona
- [ ] 27.15 Footer legal completo se muestra
- [ ] 27.16 Link de unsubscribe funciona
- [ ] 27.17 Diseño es responsive

### 28. Sistema de Unsubscribe

- [ ] 28.1 Link de unsubscribe abre página correcta
- [ ] 28.2 Token se valida correctamente
- [ ] 28.3 Campo email_notifications se pone en false
- [ ] 28.4 Usuario deja de recibir emails
- [ ] 28.5 Mensaje de confirmación apropiado
- [ ] 28.6 Link de resubscribe funciona (si existe)
- [ ] 28.7 Token inválido muestra mensaje de error

---

## 🔒 SEGURIDAD Y PERMISOS

### 29. Autenticación

- [ ] 29.1 Sanctum genera tokens correctamente
- [ ] 29.2 Tokens se guardan en localStorage
- [ ] 29.3 Header Authorization se envía en todas las peticiones
- [ ] 29.4 Token inválido retorna 401
- [ ] 29.5 Token expirado retorna 401
- [ ] 29.6 Usuario se desautentica correctamente
- [ ] 29.7 Rutas protegidas requieren autenticación
- [ ] 29.8 Redirección a login cuando no autenticado

### 30. Autorización (Roles)

**Customer:**
- [ ] 30.1 Puede acceder a su cuenta
- [ ] 30.2 Puede ver sus pedidos
- [ ] 30.3 NO puede acceder al admin
- [ ] 30.4 Redirección si intenta acceder al admin

**Manager:**
- [ ] 30.5 Puede acceder a todas las secciones de admin excepto usuarios
- [ ] 30.6 Puede gestionar productos
- [ ] 30.7 Puede gestionar categorías
- [ ] 30.8 Puede gestionar órdenes
- [ ] 30.9 Puede gestionar envíos
- [ ] 30.10 Puede gestionar cupones
- [ ] 30.11 Puede ver reportes
- [ ] 30.12 NO puede gestionar usuarios
- [ ] 30.13 Redirección si intenta acceder a usuarios

**Admin:**
- [ ] 30.14 Tiene acceso completo
- [ ] 30.15 Puede gestionar usuarios
- [ ] 30.16 Puede cambiar roles
- [ ] 30.17 Puede hacer todo lo que manager

### 31. Validación y Sanitización

**Frontend:**
- [ ] 31.1 Todos los formularios validan antes de enviar
- [ ] 31.2 Mensajes de error son claros
- [ ] 31.3 Campos requeridos están marcados
- [ ] 31.4 Formatos de email se validan
- [ ] 31.5 Contraseñas tienen mínimo de caracteres
- [ ] 31.6 Números de teléfono se validan

**Backend:**
- [ ] 31.7 Todos los endpoints validan input
- [ ] 31.8 Requests usan Form Requests
- [ ] 31.9 Validación de tipos de datos
- [ ] 31.10 Validación de rangos (precios, stock, etc.)
- [ ] 31.11 Sanitización de strings
- [ ] 31.12 Protección contra SQL injection
- [ ] 31.13 Protección contra XSS
- [ ] 31.14 Mensajes de error no exponen información sensible

### 32. Protección de Datos

- [ ] 32.1 Contraseñas se hashean con bcrypt
- [ ] 32.2 Tokens se encriptan (unsubscribe)
- [ ] 32.3 Información sensible no se muestra en logs
- [ ] 32.4 API no retorna contraseñas
- [ ] 32.5 CORS está configurado correctamente
- [ ] 32.6 CSRF protection está activa

---

## 🎨 UI/UX Y DISEÑO

### 33. Responsive Design

**Desktop (>1024px):**
- [ ] 33.1 Todas las páginas se ven correctamente
- [ ] 33.2 Navegación horizontal funciona
- [ ] 33.3 Grids de productos muestran 3-4 columnas
- [ ] 33.4 Imágenes no se distorsionan
- [ ] 33.5 Modales se centran correctamente
- [ ] 33.6 Footer se muestra completo

**Tablet (768px-1024px):**
- [ ] 33.7 Todas las páginas se adaptan
- [ ] 33.8 Navegación se adapta o colapsa
- [ ] 33.9 Grids muestran 2-3 columnas
- [ ] 33.10 Formularios son usables
- [ ] 33.11 Tablas son scrolleables horizontalmente

**Móvil (<768px):**
- [ ] 33.12 Todas las páginas son usables
- [ ] 33.13 Menú hamburguesa funciona
- [ ] 33.14 Grids muestran 1-2 columnas
- [ ] 33.15 Botones son suficientemente grandes (min 44px)
- [ ] 33.16 Texto es legible sin zoom
- [ ] 33.17 Formularios no requieren scroll horizontal
- [ ] 33.18 Modales ocupan toda la pantalla
- [ ] 33.19 Footer se adapta

### 34. Dark Mode

- [ ] 34.1 Toggle de dark mode funciona
- [ ] 34.2 Preferencia se guarda en localStorage
- [ ] 34.3 Se mantiene entre páginas
- [ ] 34.4 Se mantiene al recargar
- [ ] 34.5 Todos los colores se adaptan
- [ ] 34.6 Texto es legible en ambos modos
- [ ] 34.7 Imágenes se ven bien en ambos modos
- [ ] 34.8 Íconos cambian apropiadamente
- [ ] 34.9 No hay "flashing" al cargar

### 35. Accesibilidad

- [ ] 35.1 Todos los botones tienen labels descriptivos
- [ ] 35.2 Imágenes tienen alt text
- [ ] 35.3 Formularios tienen labels asociados
- [ ] 35.4 Contraste de colores es adecuado
- [ ] 35.5 Se puede navegar con teclado (Tab)
- [ ] 35.6 Focus visible en elementos interactivos
- [ ] 35.7 Errores se anuncian claramente
- [ ] 35.8 No hay solo-color como indicador

### 36. Performance

- [ ] 36.1 Páginas cargan en menos de 3 segundos
- [ ] 36.2 Imágenes están optimizadas
- [ ] 36.3 Lazy loading de imágenes funciona
- [ ] 36.4 No hay memory leaks (abrir DevTools)
- [ ] 36.5 API responses son rápidas (<500ms)
- [ ] 36.6 Paginación evita cargar todo de una vez
- [ ] 36.7 Búsqueda tiene debounce
- [ ] 36.8 No hay renders innecesarios

### 37. Notificaciones Toast

- [ ] 37.1 Toasts aparecen en posición consistente
- [ ] 37.2 Toasts de éxito son verdes
- [ ] 37.3 Toasts de error son rojos
- [ ] 37.4 Toasts de info son azules
- [ ] 37.5 Toasts de warning son amarillos
- [ ] 37.6 Toasts se cierran automáticamente
- [ ] 37.7 Se pueden cerrar manualmente
- [ ] 37.8 No se apilan demasiados toasts
- [ ] 37.9 Mensajes son claros y concisos

---

## 🗃️ BASE DE DATOS Y BACKEND

### 38. Integridad de Datos

**Productos:**
- [ ] 38.1 No se pueden crear productos sin nombre
- [ ] 38.2 SKU es único
- [ ] 38.3 Slug es único
- [ ] 38.4 Precio no puede ser negativo
- [ ] 38.5 Stock no puede ser negativo
- [ ] 38.6 Relación con categoría funciona (foreign key)
- [ ] 38.7 Cascada al eliminar categoría

**Órdenes:**
- [ ] 38.8 Número de orden es único
- [ ] 38.9 Relación con usuario funciona
- [ ] 38.10 Relación con dirección funciona
- [ ] 38.11 Relación con items funciona
- [ ] 38.12 Relación con payment funciona
- [ ] 38.13 Relación con shipment funciona
- [ ] 38.14 Totales se calculan correctamente
- [ ] 38.15 Estados son válidos (enum)

**Usuarios:**
- [ ] 38.16 Email es único
- [ ] 38.17 Contraseña se hashea
- [ ] 38.18 Rol es válido (enum)
- [ ] 38.19 Timestamps se guardan correctamente

**Envíos:**
- [ ] 38.20 Relación con orden funciona (1-a-1)
- [ ] 38.21 Tracking number es único
- [ ] 38.22 Fechas se guardan correctamente
- [ ] 38.23 Estados son válidos (enum)

### 39. Migraciones y Seeders

- [ ] 39.1 Todas las migraciones corren sin errores
- [ ] 39.2 Comando `php artisan migrate:fresh` funciona
- [ ] 39.3 Seeders crean datos de prueba
- [ ] 39.4 Datos de seed son realistas
- [ ] 39.5 Rollback funciona correctamente

### 40. API Endpoints

**Productos:**
- [ ] 40.1 GET /api/v1/products retorna lista
- [ ] 40.2 GET /api/v1/products/{slug} retorna producto
- [ ] 40.3 GET /api/v1/products/search funciona
- [ ] 40.4 GET /api/v1/products/featured funciona
- [ ] 40.5 Filtros y ordenamiento funcionan
- [ ] 40.6 Paginación funciona

**Categorías:**
- [ ] 40.7 GET /api/v1/categories retorna lista
- [ ] 40.8 GET /api/v1/categories/{slug} retorna categoría

**Órdenes:**
- [ ] 40.9 POST /api/v1/orders crea orden (auth opcional)
- [ ] 40.10 GET /api/v1/orders retorna órdenes del usuario (auth)
- [ ] 40.11 GET /api/v1/orders/{number} retorna orden específica
- [ ] 40.12 PUT /api/v1/orders/{id}/cancel cancela orden

**Admin:**
- [ ] 40.13 Todos los endpoints de admin requieren auth
- [ ] 40.14 Endpoints de manager requieren rol manager
- [ ] 40.15 Endpoints de admin requieren rol admin
- [ ] 40.16 401 si no autenticado
- [ ] 40.17 403 si no tiene permisos

---

## ✅ FUNCIONALIDADES ESPECIALES

### 41. Sistema de Cupones

**Validación:**
- [ ] 41.1 Cupón inválido muestra error
- [ ] 41.2 Cupón expirado muestra error
- [ ] 41.3 Cupón inactivo no se puede usar
- [ ] 41.4 Cupón con límite de usos agotado no funciona
- [ ] 41.5 Cupón con monto mínimo valida correctamente
- [ ] 41.6 Código no es case-sensitive (se convierte a mayúsculas)

**Aplicación:**
- [ ] 41.7 Descuento porcentual se calcula correctamente
- [ ] 41.8 Descuento fijo se aplica correctamente
- [ ] 41.9 Descuento no puede ser mayor que el total
- [ ] 41.10 Contador de usos se incrementa
- [ ] 41.11 Cupón se guarda en la orden
- [ ] 41.12 Un solo cupón por orden

### 42. Google Maps Integration

**Carga:**
- [ ] 42.1 API key está configurada en .env
- [ ] 42.2 Mapa carga sin errores
- [ ] 42.3 No hay errores de billing en consola
- [ ] 42.4 Mapa se muestra en español

**Funcionalidad:**
- [ ] 42.5 Geolocalización solicita permiso
- [ ] 42.6 Marcador es arrastrable
- [ ] 42.7 Click en mapa mueve marcador
- [ ] 42.8 Geocoding inverso funciona
- [ ] 42.9 Dirección se obtiene correctamente
- [ ] 42.10 Ciudad y departamento se extraen
- [ ] 42.11 Código postal se extrae (si está disponible)
- [ ] 42.12 Coordenadas se guardan en el pedido

### 43. Stock Management

**Reducción de Stock:**
- [ ] 43.1 Stock se reduce al crear orden
- [ ] 43.2 No se puede comprar más del stock disponible
- [ ] 43.3 Validación en frontend
- [ ] 43.4 Validación en backend
- [ ] 43.5 Mensaje de error apropiado

**Devolución de Stock:**
- [ ] 43.6 Stock se devuelve al cancelar orden
- [ ] 43.7 Cantidades correctas se devuelven
- [ ] 43.8 Stock se actualiza inmediatamente

**Alertas:**
- [ ] 43.9 Productos con stock bajo aparecen en dashboard
- [ ] 43.10 Productos sin stock aparecen marcados
- [ ] 43.11 Badge "Agotado" en frontend cuando stock = 0
- [ ] 43.12 No se puede agregar al carrito si stock = 0

### 44. Reportes y Exportación

**Excel:**
- [ ] 44.1 Exportación de productos funciona
- [ ] 44.2 Exportación de órdenes funciona
- [ ] 44.3 Reporte de ventas funciona
- [ ] 44.4 Archivos se descargan correctamente
- [ ] 44.5 Datos son correctos
- [ ] 44.6 Formato es apropiado
- [ ] 44.7 Headers están incluidos
- [ ] 44.8 Filtros se aplican en la exportación

**PDF:**
- [ ] 44.9 Exportación de órdenes a PDF funciona
- [ ] 44.10 Archivo se descarga correctamente
- [ ] 44.11 Diseño es profesional
- [ ] 44.12 Datos son correctos
- [ ] 44.13 Logo se muestra (si está incluido)

---

## 🐛 CASOS EDGE Y ERRORES

### 45. Manejo de Errores

**Network Errors:**
- [ ] 45.1 Error de conexión muestra mensaje apropiado
- [ ] 45.2 Timeout muestra mensaje apropiado
- [ ] 45.3 No hay crash de la app

**Validation Errors:**
- [ ] 45.4 422 muestra errores de validación claros
- [ ] 45.5 Errores se muestran en el campo correspondiente
- [ ] 45.6 Errores se limpian al corregir

**Server Errors:**
- [ ] 45.7 500 muestra mensaje genérico (no expone detalles)
- [ ] 45.8 404 muestra página/mensaje apropiado
- [ ] 45.9 403 muestra mensaje de sin permisos

**Auth Errors:**
- [ ] 45.10 401 redirige a login
- [ ] 45.11 Token inválido limpia localStorage
- [ ] 45.12 Mensaje apropiado se muestra

### 46. Casos Límite

**Carrito:**
- [ ] 46.1 Carrito vacío muestra mensaje apropiado
- [ ] 46.2 Producto sin stock se maneja correctamente
- [ ] 46.3 Stock cambió entre agregar al carrito y checkout
- [ ] 46.4 Producto eliminado mientras está en carrito

**Órdenes:**
- [ ] 46.5 Orden sin productos (no debería pasar)
- [ ] 46.6 Total = 0 (cupón 100%)
- [ ] 46.7 Múltiples órdenes simultáneas del mismo usuario
- [ ] 46.8 Orden de usuario eliminado

**Stock:**
- [ ] 46.9 Stock = 0 se maneja
- [ ] 46.10 Stock negativo no se permite
- [ ] 46.11 Múltiples usuarios comprando el último stock

**Emails:**
- [ ] 46.12 Email falla pero orden se crea igual
- [ ] 46.13 Usuario sin email
- [ ] 46.14 Email inválido en guest checkout

---

## 📱 NAVEGADORES Y DISPOSITIVOS

### 47. Compatibilidad de Navegadores

**Chrome:**
- [ ] 47.1 Todas las funcionalidades funcionan
- [ ] 47.2 No hay errores en consola
- [ ] 47.3 Diseño se ve correcto

**Firefox:**
- [ ] 47.4 Todas las funcionalidades funcionan
- [ ] 47.5 No hay errores en consola
- [ ] 47.6 Diseño se ve correcto

**Safari:**
- [ ] 47.7 Todas las funcionalidades funcionan
- [ ] 47.8 Formularios funcionan correctamente
- [ ] 47.9 Diseño se ve correcto

**Edge:**
- [ ] 47.10 Todas las funcionalidades funcionan
- [ ] 47.11 No hay errores en consola
- [ ] 47.12 Diseño se ve correcto

### 48. Dispositivos Móviles

**Android:**
- [ ] 48.1 La app es usable
- [ ] 48.2 Touch funciona correctamente
- [ ] 48.3 Keyboard no oculta contenido importante
- [ ] 48.4 Botones son fáciles de presionar

**iOS:**
- [ ] 48.5 La app es usable
- [ ] 48.6 Safari móvil funciona correctamente
- [ ] 48.7 Gestures funcionan
- [ ] 48.8 No hay problemas con el teclado

---

## 📋 CHECKLIST FINAL DE PRODUCCIÓN

### 49. Configuración de Producción

- [ ] 49.1 .env de producción configurado
- [ ] 49.2 APP_DEBUG = false
- [ ] 49.3 APP_ENV = production
- [ ] 49.4 Claves API de producción configuradas
- [ ] 49.5 Base de datos de producción configurada
- [ ] 49.6 SMTP de producción configurado
- [ ] 49.7 Google Maps API key con restricciones
- [ ] 49.8 Dominio configurado
- [ ] 49.9 SSL/HTTPS funcionando
- [ ] 49.10 Storage configurado (S3/Cloudinary)

### 50. SEO y Analytics

- [ ] 50.1 Meta tags básicos en todas las páginas
- [ ] 50.2 Títulos descriptivos
- [ ] 50.3 Descriptions apropiadas
- [ ] 50.4 Sitemap XML (opcional pero recomendado)
- [ ] 50.5 Robots.txt configurado
- [ ] 50.6 Google Analytics instalado (opcional)
- [ ] 50.7 URLs amigables (slugs)
- [ ] 50.8 Canonical URLs

---

## 📝 NOTAS DE TESTING

**Bugs Encontrados:**
1. _______________________________________________________________
2. _______________________________________________________________
3. _______________________________________________________________
4. _______________________________________________________________
5. _______________________________________________________________

**Mejoras Sugeridas:**
1. _______________________________________________________________
2. _______________________________________________________________
3. _______________________________________________________________

**Conclusión Final:**
- [ ] ✅ El sistema está listo para producción
- [ ] ⚠️ El sistema necesita correcciones menores
- [ ] ❌ El sistema necesita correcciones mayores

**Firma del Testeador:** _______________ **Fecha:** _______________

---

## 🎉 RESUMEN

**Total de Puntos de Prueba:** 500+

**Distribución:**
- Frontend Público: ~150 puntos
- Autenticación: ~50 puntos
- Panel Admin: ~180 puntos
- Emails: ~30 puntos
- Seguridad: ~30 puntos
- UI/UX: ~40 puntos
- Backend: ~20 puntos

**Tiempo Estimado de Testing Completo:** 8-12 horas

---

**¡Buen testing! 🚀**
