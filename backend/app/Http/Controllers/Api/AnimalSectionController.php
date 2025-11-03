<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AnimalSection;
use App\Http\Resources\AnimalSectionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AnimalSectionController extends Controller
{
    /**
     * Listar secciones (Público - solo activas, Admin - todas)
     */
    public function index(Request $request)
    {
        $query = AnimalSection::query()->ordered();

        // Si el usuario es admin/manager, mostrar todas. Si no, solo activas
        $user = $request->user();
        if (!$user || !in_array($user->role, ['admin', 'manager'])) {
            $query->active();
        }

        // Opción para incluir conteo de categorías
        if ($request->get('with_categories_count')) {
            $query->withCount('categories');
        }

        // Opción para cargar categorías completas
        if ($request->get('with_categories')) {
            $query->with(['categories' => function($q) {
                $q->active()->orderBy('order');
            }]);
        }

        $sections = $query->get();

        return AnimalSectionResource::collection($sections);
    }

    /**
     * Admin: Crear nueva sección
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:animal_sections,slug',
            'icon' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Generar slug si no se proporciona
        if (!isset($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $section = AnimalSection::create($validated);

        return response()->json([
            'data' => new AnimalSectionResource($section),
            'message' => 'Sección creada exitosamente'
        ], 201);
    }

    /**
     * Mostrar una sección específica
     */
    public function show($slug)
    {
        $section = AnimalSection::where('slug', $slug)
            ->with(['categories' => function($q) {
                $q->active()->orderBy('order');
            }])
            ->firstOrFail();

        return new AnimalSectionResource($section);
    }

    /**
     * Admin: Actualizar sección
     */
    public function update(Request $request, $id)
    {
        $section = AnimalSection::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'nullable|string|unique:animal_sections,slug,' . $id,
            'icon' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Generar slug si se cambió el nombre pero no se proporcionó slug
        if (isset($validated['name']) && !isset($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $section->update($validated);

        return response()->json([
            'data' => new AnimalSectionResource($section),
            'message' => 'Sección actualizada exitosamente'
        ]);
    }

    /**
     * Admin: Eliminar sección
     */
    public function destroy($id)
    {
        $section = AnimalSection::findOrFail($id);

        // Verificar si tiene categorías asociadas
        if ($section->categories()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar una sección que tiene categorías asociadas. Elimina o reasigna las categorías primero.'
            ], 400);
        }

        $section->delete();

        return response()->json([
            'message' => 'Sección eliminada exitosamente'
        ]);
    }

    /**
     * Admin: Alternar estado activo/inactivo
     */
    public function toggleStatus($id)
    {
        $section = AnimalSection::findOrFail($id);
        $section->is_active = !$section->is_active;
        $section->save();

        return response()->json([
            'data' => new AnimalSectionResource($section),
            'message' => $section->is_active
                ? 'Sección activada. Ahora es visible en el frontend.'
                : 'Sección desactivada. Ya no es visible en el frontend.'
        ]);
    }

    /**
     * Admin: Estadísticas de secciones
     */
    public function stats()
    {
        $totalSections = AnimalSection::count();
        $activeSections = AnimalSection::active()->count();

        $sectionsWithCounts = AnimalSection::withCount('categories')
            ->ordered()
            ->get()
            ->map(function($section) {
                return [
                    'id' => $section->id,
                    'name' => $section->name,
                    'icon' => $section->icon,
                    'is_active' => $section->is_active,
                    'categories_count' => $section->categories_count,
                ];
            });

        return response()->json([
            'total_sections' => $totalSections,
            'active_sections' => $activeSections,
            'inactive_sections' => $totalSections - $activeSections,
            'sections' => $sectionsWithCounts,
        ]);
    }

    /**
     * Admin: Reordenar secciones
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'required|exists:animal_sections,id',
            'sections.*.order' => 'required|integer|min:0',
        ]);

        foreach ($validated['sections'] as $sectionData) {
            AnimalSection::where('id', $sectionData['id'])
                ->update(['order' => $sectionData['order']]);
        }

        return response()->json([
            'message' => 'Orden de secciones actualizado exitosamente'
        ]);
    }
}
