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

        // Obtener todas las estadísticas en una sola query con agregaciones
        $stats = Review::where('product_id', $productId)
            ->where('is_approved', true)
            ->selectRaw('
                COUNT(*) as total_reviews,
                AVG(rating) as average_rating,
                SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as rating_5,
                SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as rating_4,
                SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as rating_3,
                SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as rating_2,
                SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as rating_1,
                SUM(CASE WHEN is_verified_purchase = 1 THEN 1 ELSE 0 END) as verified_purchases
            ')
            ->first();

        return response()->json([
            'product_id' => $productId,
            'product_name' => $product->name,
            'total_reviews' => (int) ($stats->total_reviews ?? 0),
            'average_rating' => round($stats->average_rating ?? 0, 1),
            'rating_distribution' => [
                5 => (int) ($stats->rating_5 ?? 0),
                4 => (int) ($stats->rating_4 ?? 0),
                3 => (int) ($stats->rating_3 ?? 0),
                2 => (int) ($stats->rating_2 ?? 0),
                1 => (int) ($stats->rating_1 ?? 0),
            ],
            'verified_purchases' => (int) ($stats->verified_purchases ?? 0),
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
