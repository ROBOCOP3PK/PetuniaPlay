<?php

namespace App\Services;

use App\Models\ShippingConfig;

class ShippingCalculatorService
{
    /**
     * Calcular el costo de envío basado en los parámetros
     *
     * @param string $city Ciudad de destino
     * @param int $itemCount Cantidad de artículos en el pedido
     * @param bool $nightDelivery Si se solicita entrega nocturna
     * @return float Costo de envío
     */
    public function calculate(string $city, int $itemCount, bool $nightDelivery = false): float
    {
        $config = ShippingConfig::current();

        $cityNormalized = strtolower(trim($city));
        $isBogota = in_array($cityNormalized, ['bogotá', 'bogota']);

        // Envío gratis en Bogotá si elige entrega nocturna y está habilitada
        if ($isBogota && $nightDelivery && $config->night_delivery_enabled) {
            return 0.0;
        }

        // Envío gratis en Bogotá si compra más artículos que el mínimo configurado
        if ($isBogota && $itemCount >= $config->free_shipping_min_items) {
            return 0.0;
        }

        // Para Bogotá sin cumplir condiciones, o para otras ciudades
        return (float) $config->standard_shipping_cost;
    }

    /**
     * Calcular el costo de envío desde datos de la orden
     *
     * @param array $orderData Datos de la orden
     * @return float Costo de envío
     */
    public function calculateFromOrderData(array $orderData): float
    {
        $city = $orderData['shipping']['city'] ?? '';
        $itemCount = count($orderData['items'] ?? []);
        $nightDelivery = $orderData['shipping']['nightDelivery'] ?? false;

        return $this->calculate($city, $itemCount, $nightDelivery);
    }
}
