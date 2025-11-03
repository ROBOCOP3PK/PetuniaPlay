<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WishlistItem;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Get all wishlist items for authenticated user
     */
    public function index(Request $request)
    {
        $wishlistItems = $request->user()
            ->wishlistItems()
            ->with(['product.images', 'product.primaryImage', 'product.category'])
            ->get();

        // Formatear los datos para el frontend
        $wishlist = $wishlistItems->map(function ($item) {
            return [
                'id' => $item->id,
                'product' => $item->product,
                'added_at' => $item->created_at,
            ];
        });

        return response()->json([
            'data' => $wishlist
        ]);
    }

    /**
     * Add a product to wishlist
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $userId = $request->user()->id;
        $productId = $validated['product_id'];

        // Verificar si el producto ya está en la wishlist
        $existingItem = WishlistItem::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existingItem) {
            return response()->json([
                'message' => 'Este producto ya está en tu lista de deseos'
            ], 409);
        }

        // Crear el item en la wishlist
        $wishlistItem = WishlistItem::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        // Cargar el producto con sus relaciones
        $wishlistItem->load(['product.images', 'product.primaryImage', 'product.category']);

        return response()->json([
            'data' => [
                'id' => $wishlistItem->id,
                'product' => $wishlistItem->product,
                'added_at' => $wishlistItem->created_at,
            ],
            'message' => 'Producto agregado a la lista de deseos'
        ], 201);
    }

    /**
     * Remove a product from wishlist
     */
    public function destroy(Request $request, $productId)
    {
        $wishlistItem = WishlistItem::where('user_id', $request->user()->id)
            ->where('product_id', $productId)
            ->first();

        if (!$wishlistItem) {
            return response()->json([
                'message' => 'Producto no encontrado en la lista de deseos'
            ], 404);
        }

        $wishlistItem->delete();

        return response()->json([
            'message' => 'Producto eliminado de la lista de deseos'
        ]);
    }

    /**
     * Check if a product is in user's wishlist
     */
    public function check(Request $request, $productId)
    {
        $exists = WishlistItem::where('user_id', $request->user()->id)
            ->where('product_id', $productId)
            ->exists();

        return response()->json([
            'in_wishlist' => $exists
        ]);
    }

    /**
     * Get all product IDs in user's wishlist
     */
    public function getProductIds(Request $request)
    {
        $productIds = $request->user()
            ->wishlistItems()
            ->pluck('product_id');

        return response()->json([
            'product_ids' => $productIds
        ]);
    }

    /**
     * Clear all items from wishlist
     */
    public function clear(Request $request)
    {
        $request->user()->wishlistItems()->delete();

        return response()->json([
            'message' => 'Lista de deseos vaciada exitosamente'
        ]);
    }
}
