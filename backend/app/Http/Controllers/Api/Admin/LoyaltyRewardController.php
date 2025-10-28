<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLoyaltyRewardRequest;
use App\Models\LoyaltyReward;
use App\Models\LoyaltyProgram;
use Illuminate\Http\Request;

class LoyaltyRewardController extends Controller
{
    /**
     * Display a listing of loyalty rewards.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $program = LoyaltyProgram::first();

            if (!$program) {
                return response()->json([
                    'data' => [],
                    'message' => 'No hay programa de lealtad configurado'
                ]);
            }

            $query = LoyaltyReward::where('loyalty_program_id', $program->id)
                ->with('product.primaryImage');

            // Filter by type
            if ($request->has('type')) {
                $query->where('type', $request->type);
            }

            // Filter by status
            if ($request->has('is_active')) {
                $query->where('is_active', $request->is_active);
            }

            $rewards = $query->orderBy('required_purchases', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'data' => $rewards
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener las recompensas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created reward.
     *
     * @param \App\Http\Requests\StoreLoyaltyRewardRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreLoyaltyRewardRequest $request)
    {
        try {
            $program = LoyaltyProgram::first();

            if (!$program) {
                return response()->json([
                    'message' => 'Debes crear un programa de lealtad primero'
                ], 404);
            }

            $validated = $request->validated();
            $validated['loyalty_program_id'] = $program->id;

            $reward = LoyaltyReward::create($validated);

            // Load relationships
            $reward->load('product.primaryImage');

            return response()->json([
                'data' => $reward,
                'message' => 'Recompensa creada exitosamente'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la recompensa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified reward.
     *
     * @param \App\Http\Requests\StoreLoyaltyRewardRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreLoyaltyRewardRequest $request, int $id)
    {
        try {
            $reward = LoyaltyReward::findOrFail($id);

            $validated = $request->validated();
            $reward->update($validated);

            // Reload relationships
            $reward->load('product.primaryImage');

            return response()->json([
                'data' => $reward,
                'message' => 'Recompensa actualizada exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar la recompensa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified reward.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            $reward = LoyaltyReward::findOrFail($id);

            // Check if reward has redemptions
            if ($reward->redemptions()->exists()) {
                return response()->json([
                    'message' => 'No se puede eliminar una recompensa que ya tiene canjes registrados'
                ], 422);
            }

            $reward->delete();

            return response()->json([
                'message' => 'Recompensa eliminada exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar la recompensa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle reward active status.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggle(int $id)
    {
        try {
            $reward = LoyaltyReward::findOrFail($id);

            $reward->update(['is_active' => !$reward->is_active]);

            return response()->json([
                'data' => $reward,
                'message' => $reward->is_active
                    ? 'Recompensa activada'
                    : 'Recompensa desactivada'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cambiar el estado de la recompensa',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
