<?php

namespace App\Services;

use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Exceptions\MPApiException;
use Exception;

class MercadoPagoService
{
    protected $accessToken;
    protected $testMode;

    public function __construct()
    {
        $this->accessToken = config('mercadopago.access_token');
        $this->testMode = config('mercadopago.test_mode');

        // Configurar el SDK de Mercado Pago
        MercadoPagoConfig::setAccessToken($this->accessToken);

        // Opcional: Configurar timeout
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
    }

    /**
     * Crear una preferencia de pago (Checkout Pro)
     *
     * @param array $orderData
     * @return array
     */
    public function createPreference(array $orderData)
    {
        try {
            $client = new PreferenceClient();

            // Preparar items para Mercado Pago
            $items = collect($orderData['items'])->map(function ($item) {
                return [
                    'id' => (string) $item['product_id'],
                    'title' => $item['product_name'],
                    'description' => $item['product_description'] ?? 'Producto de Petunia Play',
                    'picture_url' => $item['product_image'] ?? null,
                    'category_id' => 'toys',
                    'quantity' => (int) $item['quantity'],
                    'currency_id' => 'COP',
                    'unit_price' => (float) $item['price'],
                ];
            })->toArray();

            // Preparar datos del pagador
            $payer = [
                'name' => $orderData['customer']['name'],
                'email' => $orderData['customer']['email'],
                'phone' => [
                    'number' => $orderData['customer']['phone'],
                ],
                'identification' => [
                    'type' => 'CC', // Cédula de Ciudadanía (Colombia)
                    'number' => $orderData['customer']['document'],
                ],
                'address' => [
                    'street_name' => $orderData['shipping']['address'],
                    'street_number' => '0',
                    'zip_code' => $orderData['shipping']['zipCode'] ?? '000000',
                ],
            ];

            // Configurar URLs de retorno
            $backUrls = [
                'success' => config('mercadopago.urls.success'),
                'failure' => config('mercadopago.urls.failure'),
                'pending' => config('mercadopago.urls.pending'),
            ];

            // Preparar metadata con información adicional del pedido
            $metadata = [
                'order_number' => $orderData['order_number'] ?? null,
                'order_id' => $orderData['order_id'] ?? null,
                'shipping_address' => $orderData['shipping']['address'],
                'shipping_city' => $orderData['shipping']['city'],
                'shipping_notes' => $orderData['shipping']['notes'] ?? '',
                'night_delivery' => $orderData['shipping']['nightDelivery'] ?? false,
            ];

            // Datos de la preferencia
            $preferenceData = [
                'items' => $items,
                'payer' => $payer,
                'back_urls' => $backUrls,
                'auto_return' => 'approved', // Retorna automáticamente al aprobar
                'payment_methods' => [
                    'installments' => 12, // Permitir hasta 12 cuotas
                ],
                'notification_url' => config('mercadopago.notification_url'),
                'statement_descriptor' => 'PETUNIA PLAY', // Aparece en el estado de cuenta
                'external_reference' => $orderData['order_number'] ?? uniqid('ORD-'),
                'metadata' => $metadata,
            ];

            // Crear la preferencia
            $preference = $client->create($preferenceData);

            return [
                'success' => true,
                'preference_id' => $preference->id,
                'init_point' => $preference->init_point, // URL de pago
                'sandbox_init_point' => $preference->sandbox_init_point, // URL de pago en sandbox
            ];

        } catch (MPApiException $e) {
            \Log::error('Error de API de Mercado Pago', [
                'message' => $e->getMessage(),
                'status' => $e->getApiResponse()->getStatusCode(),
                'content' => $e->getApiResponse()->getContent(),
            ]);

            return [
                'success' => false,
                'error' => 'Error al procesar el pago con Mercado Pago',
                'details' => $e->getMessage(),
            ];

        } catch (Exception $e) {
            \Log::error('Error general al crear preferencia de Mercado Pago', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Error inesperado al procesar el pago',
                'details' => $e->getMessage(),
            ];
        }
    }

    /**
     * Obtener información de un pago
     *
     * @param string $paymentId
     * @return array|null
     */
    public function getPayment($paymentId)
    {
        try {
            $client = new PaymentClient();
            $payment = $client->get($paymentId);

            return [
                'id' => $payment->id,
                'status' => $payment->status,
                'status_detail' => $payment->status_detail,
                'transaction_amount' => $payment->transaction_amount,
                'currency_id' => $payment->currency_id,
                'external_reference' => $payment->external_reference,
                'payer_email' => $payment->payer->email ?? null,
                'payment_method_id' => $payment->payment_method_id,
                'payment_type_id' => $payment->payment_type_id,
            ];

        } catch (Exception $e) {
            \Log::error('Error al obtener información del pago', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Verificar la firma de las notificaciones de Mercado Pago
     *
     * @param array $headers
     * @param array $data
     * @return bool
     */
    public function verifyWebhookSignature(array $headers, array $data)
    {
        // Mercado Pago envía la firma en el header x-signature
        // Por seguridad, siempre valida las notificaciones consultando la API
        return true; // Simplificado para este ejemplo
    }
}
