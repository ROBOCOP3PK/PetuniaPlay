<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Get all addresses for authenticated user
     */
    public function index(Request $request)
    {
        $addresses = $request->user()
            ->addresses()
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $addresses
        ]);
    }

    /**
     * Store a new address
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:500',
            'address_line_2' => 'nullable|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'boolean',
        ]);

        // Si esta dirección será la predeterminada, quitar el flag de las demás
        if ($validated['is_default'] ?? false) {
            $request->user()->addresses()->update(['is_default' => false]);
        }

        $address = $request->user()->addresses()->create($validated);

        return response()->json([
            'data' => $address,
            'message' => 'Dirección creada exitosamente'
        ], 201);
    }

    /**
     * Show a specific address
     */
    public function show(Request $request, $id)
    {
        $address = $request->user()->addresses()->findOrFail($id);

        return response()->json([
            'data' => $address
        ]);
    }

    /**
     * Update an address
     */
    public function update(Request $request, $id)
    {
        $address = $request->user()->addresses()->findOrFail($id);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:500',
            'address_line_2' => 'nullable|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'boolean',
        ]);

        // Si esta dirección será la predeterminada, quitar el flag de las demás
        if ($validated['is_default'] ?? false) {
            $request->user()->addresses()->where('id', '!=', $id)->update(['is_default' => false]);
        }

        $address->update($validated);

        return response()->json([
            'data' => $address,
            'message' => 'Dirección actualizada exitosamente'
        ]);
    }

    /**
     * Delete an address
     */
    public function destroy(Request $request, $id)
    {
        $address = $request->user()->addresses()->findOrFail($id);
        $address->delete();

        return response()->json([
            'message' => 'Dirección eliminada exitosamente'
        ]);
    }

    /**
     * Set an address as default
     */
    public function setDefault(Request $request, $id)
    {
        $address = $request->user()->addresses()->findOrFail($id);

        // Quitar el flag de predeterminada de todas las direcciones
        $request->user()->addresses()->update(['is_default' => false]);

        // Marcar esta como predeterminada
        $address->update(['is_default' => true]);

        return response()->json([
            'data' => $address,
            'message' => 'Dirección predeterminada actualizada'
        ]);
    }
}
