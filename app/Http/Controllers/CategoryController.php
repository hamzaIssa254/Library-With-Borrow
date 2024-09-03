<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Services\ApiResponseService;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        // $this->authorize('admin');

        $this->categoryService = $categoryService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $perPage = $request->input('per_page', 15);
        $categories = $this->categoryService->listAllCategories($perPage);
        return ApiResponseService::paginated(CategoryResource::collection($categories),'categories retrieves success');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request)
    {
        $data = $request->validated();
       $category =$this->categoryService->createCategory($data);
       return ApiResponseService::success(new CategoryResource($category),'category created success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $this->categoryService->getCategory($category);
        return ApiResponseService::success(new CategoryResource($category),'get category success');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request,Category $category)
    {
        $data = $request->validated();
        $category = $this->categoryService->updateCategory($data,$category);
        return ApiResponseService::success(new CategoryResource($category),'category update success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
     $this->categoryService->deleteCategory($category);
     return ApiResponseService::success(null,'category delete success',201);
    }
}
