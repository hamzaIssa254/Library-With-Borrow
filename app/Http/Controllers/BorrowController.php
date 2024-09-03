<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use Illuminate\Http\Request;
use App\Services\BorrowService;
use App\Services\ApiResponseService;
use App\Http\Requests\CreateBorrowRequest;
use App\Http\Requests\UpdateBorrowRequest;

class BorrowController extends Controller
{
    protected $borrowService;

    public function __construct(BorrowService $borrowService)
    {
        $this->borrowService = $borrowService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['user_id','book_id']);
        $perPage = $request->input('per_page', 15);
        $borrows= $this->borrowService->listAllBorrow($filters,$perPage);
        return ApiResponseService::paginated($borrows,'borrows retreive success');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBorrowRequest $request)
    {
        $data = $request->validated();
        $borrow = $this->borrowService->createBorrow($data);
        return ApiResponseService::success($borrow,'borrow created success',201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $borrow = $this->borrowService->showBorrow($id);
        return ApiResponseService::success($borrow,'retrievw success');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBorrowRequest $request, Borrow $borrow)
    {
        $data = $request->validated();
        $borrow = $this->borrowService->updateBorrow($data,$borrow);
        return ApiResponseService::success($borrow,'update success');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrow $borrow)
    {
        $this->borrowService->deleteBorrow($borrow);
        return ApiResponseService::success(null,'delete success');

    }

    public function retrieveBook(Borrow $borrow)
    {
        $this->borrowService->returnBook($borrow);
        return ApiResponseService::success(null,'book returned success');

    }
}
