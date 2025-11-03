<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Exports\ProductsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);

        // Si hay filtros, usar el método de filtrado avanzado
        if ($request->hasAny(['min_price', 'max_price', 'brand', 'category', 'in_stock', 'min_rating', 'sort_by', 'animal_section_id'])) {
            $filters = [
                'animal_section_id' => $request->get('animal_section_id'),
                'category' => $request->get('category'),
                'min_price' => $request->get('min_price'),
                'max_price' => $request->get('max_price'),
                'brand' => $request->get('brand'),
                'in_stock' => $request->boolean('in_stock'),
                'min_rating' => $request->get('min_rating'),
                'sort_by' => $request->get('sort_by'),
            ];

            $products = $this->productService->filterProducts($filters, $perPage);
        } else {
            $products = $this->productService->getAllProducts($perPage);
        }

        return ProductResource::collection($products);
    }

    /**
     * Display featured products.
     */
    public function featured(Request $request)
    {
        $limit = $request->get('limit', 8);
        $products = $this->productService->getFeaturedProducts($limit);

        return ProductResource::collection($products);
    }

    /**
     * Search products.
     */
    public function search(Request $request)
    {
        $term = $request->get('q');
        $perPage = $request->get('per_page', 15);

        if (!$term) {
            return response()->json(['message' => 'Se requiere un término de búsqueda'], 400);
        }

        $products = $this->productService->searchProducts($term, $perPage);

        return ProductResource::collection($products);
    }

    /**
     * Autocomplete search for products.
     */
    public function autocomplete(Request $request)
    {
        $term = $request->get('q');
        $limit = $request->get('limit', 10);

        if (!$term || strlen($term) < 2) {
            return response()->json(['data' => []]);
        }

        $products = $this->productService->autocompleteProducts($term, $limit);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $product = $this->productService->createProduct($request->validated());

            return new ProductResource($product);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear producto: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource by slug.
     */
    public function show(string $slug)
    {
        try {
            $product = $this->productService->getProductBySlug($slug);

            return new ProductResource($product);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        try {
            $product = $this->productService->updateProduct($id, $request->validated());

            return new ProductResource($product);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar producto: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->productService->deleteProduct($id);

            return response()->json(['message' => 'Producto eliminado exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar producto: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get all unique brands.
     */
    public function brands()
    {
        try {
            $brands = $this->productService->getAllBrands();
            return response()->json(['brands' => $brands]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener marcas: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get related products by slug.
     */
    public function related(string $slug)
    {
        try {
            $product = $this->productService->getProductBySlug($slug);
            $relatedProducts = $this->productService->getRelatedProducts($product, 4);

            return ProductResource::collection($relatedProducts);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener productos relacionados'], 500);
        }
    }

    /**
     * Export products to Excel (admin only)
     */
    public function exportExcel(Request $request)
    {
        $category = $request->input('category');
        $brand = $request->input('brand');
        $lowStock = $request->boolean('low_stock');

        $filename = 'productos_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(
            new ProductsExport($category, $brand, $lowStock),
            $filename
        );
    }
}
