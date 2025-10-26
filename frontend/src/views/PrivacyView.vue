<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
            Política de Privacidad
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
              Introducción
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              En Petunia Play, valoramos su privacidad y nos comprometemos a proteger su información personal. Esta Política de
              Privacidad explica cómo recopilamos, usamos, divulgamos y protegemos su información cuando visita nuestro sitio web
              y realiza compras.
            </p>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Al usar nuestro sitio, usted acepta la recopilación y el uso de información de acuerdo con esta política.
            </p>
          </section>

          <!-- 1. Information We Collect -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              1. Información que Recopilamos
            </h2>

            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
              1.1 Información que Usted Proporciona
            </h3>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Recopilamos información que usted nos proporciona directamente, incluyendo:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 mb-4 space-y-2">
              <li><strong>Información de cuenta:</strong> Nombre, dirección de correo electrónico, contraseña</li>
              <li><strong>Información de pedido:</strong> Dirección de envío y facturación, número de teléfono</li>
              <li><strong>Información de pago:</strong> Datos de tarjeta de crédito/débito (procesados de forma segura por proveedores de pago terceros)</li>
              <li><strong>Información de contacto:</strong> Cuando nos contacta con preguntas, comentarios o solicitudes de soporte</li>
            </ul>

            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
              1.2 Información Recopilada Automáticamente
            </h3>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Cuando visita nuestro sitio, recopilamos automáticamente cierta información, incluyendo:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 mb-4 space-y-2">
              <li><strong>Información del dispositivo:</strong> Dirección IP, tipo de navegador, sistema operativo</li>
              <li><strong>Información de uso:</strong> Páginas visitadas, tiempo en el sitio, enlaces en los que hace clic</li>
              <li><strong>Cookies y tecnologías similares:</strong> Para mejorar su experiencia y analizar el uso del sitio</li>
            </ul>
          </section>

          <!-- 2. How We Use Your Information -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              2. Cómo Usamos su Información
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Usamos la información que recopilamos para:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 mb-4 space-y-2">
              <li>Procesar y cumplir sus pedidos</li>
              <li>Comunicarnos con usted sobre su pedido, cuenta o consultas</li>
              <li>Enviarle actualizaciones de pedidos y confirmaciones de envío</li>
              <li>Mejorar nuestro sitio web y servicios</li>
              <li>Personalizar su experiencia de compra</li>
              <li>Detectar, prevenir y abordar problemas técnicos o de seguridad</li>
              <li>Cumplir con obligaciones legales</li>
              <li>Enviarle correos electrónicos promocionales (si ha optado por recibirlos)</li>
            </ul>
          </section>

          <!-- 3. Information Sharing -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              3. Compartir Información
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              No vendemos, intercambiamos ni transferimos su información personal a terceros, excepto en los siguientes casos:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 mb-4 space-y-2">
              <li><strong>Proveedores de servicios:</strong> Compartimos información con proveedores que nos ayudan a operar nuestro
                negocio (procesadores de pago, servicios de envío, servicios de email)</li>
              <li><strong>Cumplimiento legal:</strong> Cuando sea requerido por ley o para proteger nuestros derechos</li>
              <li><strong>Transferencia de negocio:</strong> En caso de fusión, adquisición o venta de activos</li>
              <li><strong>Con su consentimiento:</strong> Cuando usted nos da permiso para compartir su información</li>
            </ul>
          </section>

          <!-- 4. Data Security -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              4. Seguridad de Datos
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Implementamos medidas de seguridad técnicas y organizativas apropiadas para proteger su información personal contra
              el acceso no autorizado, alteración, divulgación o destrucción. Estas medidas incluyen:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 mb-4 space-y-2">
              <li>Cifrado SSL/TLS para todas las transmisiones de datos</li>
              <li>Sistemas de pago seguros mediante proveedores certificados PCI-DSS</li>
              <li>Almacenamiento seguro de contraseñas mediante hash</li>
              <li>Acceso restringido a información personal solo a empleados autorizados</li>
              <li>Auditorías de seguridad regulares</li>
            </ul>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Sin embargo, ningún método de transmisión por Internet o almacenamiento electrónico es 100% seguro, por lo que no
              podemos garantizar la seguridad absoluta.
            </p>
          </section>

          <!-- 5. Cookies -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              5. Cookies y Tecnologías de Seguimiento
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Utilizamos cookies y tecnologías similares para:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 mb-4 space-y-2">
              <li>Mantener su sesión iniciada</li>
              <li>Recordar sus preferencias</li>
              <li>Mantener los artículos en su carrito de compras</li>
              <li>Analizar el tráfico del sitio web</li>
              <li>Mejorar la funcionalidad del sitio</li>
            </ul>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Puede configurar su navegador para rechazar cookies, pero esto puede afectar la funcionalidad del sitio.
            </p>
          </section>

          <!-- 6. Your Rights -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              6. Sus Derechos
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Usted tiene derecho a:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 mb-4 space-y-2">
              <li><strong>Acceder:</strong> Solicitar una copia de la información personal que tenemos sobre usted</li>
              <li><strong>Rectificar:</strong> Corregir información inexacta o incompleta</li>
              <li><strong>Eliminar:</strong> Solicitar la eliminación de su información personal</li>
              <li><strong>Oponerse:</strong> Oponerse al procesamiento de su información personal</li>
              <li><strong>Portabilidad:</strong> Recibir su información en un formato estructurado y de uso común</li>
              <li><strong>Retirar consentimiento:</strong> Retirar su consentimiento en cualquier momento</li>
            </ul>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Para ejercer estos derechos, contáctenos a través de privacy@petuniaplay.com
            </p>
          </section>

          <!-- 7. Data Retention -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              7. Retención de Datos
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Conservamos su información personal durante el tiempo necesario para cumplir con los propósitos descritos en esta
              política, a menos que la ley requiera o permita un período de retención más largo. Los criterios para determinar
              nuestros períodos de retención incluyen:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 mb-4 space-y-2">
              <li>El tiempo que tenga una relación continua con nosotros</li>
              <li>Si existe una obligación legal a la que estemos sujetos</li>
              <li>Si la retención es aconsejable a la luz de nuestra posición legal</li>
            </ul>
          </section>

          <!-- 8. Children's Privacy -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              8. Privacidad de Menores
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Nuestro sitio web está destinado a usuarios mayores de 18 años. No recopilamos intencionalmente información personal
              de niños menores de 13 años. Si descubrimos que hemos recopilado información de un niño menor de 13 años, eliminaremos
              esa información de inmediato.
            </p>
          </section>

          <!-- 9. Changes to Policy -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              9. Cambios a Esta Política
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Podemos actualizar esta Política de Privacidad periódicamente. Le notificaremos cualquier cambio publicando la nueva
              política en esta página y actualizando la fecha de "última actualización". Se le recomienda revisar esta política
              periódicamente para conocer cualquier cambio.
            </p>
          </section>

          <!-- 10. Contact -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
              10. Contacto
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              Si tiene preguntas o inquietudes sobre esta Política de Privacidad, puede contactarnos:
            </p>
            <ul class="list-none text-gray-700 dark:text-gray-300 mb-4 space-y-2">
              <li>• Por correo electrónico: privacy@petuniaplay.com</li>
              <li>• A través de nuestra
                <router-link to="/contact" class="text-primary hover:text-primary-dark font-semibold">
                  página de contacto
                </router-link>
              </li>
            </ul>
          </section>
        </div>

        <!-- Back to Home -->
        <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
          <router-link
            to="/"
            class="inline-flex items-center gap-2 text-primary hover:text-primary-dark font-semibold"
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
