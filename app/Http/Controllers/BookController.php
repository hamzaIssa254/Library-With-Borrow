<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Services\BookService;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BookResource;
use App\Services\ApiResponseService;
use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\UpdateBookRequest;

class BookController extends Controller
{
    /**
     * Summary of bookService
     * @var
     */
    protected $bookService;
    /**
     * Summary of __construct
     * @param \App\Services\BookService $bookService
     */
    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }
    /**
     * Summary of index
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
         $filters = $request->only(['author','category_id','available']);
        $perPage = $request->input('per_page', 15);
        $books= $this->bookService->listAllBooks($filters,$perPage);
        return ApiResponseService::paginated(BookResource::collection($books),'books retreive success');

    }

    /**
     * Summary of store
     * @param \App\Http\Requests\CreateBookRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateBookRequest $request)
    {
        $data = $request->validated();
        $book = $this->bookService->createBook($data);
        return ApiResponseService::success($book,'book created success',201);
    }

    /**
     * Summary of show
     * @param \App\Models\Book $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Book $book)
    {
        $this->bookService->getBook($book);
        return ApiResponseService::success(new BookResource($book),'book retrieve success');
    }

    /**
     * Summary of update
     * @param \App\Http\Requests\UpdateBookRequest $request
     * @param \App\Models\Book $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
       $data = $request->validated();
       $book = $this->bookService->updateBook($data,$book);
       return ApiResponseService::success(new BookResource($book),'update success');
    }

    /**
     * Summary of destroy
     * @param \App\Models\Book $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Book $book)
    {
        $this->bookService->deleteBook($book);
        return ApiResponseService::success(null,'delete success');

    }
}
