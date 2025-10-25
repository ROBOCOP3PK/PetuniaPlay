<?php

namespace App\Repositories;

use App\Models\Product;

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
            ->paginate($perPage);
    }

    public function getFeatured($limit = 8)
    {
        return $this->model->active()
            ->featured()
            ->with(['category', 'primaryImage'])
            ->limit($limit)
            ->get();
    }

    public function getByCategory($categoryId, $perPage = 15)
    {
        return $this->model->active()
            ->where('category_id', $categoryId)
            ->with(['category', 'primaryImage'])
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
                    ->orWhere('sku', 'like', "%{$term}%");
            })
            ->with(['category', 'primaryImage'])
            ->paginate($perPage);
    }

    public function getInStock($perPage = 15)
    {
        return $this->model->active()
            ->inStock()
            ->with(['category', 'primaryImage'])
            ->paginate($perPage);
    }

    public function updateStock($productId, $quantity)
    {
        $product = $this->findOrFail($productId);
        $product->decrement('stock', $quantity);
        return $product;
    }

    public function getRelatedProducts($categoryId, $productId, $limit = 4)
    {
        return $this->model->active()
            ->where('category_id', $categoryId)
            ->where('id', '!=', $productId)
            ->with(['primaryImage'])
            ->limit($limit)
            ->get();
    }
}
