<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'animal_section_id' => $this->animal_section_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image,
            'parent_id' => $this->parent_id,
            'order' => $this->order,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relaciones
            'animal_section' => $this->whenLoaded('animalSection', function () {
                return [
                    'id' => $this->animalSection->id,
                    'name' => $this->animalSection->name,
                    'slug' => $this->animalSection->slug,
                    'icon' => $this->animalSection->icon,
                ];
            }),

            'parent' => $this->whenLoaded('parent', function () {
                return [
                    'id' => $this->parent->id,
                    'name' => $this->parent->name,
                    'slug' => $this->parent->slug,
                ];
            }),

            'children' => CategoryResource::collection($this->whenLoaded('children')),

            // Conteo de hijos
            'children_count' => $this->children()->count(),

            // Conteo de productos
            'products_count' => $this->when(
                $this->relationLoaded('products'),
                $this->products->count()
            ),
        ];
    }
}
