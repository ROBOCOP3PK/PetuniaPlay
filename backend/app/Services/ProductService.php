<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Support\Str;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts($perPage = 15)
    {
        return $this->productRepository->getAllActive($perPage);
    }

    public function getFeaturedProducts($limit = 8)
    {
        return $this->productRepository->getFeatured($limit);
    }

    public function getProductsByCategory($categoryId, $perPage = 15)
    {
        return $this->productRepository->getByCategory($categoryId, $perPage);
    }

    public function getProductBySlug($slug)
    {
        return $this->productRepository->getBySlug($slug);
    }

    public function searchProducts($term, $perPage = 15)
    {
        return $this->productRepository->search($term, $perPage);
    }

    public function getProduct($id)
    {
        return $this->productRepository->findOrFail($id);
    }

    public function createProduct(array $data)
    {
        // Generar slug si no existe
        if (!isset($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Generar SKU si no existe
        if (!isset($data['sku'])) {
            $data['sku'] = 'SKU-' . strtoupper(uniqid());
        }

        return $this->productRepository->create($data);
    }

    public function updateProduct($id, array $data)
    {
        // Actualizar slug si cambió el nombre
        if (isset($data['name']) && !isset($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $this->productRepository->update($id, $data);
    }

    public function deleteProduct($id)
    {
        return $this->productRepository->delete($id);
    }

    public function checkStock($productId, $quantity)
    {
        $product = $this->productRepository->findOrFail($productId);
        return $product->stock >= $quantity;
    }

    public function reduceStock($productId, $quantity)
    {
        if (!$this->checkStock($productId, $quantity)) {
            throw new \Exception('Stock insuficiente');
        }

        return $this->productRepository->updateStock($productId, $quantity);
    }

    public function getRelatedProducts($product, $limit = 4)
    {
        return $this->productRepository->getRelatedProducts(
            $product->category_id,
            $product->id,
            $limit
        );
    }
}
