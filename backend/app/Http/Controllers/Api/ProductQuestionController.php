<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductQuestion;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductQuestionController extends Controller
{
    /**
     * Obtener preguntas públicas de un producto específico
     */
    public function getByProduct($productId)
    {
        try {
            $product = Product::findOrFail($productId);

            $questions = ProductQuestion::where('product_id', $productId)
                ->public()
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'data' => $questions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las preguntas'
            ], 500);
        }
    }

    /**
     * Obtener TODAS las preguntas (solo para admin/gestor)
     * Incluye pendientes y respondidas
     */
    public function index(Request $request)
    {
        try {
            $query = ProductQuestion::with(['product.images', 'user', 'answeredBy'])
                ->latest();

            // Filtro por estado (pendiente o respondidas)
            if ($request->has('status')) {
                if ($request->status === 'pending') {
                    $query->pending();
                } elseif ($request->status === 'answered') {
                    $query->public();
                }
            }

            // Filtro por producto
            if ($request->has('product_id')) {
                $query->where('product_id', $request->product_id);
            }

            // Paginación
            $perPage = $request->get('per_page', 20);
            $questions = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $questions->items(),
                'meta' => [
                    'current_page' => $questions->currentPage(),
                    'last_page' => $questions->lastPage(),
                    'per_page' => $questions->perPage(),
                    'total' => $questions->total(),
                    'from' => $questions->firstItem(),
                    'to' => $questions->lastItem(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las preguntas'
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de preguntas (para admin)
     */
    public function stats()
    {
        try {
            $stats = [
                'total' => ProductQuestion::count(),
                'pending' => ProductQuestion::pending()->count(),
                'answered' => ProductQuestion::public()->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas'
            ], 500);
        }
    }

    /**
     * Crear una nueva pregunta (usuario autenticado)
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'question' => 'required|string|min:10|max:500'
            ], [
                'product_id.required' => 'El producto es requerido',
                'product_id.exists' => 'El producto no existe',
                'question.required' => 'La pregunta es requerida',
                'question.min' => 'La pregunta debe tener al menos 10 caracteres',
                'question.max' => 'La pregunta no puede tener más de 500 caracteres'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verificar que el producto existe y está activo
            $product = Product::where('id', $request->product_id)
                ->where('is_active', true)
                ->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'El producto no está disponible'
                ], 404);
            }

            $question = ProductQuestion::create([
                'product_id' => $request->product_id,
                'user_id' => Auth::id(),
                'question' => $request->question,
                'is_public' => false // Será público cuando se responda
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tu pregunta ha sido enviada. Será publicada cuando reciba respuesta.',
                'data' => $question->load(['user', 'product'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la pregunta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Responder una pregunta (solo admin/gestor)
     */
    public function answer(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'answer' => 'required|string|min:10|max:1000'
            ], [
                'answer.required' => 'La respuesta es requerida',
                'answer.min' => 'La respuesta debe tener al menos 10 caracteres',
                'answer.max' => 'La respuesta no puede tener más de 1000 caracteres'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $question = ProductQuestion::findOrFail($id);

            $question->update([
                'answer' => $request->answer,
                'answered_by' => Auth::id(),
                'answered_at' => now(),
                'is_public' => true // Se hace pública al responder
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pregunta respondida exitosamente',
                'data' => $question->fresh()->load(['user', 'product', 'answeredBy'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al responder la pregunta'
            ], 500);
        }
    }

    /**
     * Eliminar una pregunta (solo admin)
     */
    public function destroy($id)
    {
        try {
            $question = ProductQuestion::findOrFail($id);
            $question->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pregunta eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la pregunta'
            ], 500);
        }
    }
}
