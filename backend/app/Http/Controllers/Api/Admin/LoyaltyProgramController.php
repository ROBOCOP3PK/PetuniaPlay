<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLoyaltyProgramRequest;
use App\Models\LoyaltyProgram;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;

class LoyaltyProgramController extends Controller
{
    protected $loyaltyService;

    public function __construct(LoyaltyService $loyaltyService)
    {
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Get the loyalty program (there should only be one).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $program = LoyaltyProgram::first();

            if (!$program) {
                return response()->json([
                    'data' => null,
                    'message' => 'No hay programa de lealtad configurado'
                ], 404);
            }

            return response()->json([
                'data' => $program
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener el programa de lealtad',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create or update the loyalty program.
     *
     * @param \App\Http\Requests\StoreLoyaltyProgramRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreLoyaltyProgramRequest $request)
    {
        try {
            $validated = $request->validated();

            // Check if a program already exists
            $program = LoyaltyProgram::first();

            if ($program) {
                // Update existing program
                $program->update($validated);
                $message = 'Programa de lealtad actualizado exitosamente';
            } else {
                // Create new program
                $program = LoyaltyProgram::create($validated);
                $message = 'Programa de lealtad creado exitosamente';
            }

            return response()->json([
                'data' => $program,
                'message' => $message
            ], $program->wasRecentlyCreated ? 201 : 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al guardar el programa de lealtad',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Activate or deactivate the loyalty program.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function activate(Request $request)
    {
        try {
            $validated = $request->validate([
                'is_active' => 'required|boolean'
            ]);

            $program = LoyaltyProgram::first();

            if (!$program) {
                return response()->json([
                    'message' => 'No hay programa de lealtad configurado'
                ], 404);
            }

            $program->update(['is_active' => $validated['is_active']]);

            return response()->json([
                'data' => $program,
                'message' => $program->is_active
                    ? 'Programa de lealtad activado'
                    : 'Programa de lealtad desactivado'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cambiar el estado del programa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get loyalty program statistics.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistics()
    {
        try {
            $stats = $this->loyaltyService->getStatistics();

            return response()->json([
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener estadísticas',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
