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
        // Extraer imágenes del array
        $images = $data['images'] ?? [];
        unset($data['images']);

        // Generar slug si no existe
        if (!isset($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Generar SKU si no existe
        if (!isset($data['sku'])) {
            $data['sku'] = 'SKU-' . strtoupper(uniqid());
        }

        // Crear producto
        $product = $this->productRepository->create($data);

        // Crear imágenes si existen
        if (!empty($images)) {
            foreach ($images as $index => $imageUrl) {
                $product->images()->create([
                    'image_url' => $imageUrl,
                    'is_primary' => $index === 0, // Primera imagen es la principal
                    'order' => $index + 1
                ]);
            }
        }

        // Recargar producto con imágenes
        return $product->load(['category', 'images']);
    }

    public function updateProduct($id, array $data)
    {
        // Extraer imágenes del array si existen
        $images = $data['images'] ?? null;
        unset($data['images']);

        // Actualizar slug si cambió el nombre
        if (isset($data['name']) && !isset($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Actualizar producto
        $product = $this->productRepository->update($id, $data);

        // Actualizar imágenes si se enviaron
        if ($images !== null) {
            // Eliminar imágenes existentes
            $product->images()->delete();

            // Crear nuevas imágenes
            if (!empty($images)) {
                foreach ($images as $index => $imageUrl) {
                    $product->images()->create([
                        'image_url' => $imageUrl,
                        'is_primary' => $index === 0,
                        'order' => $index + 1
                    ]);
                }
            }
        }

        // Recargar producto con imágenes
        return $product->load(['category', 'images']);
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

    public function filterProducts(array $filters = [], $perPage = 15)
    {
        return $this->productRepository->filterProducts($filters, $perPage);
    }

    public function getAllBrands()
    {
        return $this->productRepository->getAllBrands();
    }
}
