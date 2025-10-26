<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository extends BaseRepository
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function getAllActive()
    {
        return $this->model->active()
            ->with('parent')
            ->orderBy('order')
            ->get();
    }

    public function getParentCategories()
    {
        return $this->model->active()
            ->parent()
            ->with('children')
            ->orderBy('order')
            ->get();
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
}
