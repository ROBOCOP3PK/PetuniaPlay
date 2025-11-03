<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Http\Resources\CouponResource;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Validar un cupón (público - para checkout)
     */
    public function validate(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'El cupón no existe'
            ], 404);
        }

        if (!$coupon->isValid()) {
            $message = 'El cupón no es válido';

            if (!$coupon->is_active) {
                $message = 'El cupón está inactivo';
            } elseif ($coupon->valid_from && $coupon->valid_from > now()) {
                $message = 'El cupón aún no está disponible';
            } elseif ($coupon->valid_until && $coupon->valid_until < now()) {
                $message = 'El cupón ha expirado';
            } elseif ($coupon->usage_limit && $coupon->usage_count >= $coupon->usage_limit) {
                $message = 'El cupón ha alcanzado su límite de usos';
            }

            return response()->json([
                'valid' => false,
                'message' => $message
            ], 400);
        }

        // Validar monto mínimo
        if ($coupon->min_purchase_amount && $request->subtotal < $coupon->min_purchase_amount) {
            return response()->json([
                'valid' => false,
                'message' => "El monto mínimo de compra es $" . number_format((float) $coupon->min_purchase_amount, 2)
            ], 400);
        }

        // Calcular descuento
        $discount = $coupon->calculateDiscount($request->subtotal);

        return response()->json([
            'valid' => true,
            'message' => 'Cupón aplicado correctamente',
            'data' => [
                'coupon' => new CouponResource($coupon),
                'discount' => round($discount, 2),
                'type' => $coupon->type,
                'value' => $coupon->value,
            ]
        ]);
    }

    /**
     * Admin: Listar todos los cupones
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');
        $status = $request->get('status'); // active, expired, all

        $query = Coupon::query()->orderBy('created_at', 'desc');

        if ($search) {
            $query->where('code', 'like', "%{$search}%");
        }

        if ($status === 'active') {
            $query->active();
        } elseif ($status === 'expired') {
            $query->where('is_active', false)
                ->orWhere(function ($q) {
                    $q->whereNotNull('valid_until')
                        ->where('valid_until', '<', now());
                });
        }

        $coupons = $query->paginate($perPage);

        return CouponResource::collection($coupons);
    }

    /**
     * Admin: Crear cupón
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'is_active' => 'boolean',
        ]);

        // Convertir código a mayúsculas
        $validated['code'] = strtoupper($validated['code']);

        // Validar valor de porcentaje
        if ($validated['type'] === 'percentage' && $validated['value'] > 100) {
            return response()->json([
                'message' => 'El porcentaje no puede ser mayor a 100'
            ], 422);
        }

        $coupon = Coupon::create($validated);

        return response()->json([
            'data' => new CouponResource($coupon),
            'message' => 'Cupón creado exitosamente'
        ], 201);
    }

    /**
     * Admin: Obtener un cupón
     */
    public function show($id)
    {
        $coupon = Coupon::findOrFail($id);
        return new CouponResource($coupon);
    }

    /**
     * Admin: Actualizar cupón
     */
    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $validated = $request->validate([
            'code' => 'sometimes|required|string|max:50|unique:coupons,code,' . $id,
            'type' => 'sometimes|required|in:percentage,fixed',
            'value' => 'sometimes|required|numeric|min:0',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'is_active' => 'boolean',
        ]);

        // Convertir código a mayúsculas si se proporciona
        if (isset($validated['code'])) {
            $validated['code'] = strtoupper($validated['code']);
        }

        // Validar valor de porcentaje
        if (isset($validated['type']) && $validated['type'] === 'percentage' &&
            isset($validated['value']) && $validated['value'] > 100) {
            return response()->json([
                'message' => 'El porcentaje no puede ser mayor a 100'
            ], 422);
        }

        $coupon->update($validated);

        return response()->json([
            'data' => new CouponResource($coupon),
            'message' => 'Cupón actualizado exitosamente'
        ]);
    }

    /**
     * Admin: Eliminar cupón
     */
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);

        // Verificar si el cupón ha sido usado
        if ($coupon->usage_count > 0) {
            return response()->json([
                'message' => 'No se puede eliminar un cupón que ya ha sido usado. Puedes desactivarlo en su lugar.'
            ], 400);
        }

        $coupon->delete();

        return response()->json([
            'message' => 'Cupón eliminado exitosamente'
        ]);
    }

    /**
     * Admin: Alternar estado activo/inactivo
     */
    public function toggleStatus($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->is_active = !$coupon->is_active;
        $coupon->save();

        return response()->json([
            'data' => new CouponResource($coupon),
            'message' => $coupon->is_active ? 'Cupón activado' : 'Cupón desactivado'
        ]);
    }

    /**
     * Admin: Estadísticas de cupones
     */
    public function stats()
    {
        $totalCoupons = Coupon::count();
        $activeCoupons = Coupon::active()->count();
        $totalUsage = Coupon::sum('usage_count');
        $mostUsed = Coupon::orderBy('usage_count', 'desc')
            ->limit(5)
            ->get(['code', 'usage_count', 'type', 'value']);

        return response()->json([
            'total_coupons' => $totalCoupons,
            'active_coupons' => $activeCoupons,
            'total_usage' => $totalUsage,
            'most_used' => $mostUsed,
        ]);
    }
}
