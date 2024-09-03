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

    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         $filters = $request->only(['author','category_id','available']);
        $perPage = $request->input('per_page', 15);
        $books= $this->bookService->listAllBooks($filters,$perPage);
        return ApiResponseService::paginated($books,'books retreive success');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBookRequest $request)
    {
        $data = $request->validated();
        $book = $this->bookService->createBook($data);
        return ApiResponseService::success($book,'book created success',201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $this->bookService->getBook($book);
        return ApiResponseService::success(new BookResource($book),'book retrieve success');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
       $data = $request->validated();
       $book = $this->bookService->updateBook($data,$book);
       return ApiResponseService::success(new BookResource($book),'update success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $this->bookService->deleteBook($book);
        return ApiResponseService::success(null,'delete success');

    }
}
