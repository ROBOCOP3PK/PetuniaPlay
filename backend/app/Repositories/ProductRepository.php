<?php

namespace App\Repositories;

use App\Models\Product;
use App\Events\ProductStockUpdated;
use Illuminate\Support\Facades\Cache;

class ProductRepository extends BaseRepository
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function getAllActive($perPage = 15)
    {
        return $this->model->active()
            ->with(['category', 'primaryImage', 'images'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->paginate($perPage);
    }

    public function getFeatured($limit = 8)
    {
        // Cachear productos destacados por 30 minutos
        return Cache::remember("products.featured.{$limit}", 1800, function () use ($limit) {
            return $this->model->active()
                ->featured()
                ->with(['category', 'primaryImage'])
                ->withAvg('reviews', 'rating')
                ->withCount('reviews')
                ->limit($limit)
                ->get();
        });
    }

    public function getByCategory($categoryId, $perPage = 15)
    {
        return $this->model->active()
            ->where('category_id', $categoryId)
            ->with(['category', 'primaryImage'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->paginate($perPage);
    }

    public function getBySlug($slug)
    {
        return $this->model->active()
            ->where('slug', $slug)
            ->with(['category', 'images', 'reviews.user'])
            ->firstOrFail();
    }

    public function search($term, $perPage = 15)
    {
        return $this->model->active()
            ->where(function($query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('long_description', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%")
                    ->orWhere('brand', 'like', "%{$term}%");
            })
            ->with(['category', 'primaryImage'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->paginate($perPage);
    }

    /**
     * Autocompletado rápido para búsqueda
     */
    public function autocomplete($term, $limit = 10)
    {
        return $this->model->active()
            ->where(function($query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%")
                    ->orWhere('brand', 'like', "%{$term}%");
            })
            ->select('id', 'name', 'slug', 'sku', 'brand', 'price', 'sale_price')
            ->with(['primaryImage:id,product_id,image_url'])
            ->limit($limit)
            ->get();
    }

    public function getInStock($perPage = 15)
    {
        return $this->model->active()
            ->inStock()
            ->with(['category', 'primaryImage'])
            ->paginate($perPage);
    }

    public function update($id, array $data)
    {
        $product = $this->findOrFail($id);

        // Capturar stock anterior si está cambiando
        $previousStock = $product->stock;
        $stockChanged = isset($data['stock']) && $data['stock'] != $previousStock;

        // Actualizar el producto
        $product->update($data);

        // Disparar evento si el stock cambió
        if ($stockChanged) {
            event(new ProductStockUpdated($product->fresh(), $previousStock));
        }

        return $product;
    }

    public function updateStock($productId, $quantity)
    {
        $product = $this->findOrFail($productId);
        $previousStock = $product->stock;

        $product->decrement('stock', $quantity);

        // Disparar evento de actualización de stock
        event(new ProductStockUpdated($product->fresh(), $previousStock));

        return $product;
    }

    public function getRelatedProducts($categoryId, $productId, $limit = 4)
    {
        return $this->model->active()
            ->where('category_id', $categoryId)
            ->where('id', '!=', $productId)
            ->with(['primaryImage'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->limit($limit)
            ->get();
    }

    /**
     * Filtrar productos con filtros avanzados
     */
    public function filterProducts(array $filters = [], $perPage = 15)
    {
        $query = $this->model->active()
            ->with(['category', 'primaryImage', 'reviews']);

        // Filtro por rango de precio
        if (isset($filters['min_price']) && $filters['min_price'] !== null && $filters['min_price'] !== '') {
            $query->where(function($q) use ($filters) {
                $q->where('sale_price', '>=', $filters['min_price'])
                  ->orWhere(function($sq) use ($filters) {
                      $sq->whereNull('sale_price')
                         ->where('price', '>=', $filters['min_price']);
                  });
            });
        }

        if (isset($filters['max_price']) && $filters['max_price'] !== null && $filters['max_price'] !== '') {
            $query->where(function($q) use ($filters) {
                $q->where(function($sq) use ($filters) {
                    $sq->whereNotNull('sale_price')
                       ->where('sale_price', '<=', $filters['max_price']);
                })
                ->orWhere(function($sq) use ($filters) {
                    $sq->whereNull('sale_price')
                       ->where('price', '<=', $filters['max_price']);
                });
            });
        }

        // Filtro por marca
        if (isset($filters['brand']) && $filters['brand'] !== null && $filters['brand'] !== '') {
            $query->where('brand', $filters['brand']);
        }

        // Filtro por disponibilidad de stock
        if (isset($filters['in_stock']) && $filters['in_stock'] === true) {
            $query->inStock();
        }

        // Filtro por calificación mínima
        if (isset($filters['min_rating']) && $filters['min_rating'] !== null && $filters['min_rating'] !== '') {
            $query->whereHas('reviews', function($q) {
                // Agrupa por producto y verifica que el promedio sea >= min_rating
            })->withAvg('reviews', 'rating')
              ->havingRaw('COALESCE(reviews_avg_rating, 0) >= ?', [$filters['min_rating']]);
        }

        // Ordenamiento
        if (isset($filters['sort_by'])) {
            switch ($filters['sort_by']) {
                case 'price_asc':
                    $query->orderByRaw('COALESCE(sale_price, price) ASC');
                    break;
                case 'price_desc':
                    $query->orderByRaw('COALESCE(sale_price, price) DESC');
                    break;
                case 'name':
                    $query->orderBy('name', 'ASC');
                    break;
                case 'rating':
                    $query->withAvg('reviews', 'rating')
                          ->orderByDesc('reviews_avg_rating');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'DESC');
                    break;
                default:
                    $query->orderBy('created_at', 'DESC');
            }
        } else {
            $query->orderBy('created_at', 'DESC');
        }

        return $query->paginate($perPage);
    }

    /**
     * Obtener todas las marcas únicas
     */
    public function getAllBrands()
    {
        // Cachear marcas por 2 horas
        return Cache::remember('products.brands.all', 7200, function () {
            return $this->model
                ->whereNotNull('brand')
                ->distinct()
                ->orderBy('brand')
                ->pluck('brand');
        });
    }

    /**
     * Invalidar caché cuando se crea/actualiza un producto
     */
    public function create(array $data)
    {
        $product = parent::create($data);
        $this->clearFeaturedCache();
        Cache::forget('products.brands.all');
        return $product;
    }

    public function update($id, array $data)
    {
        $product = parent::update($id, $data);

        // Invalidar caché de productos destacados si cambió is_featured
        if (isset($data['is_featured']) || isset($data['is_active'])) {
            $this->clearFeaturedCache();
        }

        // Invalidar caché de marcas si cambió la marca
        if (isset($data['brand'])) {
            Cache::forget('products.brands.all');
        }

        return $product;
    }

    protected function clearFeaturedCache()
    {
        // Limpiar múltiples cachés de featured con diferentes límites comunes
        foreach ([4, 6, 8, 10, 12] as $limit) {
            Cache::forget("products.featured.{$limit}");
        }
    }
}
