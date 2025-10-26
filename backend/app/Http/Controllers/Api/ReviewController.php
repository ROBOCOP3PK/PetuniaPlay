<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use App\Http\Resources\ReviewResource;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Obtener reseñas de un producto
     */
    public function index(Request $request, $productId)
    {
        $perPage = $request->get('per_page', 10);

        $reviews = Review::where('product_id', $productId)
            ->approved()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return ReviewResource::collection($reviews);
    }

    /**
     * Crear nueva reseña
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string|max:1000',
        ]);

        // Verificar que el usuario no haya reseñado ya este producto
        $existingReview = Review::where('product_id', $validated['product_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'message' => 'Ya has creado una reseña para este producto'
            ], 409);
        }

        // Verificar si es una compra verificada
        $isVerifiedPurchase = Order::where('user_id', $request->user()->id)
            ->whereHas('items', function($query) use ($validated) {
                $query->where('product_id', $validated['product_id']);
            })
            ->whereIn('status', ['delivered'])
            ->exists();

        // Crear reseña
        $review = Review::create([
            'product_id' => $validated['product_id'],
            'user_id' => $request->user()->id,
            'rating' => $validated['rating'],
            'title' => $validated['title'] ?? null,
            'comment' => $validated['comment'],
            'is_verified_purchase' => $isVerifiedPurchase,
            'is_approved' => true, // Auto-aprobar (puedes cambiar esto si quieres moderación)
        ]);

        $review->load('user');

        return response()->json([
            'data' => new ReviewResource($review),
            'message' => 'Reseña creada exitosamente'
        ], 201);
    }

    /**
     * Actualizar reseña (solo el propietario)
     */
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        // Verificar que el usuario sea el propietario
        if ($review->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'No tienes permiso para editar esta reseña'
            ], 403);
        }

        $validated = $request->validate([
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'sometimes|required|string|max:1000',
        ]);

        $review->update($validated);
        $review->load('user');

        return response()->json([
            'data' => new ReviewResource($review),
            'message' => 'Reseña actualizada exitosamente'
        ]);
    }

    /**
     * Eliminar reseña (solo el propietario)
     */
    public function destroy(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        // Verificar que el usuario sea el propietario
        if ($review->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'No tienes permiso para eliminar esta reseña'
            ], 403);
        }

        $review->delete();

        return response()->json([
            'message' => 'Reseña eliminada exitosamente'
        ]);
    }

    /**
     * Obtener estadísticas de reseñas de un producto
     */
    public function stats($productId)
    {
        $product = Product::findOrFail($productId);

        $reviews = Review::where('product_id', $productId)->approved();

        $totalReviews = $reviews->count();
        $averageRating = $reviews->avg('rating') ?? 0;

        // Distribución por estrellas
        $ratingDistribution = [
            5 => $reviews->clone()->where('rating', 5)->count(),
            4 => $reviews->clone()->where('rating', 4)->count(),
            3 => $reviews->clone()->where('rating', 3)->count(),
            2 => $reviews->clone()->where('rating', 2)->count(),
            1 => $reviews->clone()->where('rating', 1)->count(),
        ];

        $verifiedPurchases = $reviews->clone()->where('is_verified_purchase', true)->count();

        return response()->json([
            'product_id' => $productId,
            'product_name' => $product->name,
            'total_reviews' => $totalReviews,
            'average_rating' => round($averageRating, 1),
            'rating_distribution' => $ratingDistribution,
            'verified_purchases' => $verifiedPurchases,
        ]);
    }

    /**
     * Admin: Aprobar/rechazar reseña
     */
    public function toggleApproval(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $review->is_approved = !$review->is_approved;
        $review->save();

        return response()->json([
            'data' => new ReviewResource($review),
            'message' => $review->is_approved ? 'Reseña aprobada' : 'Reseña rechazada'
        ]);
    }

    /**
     * Admin: Obtener todas las reseñas (pendientes y aprobadas)
     */
    public function adminIndex(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $status = $request->get('status'); // 'approved', 'pending'

        $query = Review::with(['user', 'product'])
            ->orderBy('created_at', 'desc');

        if ($status === 'approved') {
            $query->where('is_approved', true);
        } elseif ($status === 'pending') {
            $query->where('is_approved', false);
        }

        $reviews = $query->paginate($perPage);

        return ReviewResource::collection($reviews);
    }
}
