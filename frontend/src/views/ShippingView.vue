<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
            Política de Envíos
          </h1>
          <p class="text-gray-600 dark:text-gray-400">
            Última actualización: {{ lastUpdated }}
          </p>
        </div>

        <!-- Content -->
        <div class="prose prose-gray dark:prose-invert max-w-none">
          <!-- Introduction -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              Información General
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              En Petunia Play, nos comprometemos a entregar sus productos de manera rápida y segura. Esta política describe
              nuestras opciones de envío, tiempos de entrega, costos y procedimientos.
            </p>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Procesamos los pedidos de lunes a viernes de 9:00 AM a 6:00 PM. Los pedidos recibidos después de las 2:00 PM
              o durante fines de semana y días festivos se procesarán el siguiente día hábil.
            </p>
          </section>

          <!-- 1. Shipping Methods -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              1. Métodos de Envío
            </h2>

            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                Envío Estándar (3-5 días hábiles)
              </h3>
              <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                <li>Entrega en 3-5 días hábiles</li>
                <li>Envío GRATIS en pedidos superiores a $50,000 COP</li>
                <li>Costo: $8,000 COP para pedidos menores a $50,000 COP</li>
                <li>Seguimiento en línea disponible</li>
              </ul>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                Envío Express (1-2 días hábiles)
              </h3>
              <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                <li>Entrega en 1-2 días hábiles</li>
                <li>Costo: $15,000 COP</li>
                <li>Seguimiento prioritario en línea</li>
                <li>Disponible solo en áreas metropolitanas principales</li>
              </ul>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                Recogida en Tienda (Gratis)
              </h3>
              <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                <li>Disponible en nuestras tiendas físicas</li>
                <li>Pedido listo en 24-48 horas</li>
                <li>Notificación por email cuando esté listo</li>
                <li>Presentar orden de compra y documento de identidad</li>
              </ul>
            </div>
          </section>

          <!-- 2. Shipping Costs -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              2. Costos de Envío
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Los costos de envío se calculan automáticamente al finalizar la compra y se basan en:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4 mb-4">
              <li>Peso total del pedido</li>
              <li>Destino de entrega</li>
              <li>Método de envío seleccionado</li>
            </ul>
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
              <p class="text-green-800 dark:text-green-300 font-semibold">
                💡 Envío GRATIS en pedidos superiores a $50,000 COP con envío estándar
              </p>
            </div>
          </section>

          <!-- 3. Delivery Times -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              3. Tiempos de Entrega
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Los tiempos de entrega son estimados y comienzan a contar desde que el pedido es despachado, no desde la
              fecha de compra. Los tiempos pueden variar según:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4 mb-4">
              <li><strong>Ubicación:</strong> Zonas urbanas reciben entregas más rápidas que zonas rurales</li>
              <li><strong>Clima:</strong> Condiciones climáticas adversas pueden causar retrasos</li>
              <li><strong>Temporada alta:</strong> Fechas especiales (Navidad, Día de la Madre, etc.) pueden extender los tiempos</li>
              <li><strong>Disponibilidad de producto:</strong> Algunos productos pueden requerir tiempo adicional de preparación</li>
            </ul>
          </section>

          <!-- 4. Tracking -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              4. Seguimiento de Pedidos
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Una vez que su pedido sea despachado, recibirá:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4 mb-4">
              <li>Email de confirmación de envío con número de rastreo</li>
              <li>Enlace directo para rastrear su paquete en tiempo real</li>
              <li>Actualizaciones de estado del envío</li>
              <li>Notificación cuando el paquete esté en camino para entrega</li>
            </ul>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Puede rastrear su pedido en cualquier momento desde su cuenta en "Mis Pedidos" o usando el número de rastreo
              proporcionado.
            </p>
          </section>

          <!-- 5. International Shipping -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              5. Envíos Internacionales
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Actualmente, solo realizamos envíos dentro del territorio colombiano. Estamos trabajando para ofrecer
              envíos internacionales en el futuro.
            </p>
          </section>

          <!-- 6. Delivery Issues -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              6. Problemas con la Entrega
            </h2>

            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
              Dirección Incorrecta
            </h3>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Es responsabilidad del cliente proporcionar una dirección de envío correcta y completa. Si el paquete es
              devuelto debido a una dirección incorrecta, el cliente será responsable de los costos de reenvío.
            </p>

            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
              Paquete No Entregado
            </h3>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Si su paquete no llega dentro del tiempo estimado:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4 mb-4">
              <li>Verifique el estado del rastreo en línea</li>
              <li>Contacte a nuestro servicio al cliente dentro de los 7 días posteriores a la fecha estimada de entrega</li>
              <li>Proporcione su número de orden y número de rastreo</li>
              <li>Investigaremos con la empresa de transporte y resolveremos el problema</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
              Paquete Dañado
            </h3>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Si recibe un paquete dañado:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4 mb-4">
              <li>No abra el paquete si nota daños externos evidentes</li>
              <li>Tome fotografías del paquete dañado</li>
              <li>Contacte a nuestro servicio al cliente dentro de las 48 horas</li>
              <li>Procesaremos un reemplazo o reembolso según corresponda</li>
            </ul>
          </section>

          <!-- 7. Restrictions -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              7. Restricciones de Envío
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Algunos productos pueden tener restricciones de envío debido a:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4 mb-4">
              <li>Tamaño o peso del producto</li>
              <li>Regulaciones de transporte (productos refrigerados, aerosoles, etc.)</li>
              <li>Disponibilidad limitada a ciertas regiones</li>
            </ul>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Estas restricciones se indicarán claramente en la página del producto antes de la compra.
            </p>
          </section>

          <!-- 8. Contact -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              8. Contacto
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Si tiene preguntas sobre nuestra política de envíos o necesita ayuda con su pedido, contáctenos:
            </p>
            <ul class="list-none text-gray-700 dark:text-gray-300 space-y-2 mb-4">
              <li>📧 Email: envios@petuniaplay.com</li>
              <li>📞 Teléfono: +57 (1) 234-5678</li>
              <li>💬 Chat en vivo: Disponible en nuestro sitio web</li>
              <li>🕐 Horario: Lunes a Viernes, 9:00 AM - 6:00 PM</li>
            </ul>
          </section>

          <!-- Footer Note -->
          <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 mt-8">
            <p class="text-blue-900 dark:text-blue-300">
              <strong>Nota:</strong> Petunia Play se reserva el derecho de modificar esta política de envíos en cualquier
              momento. Los cambios entrarán en vigencia inmediatamente después de su publicación en este sitio web.
              Le recomendamos revisar esta página periódicamente para estar al tanto de cualquier cambio.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const lastUpdated = ref(new Date().toLocaleDateString('es-CO', {
  year: 'numeric',
  month: 'long',
  day: 'numeric'
}))
</script>
