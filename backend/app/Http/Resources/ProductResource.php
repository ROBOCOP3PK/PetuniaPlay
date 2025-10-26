<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'brand' => $this->brand,
            'slug' => $this->slug,
            'description' => $this->description,
            'long_description' => $this->when($request->routeIs('products.show'), $this->long_description),
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'final_price' => $this->final_price,
            'has_discount' => $this->has_discount,
            'discount_percentage' => $this->discount_percentage,
            'sku' => $this->sku,
            'stock' => $this->stock,
            'low_stock_threshold' => $this->low_stock_threshold,
            'in_stock' => $this->in_stock,
            'is_low_stock' => $this->is_low_stock,
            'is_out_of_stock' => $this->is_out_of_stock,
            'weight' => $this->weight,
            'is_featured' => $this->is_featured,
            'average_rating' => round($this->average_rating, 1),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'images' => ProductImageResource::collection($this->whenLoaded('images')),
            'primary_image' => new ProductImageResource($this->whenLoaded('primaryImage')),
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
