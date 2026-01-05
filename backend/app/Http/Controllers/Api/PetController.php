<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\AnimalSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    /**
     * Obtener todas las mascotas del usuario autenticado
     */
    public function index()
    {
        $pets = Auth::user()->pets()
            ->with('animalSection')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $pets
        ]);
    }

    /**
     * Crear una nueva mascota
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'animal_section_id' => 'nullable|exists:animal_sections,id',
            'birth_date' => 'nullable|date|before_or_equal:today',
            'weight' => 'nullable|numeric|min:0|max:999.99',
            'photo' => 'nullable|string|max:500',
        ]);

        $validated['user_id'] = Auth::id();

        $pet = Pet::create($validated);
        $pet->load('animalSection');

        return response()->json([
            'data' => $pet,
            'message' => 'Mascota registrada exitosamente'
        ], 201);
    }

    /**
     * Mostrar una mascota específica
     */
    public function show($id)
    {
        $pet = Pet::with('animalSection')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return response()->json([
            'data' => $pet
        ]);
    }

    /**
     * Actualizar una mascota
     */
    public function update(Request $request, $id)
    {
        $pet = Pet::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:100',
            'animal_section_id' => 'nullable|exists:animal_sections,id',
            'birth_date' => 'nullable|date|before_or_equal:today',
            'weight' => 'nullable|numeric|min:0|max:999.99',
            'photo' => 'nullable|string|max:500',
        ]);

        $pet->update($validated);
        $pet->load('animalSection');

        return response()->json([
            'data' => $pet,
            'message' => 'Mascota actualizada exitosamente'
        ]);
    }

    /**
     * Eliminar una mascota
     */
    public function destroy($id)
    {
        $pet = Pet::where('user_id', Auth::id())->findOrFail($id);

        // Si tiene foto, eliminarla del storage
        if ($pet->photo && !str_starts_with($pet->photo, 'http')) {
            Storage::disk('public')->delete($pet->photo);
        }

        $pet->delete();

        return response()->json([
            'message' => 'Mascota eliminada exitosamente'
        ]);
    }

    /**
     * Subir foto de mascota
     */
    public function uploadPhoto(Request $request, $id)
    {
        $pet = Pet::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        // Eliminar foto anterior si existe
        if ($pet->photo && !str_starts_with($pet->photo, 'http')) {
            Storage::disk('public')->delete($pet->photo);
        }

        // Guardar nueva foto
        $path = $request->file('photo')->store('pets', 'public');
        $pet->update(['photo' => Storage::url($path)]);

        return response()->json([
            'data' => $pet->fresh()->load('animalSection'),
            'message' => 'Foto actualizada exitosamente'
        ]);
    }

    /**
     * Eliminar foto de mascota
     */
    public function deletePhoto($id)
    {
        $pet = Pet::where('user_id', Auth::id())->findOrFail($id);

        if ($pet->photo && !str_starts_with($pet->photo, 'http')) {
            Storage::disk('public')->delete($pet->photo);
        }

        $pet->update(['photo' => null]);

        return response()->json([
            'data' => $pet->fresh()->load('animalSection'),
            'message' => 'Foto eliminada exitosamente'
        ]);
    }

    /**
     * Obtener categorías de animales disponibles para mascotas
     */
    public function getAnimalCategories()
    {
        $categories = AnimalSection::where('is_active', true)
            ->orderBy('order')
            ->select('id', 'name', 'icon', 'slug')
            ->get();

        return response()->json([
            'data' => $categories
        ]);
    }
}
