<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
            Política de Devoluciones y Reembolsos
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
              Nuestra Garantía de Satisfacción
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              En Petunia Play, queremos que esté completamente satisfecho con su compra. Si no está satisfecho por cualquier motivo,
              ofrecemos una política de devolución flexible. Esta política describe los términos y condiciones de nuestro proceso de
              devolución y reembolso.
            </p>
          </section>

          <!-- 1. Return Period -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              1. Período de Devolución
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Tiene <strong>30 días naturales</strong> desde la fecha de recepción de su pedido para iniciar una devolución.
              Para ser elegible para una devolución:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 mb-4 space-y-2">
              <li>El artículo debe estar sin usar y en las mismas condiciones en que lo recibió</li>
              <li>Debe estar en su embalaje original</li>
              <li>Debe incluir todos los accesorios, manuales y documentación</li>
              <li>Debe tener el recibo o comprobante de compra</li>
            </ul>
          </section>

          <!-- 2. Non-Returnable Items -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              2. Artículos No Retornables
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Los siguientes artículos no pueden ser devueltos:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 mb-4 space-y-2">
              <li>Artículos personalizados o hechos a medida</li>
              <li>Artículos en oferta o liquidación (salvo defectos de fabricación)</li>
              <li>Tarjetas de regalo</li>
              <li>Software descargable o productos digitales</li>
              <li>Artículos de higiene personal una vez abiertos</li>
              <li>Productos perecederos</li>
            </ul>
          </section>

          <!-- 3. How to Return -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              3. Cómo Realizar una Devolución
            </h2>

            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
              Paso 1: Solicitar Devolución
            </h3>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Para iniciar una devolución:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 mb-4 space-y-2">
              <li>Inicie sesión en su cuenta y vaya a "Mis Pedidos"</li>
              <li>Seleccione el pedido que desea devolver</li>
              <li>Haga clic en "Solicitar Devolución"</li>
              <li>Indique el motivo de la devolución</li>
            </ul>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Alternativamente, puede contactarnos directamente a través de nuestra
              <router-link to="/contact" class="text-primary dark:text-fuchsia-400 hover:text-primary-dark font-semibold">
                página de contacto
              </router-link>
              con su número de pedido.
            </p>

            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
              Paso 2: Empaquetar el Artículo
            </h3>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Una vez aprobada su solicitud:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 mb-4 space-y-2">
              <li>Empaque el artículo de forma segura en su caja original si es posible</li>
              <li>Incluya todos los accesorios y documentación</li>
              <li>Imprima y adjunte la etiqueta de devolución que le enviaremos por correo electrónico</li>
            </ul>

            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
              Paso 3: Enviar el Paquete
            </h3>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Envíe el paquete a la dirección indicada en la etiqueta de devolución. Recomendamos usar un servicio de envío con
              seguimiento y seguro.
            </p>
          </section>

          <!-- 4. Return Shipping -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              4. Costos de Envío de Devolución
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Los costos de envío de devolución varían según el motivo:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 mb-4 space-y-2">
              <li><strong>Defecto o Error Nuestro:</strong> Cubrimos todos los costos de envío de devolución</li>
              <li><strong>Cambio de Opinión:</strong> Usted es responsable de los costos de envío de devolución</li>
              <li><strong>Artículo Incorrecto Recibido:</strong> Cubrimos todos los costos de envío</li>
            </ul>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Los costos de envío originales no son reembolsables, excepto en casos de defecto o error de nuestra parte.
            </p>
          </section>

          <!-- 5. Refund Process -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              5. Proceso de Reembolso
            </h2>

            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
              Inspección
            </h3>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Una vez que recibamos su devolución, la inspeccionaremos y le notificaremos si el reembolso ha sido aprobado o rechazado.
            </p>

            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
              Procesamiento del Reembolso
            </h3>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Si su devolución es aprobada:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 mb-4 space-y-2">
              <li>Procesaremos su reembolso en <strong>5-7 días hábiles</strong></li>
              <li>El reembolso se acreditará al método de pago original</li>
              <li>Recibirá un correo electrónico de confirmación cuando se procese el reembolso</li>
              <li>Dependiendo de su banco, puede tomar 3-5 días adicionales para que el crédito aparezca en su cuenta</li>
            </ul>

            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
              Reembolsos Parciales
            </h3>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              En algunos casos, solo se aprueban reembolsos parciales:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 mb-4 space-y-2">
              <li>Artículos con signos obvios de uso</li>
              <li>Artículos devueltos más de 30 días después de la entrega</li>
              <li>Artículos no en su condición original, dañados o con piezas faltantes no debido a un error nuestro</li>
            </ul>
          </section>

          <!-- 6. Exchanges -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              6. Cambios
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Solo reemplazamos artículos si están defectuosos o dañados. Si necesita cambiarlo por el mismo artículo:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 mb-4 space-y-2">
              <li>Contacte nuestro servicio de atención al cliente</li>
              <li>Proporcione fotos del defecto o daño</li>
              <li>Le enviaremos un artículo de reemplazo sin costo adicional</li>
            </ul>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Si desea un artículo diferente, le recomendamos devolver el artículo original para un reembolso y realizar una nueva
              compra del artículo deseado.
            </p>
          </section>

          <!-- 7. Damaged or Defective Items -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              7. Artículos Dañados o Defectuosos
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Si recibe un artículo dañado o defectuoso:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 mb-4 space-y-2">
              <li>Contáctenos <strong>dentro de 48 horas</strong> después de recibir el pedido</li>
              <li>Proporcione fotos claras del daño o defecto</li>
              <li>Incluya su número de pedido</li>
            </ul>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Le enviaremos un reemplazo o procesaremos un reembolso completo, incluyendo todos los costos de envío.
            </p>
          </section>

          <!-- 8. Late or Missing Refunds -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              8. Reembolsos Tardíos o Faltantes
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Si aún no ha recibido un reembolso:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 mb-4 space-y-2">
              <li>Primero verifique su cuenta bancaria nuevamente</li>
              <li>Contacte a su compañía de tarjeta de crédito - puede tomar tiempo antes de que su reembolso se publique oficialmente</li>
              <li>Contacte a su banco - a menudo hay algún tiempo de procesamiento antes de que se publique un reembolso</li>
              <li>Si ha hecho todo esto y aún no ha recibido su reembolso, contáctenos a returns@petuniaplay.com</li>
            </ul>
          </section>

          <!-- 9. Gift Returns -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              9. Devoluciones de Regalos
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Si el artículo fue marcado como regalo cuando se compró y se envió directamente a usted:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 mb-4 space-y-2">
              <li>Recibirá un crédito de tienda por el valor del artículo devuelto</li>
              <li>Se enviará un certificado de crédito de regalo por correo o email</li>
            </ul>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Si el regalo no fue marcado como tal, o quien lo regaló recibió el pedido para dárselo a usted más tarde, enviaremos
              el reembolso al comprador.
            </p>
          </section>

          <!-- 10. Contact -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              10. Preguntas sobre Devoluciones
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Si tiene alguna pregunta sobre nuestra política de devoluciones, contáctenos:
            </p>
            <ul class="list-none text-gray-700 dark:text-gray-300 mb-4 space-y-2">
              <li>• Por correo electrónico: returns@petuniaplay.com</li>
              <li>• Por teléfono: +57 (123) 456-7890</li>
              <li>• A través de nuestra
                <router-link to="/contact" class="text-primary dark:text-fuchsia-400 hover:text-primary-dark font-semibold">
                  página de contacto
                </router-link>
              </li>
            </ul>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Nuestro equipo de servicio al cliente está disponible de lunes a viernes, de 9:00 AM a 6:00 PM.
            </p>
          </section>

          <!-- Highlight Box -->
          <div class="bg-primary bg-opacity-10 border border-primary rounded-lg p-6 mt-8">
            <h3 class="text-xl font-bold text-primary mb-3 flex items-center gap-2">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              Su Satisfacción es Nuestra Prioridad
            </h3>
            <p class="text-gray-700 dark:text-gray-300">
              En Petunia Play, nos esforzamos por proporcionar productos de calidad y un excelente servicio al cliente.
              Si tiene algún problema con su pedido, no dude en contactarnos. Estamos aquí para ayudarle.
            </p>
          </div>
        </div>

        <!-- Back to Home -->
        <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
          <router-link
            to="/"
            class="inline-flex items-center gap-2 text-primary dark:text-fuchsia-400 hover:text-primary-dark font-semibold"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver al inicio
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const lastUpdated = ref('26 de Enero de 2024')
</script>
