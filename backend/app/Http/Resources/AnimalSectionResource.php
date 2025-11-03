<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CategoryResource;

class AnimalSectionResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'icon' => $this->icon, // Emoji o ícono pequeño para menús
            'image' => $this->image, // Ruta de la imagen
            'image_url' => $this->image ? Storage::url($this->image) : null, // URL completa de la imagen
            'description' => $this->description,
            'order' => $this->order,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Conteo de categorías en esta sección
            'categories_count' => $this->when(
                $this->relationLoaded('categories'),
                $this->categories->count()
            ),

            // Categorías si están cargadas
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
        ];
    }
}
