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
    /**
     * Summary of categoryService
     * @var
     */
    protected $categoryService;
    /**
     * Summary of __construct
     * @param \App\Services\CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {


        $this->categoryService = $categoryService;
    }
    /**
     * Summary of index
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        $perPage = $request->input('per_page', 15);
        $categories = $this->categoryService->listAllCategories($perPage);
        return ApiResponseService::paginated(CategoryResource::collection($categories),'categories retrieves success');

    }

    /**
     * Summary of store
     * @param \App\Http\Requests\CreateCategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateCategoryRequest $request)
    {
        $data = $request->validated();
       $category =$this->categoryService->createCategory($data);
       return ApiResponseService::success(new CategoryResource($category),'category created success');
    }

    /**
     * Summary of show
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Category $category)
    {
        $this->categoryService->getCategory($category);
        return ApiResponseService::success(new CategoryResource($category),'get category success');
    }

    /**
     * Summary of update
     * @param \App\Http\Requests\UpdateCategoryRequest $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCategoryRequest $request,Category $category)
    {
        $data = $request->validated();
        $category = $this->categoryService->updateCategory($data,$category);
        return ApiResponseService::success(new CategoryResource($category),'category update success');
    }

    /**
     * Summary of destroy
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Category $category)
    {
     $this->categoryService->deleteCategory($category);
     return ApiResponseService::success(null,'category delete success',201);
    }
}
