<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryRepository extends BaseRepository
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function getAllActive()
    {
        // Cachear categorías por 1 hora (se invalida en create/update/delete)
        return Cache::remember('categories.all.active', 3600, function () {
            return $this->model->active()
                ->with('parent')
                ->orderBy('order')
                ->get();
        });
    }

    public function getParentCategories()
    {
        // Cachear categorías padre con hijos por 1 hora
        return Cache::remember('categories.parents.with.children', 3600, function () {
            return $this->model->active()
                ->parent()
                ->with('children')
                ->orderBy('order')
                ->get();
        });
    }

    public function getBySlug($slug)
    {
        return $this->model->active()
            ->where('slug', $slug)
            ->with(['children', 'products' => function($query) {
                $query->active()->with('primaryImage');
            }])
            ->firstOrFail();
    }

    public function getCategoryWithProducts($id, $perPage = 15)
    {
        return $this->model->active()
            ->with(['products' => function($query) use ($perPage) {
                $query->active()->with('primaryImage')->paginate($perPage);
            }])
            ->findOrFail($id);
    }

    /**
     * Invalidar caché de categorías al crear/actualizar/eliminar
     */
    public function create(array $data)
    {
        $category = parent::create($data);
        $this->clearCache();
        return $category;
    }

    public function update($id, array $data)
    {
        $category = parent::update($id, $data);
        $this->clearCache();
        return $category;
    }

    public function delete($id)
    {
        $result = parent::delete($id);
        $this->clearCache();
        return $result;
    }

    protected function clearCache()
    {
        Cache::forget('categories.all.active');
        Cache::forget('categories.parents.with.children');
    }
}
