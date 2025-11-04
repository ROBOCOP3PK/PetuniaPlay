# Configuración de Mercado Pago - PetuniaPlay

## 📋 Guía de Implementación

Esta guía te ayudará a configurar Mercado Pago en tu proyecto PetuniaPlay.

---

## 🔑 Paso 1: Obtener Credenciales de Mercado Pago

### 1.1. Crear cuenta en Mercado Pago

1. Ve a [Mercado Pago Developers](https://www.mercadopago.com.co/developers)
2. Inicia sesión con tu cuenta de Mercado Libre o crea una nueva
3. Acepta los términos y condiciones

### 1.2. Crear una Aplicación

1. Ve al Panel de Desarrolladores
2. Haz clic en **"Tus integraciones"**
3. Haz clic en **"Crear aplicación"**
4. Completa los datos:
   - **Nombre:** PetuniaPlay (o el nombre que prefieras)
   - **Tipo de producto:** Checkout Pro
   - **Modelo de integración:** Hosted checkout

### 1.3. Obtener las Credenciales

Una vez creada la aplicación:

1. Ve a **"Credenciales"** en el menú lateral
2. Verás dos conjuntos de credenciales:
   - **Credenciales de prueba** (para desarrollo)
   - **Credenciales de producción** (para ventas reales)

#### Credenciales de Prueba (Development)

Usa estas para desarrollo y pruebas:
- **Public Key:** Comienza con `TEST-...`
- **Access Token:** Comienza con `TEST-...`

#### Credenciales de Producción

Para activar las credenciales de producción necesitas:
1. Verificar tu identidad (documento de identidad)
2. Proporcionar información bancaria
3. Completar verificación de cuenta

---

## ⚙️ Paso 2: Configurar el Backend

### 2.1. Configurar variables de entorno

Edita el archivo `/backend/.env` y actualiza las siguientes líneas:

```env
# Mercado Pago Configuration
MERCADOPAGO_ACCESS_TOKEN=TEST-XXXXXXXX-XXXXXX-XXXXXX-XXXXXXXX
MERCADOPAGO_PUBLIC_KEY=TEST-XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX
MERCADOPAGO_TEST_MODE=true
```

**Importante:**
- Para **desarrollo**, usa las credenciales de TEST y `MERCADOPAGO_TEST_MODE=true`
- Para **producción**, usa las credenciales reales y `MERCADOPAGO_TEST_MODE=false`

---

## 🧪 Paso 3: Probar la Integración

### 3.1. Tarjetas de Prueba

Mercado Pago proporciona tarjetas de prueba para simular diferentes escenarios:

#### ✅ Pago Aprobado
```
Tarjeta: MASTER
Número: 5031 7557 3453 0604
CVV: 123
Fecha: 11/25
Nombre: APRO
```

#### ❌ Pago Rechazado
```
Tarjeta: MASTER
Número: 5031 7557 3453 0604
CVV: 123
Fecha: 11/25
Nombre: OXXO
```

#### ⏳ Pago Pendiente
```
Tarjeta: VISA
Número: 4009 1753 3280 6001
CVV: 123
Fecha: 11/25
Nombre: CALL
```

#### Más tarjetas de prueba:
- Encuentra más tarjetas en: [Tarjetas de Prueba Mercado Pago](https://www.mercadopago.com.co/developers/es/docs/checkout-pro/additional-content/test-cards)

### 3.2. Usuarios de prueba (Opcional)

Si necesitas crear usuarios de prueba:

1. Ve a **Tus integraciones > Tu aplicación > Cuentas de prueba**
2. Crea dos usuarios:
   - **Vendedor:** Para recibir pagos
   - **Comprador:** Para hacer las compras

---

## 🚀 Paso 4: Flujo de Pago

### Flujo completo:

1. **Usuario agrega productos al carrito**
2. **Usuario va a checkout** (`/checkout`)
3. **Usuario completa sus datos**
4. **Usuario selecciona "Pago Online con Mercado Pago"**
5. **Usuario hace clic en "Realizar Pedido"**
6. **Sistema crea la orden en la base de datos**
7. **Sistema crea preferencia de pago en Mercado Pago**
8. **Usuario es redirigido a Mercado Pago**
9. **Usuario completa el pago en Mercado Pago**
10. **Mercado Pago redirige al usuario de vuelta:**
    - ✅ `/payment/success` si el pago fue aprobado
    - ❌ `/payment/failure` si el pago fue rechazado
    - ⏳ `/payment/pending` si el pago está pendiente
11. **Mercado Pago envía notificación a nuestro webhook** (`/api/payments/webhook`)
12. **Sistema actualiza el estado de la orden**

---

## 📝 Paso 5: URLs de Configuración

Las siguientes URLs están configuradas en `/backend/config/mercadopago.php`:

```php
'urls' => [
    'success' => env('FRONTEND_URL') . '/payment/success',
    'failure' => env('FRONTEND_URL') . '/payment/failure',
    'pending' => env('FRONTEND_URL') . '/payment/pending',
],
```

Asegúrate de que `FRONTEND_URL` en tu `.env` sea correcto:
```env
FRONTEND_URL=http://localhost:5173
```

---

## 🔍 Paso 6: Verificar la Implementación

### Checklist:

- [ ] ✅ SDK de Mercado Pago instalado en backend (`composer.json`)
- [ ] ✅ Credenciales configuradas en `.env`
- [ ] ✅ Migraciones ejecutadas (`php artisan migrate`)
- [ ] ✅ Rutas de API funcionando (`/api/v1/payments/*`)
- [ ] ✅ Frontend puede crear preferencias de pago
- [ ] ✅ Redirección a Mercado Pago funciona
- [ ] ✅ URLs de retorno funcionan correctamente
- [ ] ✅ Webhook recibe notificaciones de Mercado Pago

---

## 🐛 Solución de Problemas

### Error: "No se pudo crear la preferencia de pago"

**Posibles causas:**
1. Credenciales incorrectas en `.env`
2. Access Token expirado
3. Error en los datos de la orden

**Solución:**
```bash
# Verifica los logs del backend
tail -f backend/storage/logs/laravel.log
```

### Error: "El webhook no recibe notificaciones"

**Para desarrollo local:**
1. Usa **ngrok** para exponer tu localhost:
   ```bash
   ngrok http 8000
   ```
2. Actualiza `MERCADOPAGO_NOTIFICATION_URL` en el código con la URL de ngrok

**Para producción:**
- Asegúrate de que la URL del webhook sea accesible públicamente
- Verifica que no haya firewall bloqueando las peticiones de Mercado Pago

### Error: "Access Token inválido"

**Solución:**
1. Verifica que estés usando el Access Token correcto (TEST o PROD)
2. Regenera las credenciales desde el panel de Mercado Pago
3. Actualiza el `.env` con las nuevas credenciales

---

## 📊 Paso 7: Ir a Producción

Cuando estés listo para lanzar:

### 7.1. Verificar tu cuenta

1. Ve al panel de Mercado Pago
2. Completa la verificación de identidad
3. Proporciona información bancaria
4. Espera aprobación (1-3 días hábiles)

### 7.2. Actualizar credenciales

1. Obtén las credenciales de **Producción** desde el panel
2. Actualiza tu `.env`:
   ```env
   MERCADOPAGO_ACCESS_TOKEN=APP-XXXXXXXX-XXXXXX-XXXXXX-XXXXXXXX
   MERCADOPAGO_PUBLIC_KEY=APP-XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX
   MERCADOPAGO_TEST_MODE=false
   ```

### 7.3. Probar en producción

1. Realiza una compra de prueba con una tarjeta real
2. Verifica que el pago se procese correctamente
3. Verifica que el webhook reciba notificaciones
4. Confirma que los emails de confirmación se envíen

---

## 💰 Comisiones de Mercado Pago

**Desarrollo:**
- ✅ 100% Gratis

**Producción (Colombia):**
- 💳 Tarjetas: ~3.99% + IVA por transacción
- 🏦 PSE: Tarifa fija (~$1,500-3,000 COP)
- 💵 Efectivo: Tarifa fija

**Importante:**
- Solo pagas por transacciones aprobadas
- No hay cargo mensual
- No hay costo de instalación

---

## 📚 Recursos Adicionales

- [Documentación oficial Checkout Pro](https://www.mercadopago.com.co/developers/es/docs/checkout-pro/landing)
- [Referencia API](https://www.mercadopago.com.co/developers/es/reference)
- [SDK PHP](https://github.com/mercadopago/sdk-php)
- [Soporte Técnico](https://www.mercadopago.com.co/developers/es/support)
- [Comunidad Discord](https://discord.gg/mercadopago)

---

## ✅ ¡Listo!

Tu integración de Mercado Pago está completa. Ahora puedes:

1. ✅ Recibir pagos con tarjetas de crédito/débito
2. ✅ Aceptar pagos con PSE
3. ✅ Ofrecer pago en efectivo (Efecty, Baloto, etc.)
4. ✅ Recibir notificaciones automáticas de cambio de estado
5. ✅ Gestionar el estado de las órdenes automáticamente

**¡Feliz venta!** 🎉
