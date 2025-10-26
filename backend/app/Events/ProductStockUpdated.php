<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductStockUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Product $product;
    public int $previousStock;

    /**
     * Create a new event instance.
     */
    public function __construct(Product $product, int $previousStock)
    {
        $this->product = $product;
        $this->previousStock = $previousStock;
    }
}
