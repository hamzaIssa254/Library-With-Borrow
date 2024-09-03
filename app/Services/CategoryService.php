<?php

namespace App\Services;

use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Exception;

class CategoryService
{
    public function listAllCategories(int $perPage)
    {
        $cacheKey = 'categories_' . md5( $perPage . request('page', 1));

        // Check if the cached result exists
        $categories = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($perPage)
         {
            $categoryQuery = Category::query();
            $categoryQuery->select('name');
            return $categoryQuery->paginate($perPage);

         });
         return $categories;
    }

    public function createCategory(array $data)
    {
        DB::beginTransaction();
        try
        {
           $category = Category::create($data);
            DB::commit();
            return $category;
        }catch(Exception $e)
        {
            DB::rollBack();
            throw $e;
        }
    }

    public function getCategory(Category $category)
    {
        return $category;
    }

    public function updateCategory(array $data,Category $category)
    {
        $category->update(array_filter($data));
        return $category;
    }

    public function deleteCategory(Category $category)
    {
        $category->forcedelete();

    }
}
