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
    /**
     * Summary of borrowService
     * @var
     */
    protected $borrowService;
    /**
     * Summary of __construct
     * @param \App\Services\BorrowService $borrowService
     */
    public function __construct(BorrowService $borrowService)
    {
        $this->borrowService = $borrowService;
    }
    /**
     * Summary of index
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $filters = $request->only(['user_id','book_id']);
        $perPage = $request->input('per_page', 15);
        $borrows= $this->borrowService->listAllBorrow($filters,$perPage);
        return ApiResponseService::paginated($borrows,'borrows retreive success');
    }

    /**
     * Summary of store
     * @param \App\Http\Requests\CreateBorrowRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateBorrowRequest $request)
    {
        $data = $request->validated();
        $borrow = $this->borrowService->createBorrow($data);
        return ApiResponseService::success($borrow,'borrow created success',201);
    }

    /**
     * Summary of show
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $borrow = $this->borrowService->showBorrow($id);
        return ApiResponseService::success($borrow,'retrievw success');
    }

    /**
     * Summary of update
     * @param \App\Http\Requests\UpdateBorrowRequest $request
     * @param \App\Models\Borrow $borrow
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateBorrowRequest $request, Borrow $borrow)
    {
        $data = $request->validated();
        $borrow = $this->borrowService->updateBorrow($data,$borrow);
        return ApiResponseService::success($borrow,'update success');

    }

    /**
     * Summary of destroy
     * @param \App\Models\Borrow $borrow
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Borrow $borrow)
    {
        $this->borrowService->deleteBorrow($borrow);
        return ApiResponseService::success(null,'delete success');

    }
    /**
     * Summary of retrieveBook
     * @param \App\Models\Borrow $borrow
     * @return \Illuminate\Http\JsonResponse
     */
    public function retrieveBook(Borrow $borrow)
    {
        $this->borrowService->returnBook($borrow);
        return ApiResponseService::success(null,'book returned success');

    }
}
