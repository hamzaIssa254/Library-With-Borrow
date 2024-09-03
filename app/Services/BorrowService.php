<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\Book;
use App\Models\Borrow;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Exception\JsonException;

class BorrowService
{
    public function listAllBorrow(array $filters,int $perPage)
    {

        // Generate a unique cache key based on filters and pagination
        $cacheKey = 'borrows_' . md5(json_encode($filters) . $perPage . request('page', 1));

        // Check if the cached result exists
        $borrows = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($filters,$perPage)
         {
            // Initialize the query builder for the book model
            $borrowsQuery = Borrow::query();

            // Apply user_id filter if provided
            if (isset($filters['user_id'])) {
                $borrowsQuery->where('user_id', $filters['user_id']);
            }

            // Apply book_id filter if provided
            if (isset($filters['book_id'])) {
                $borrowsQuery->where('book_id',$filters['book_id']);
            }





            $borrowsQuery->join('books', 'borrows.book_id', '=', 'books.id')->join('users','borrows.user_id','=','users.id')
            ->select([
                'borrows.book_id',
                'borrows.user_id',
                'borrows.borrowed_at',
                'borrows.due_date',
                'borrows.returned_at',
                'books.title as book_title',
                'users.name as user_name'
            ]);





            // Return the paginated result of the query
            return $borrowsQuery->paginate($perPage);
        });

        return $borrows;
    }

    public function createBorrow(array $data)
    {
        DB::beginTransaction();
        try
        {
       $this->create($data);
          DB::commit();

        }catch(Exception $e)
        {
            DB::rollBack();
            throw $e;
        }
    }

    private function create(array $data)
    {
        $today = Carbon::now();
        $dueDate = $today->copy()->addDays(14);
          $borrow =  Borrow::create([
            'borrowed_at' => $today,
            'returned_at' => $dueDate,
          'book_id' => $data['book_id'],
          'due_date' => $data['due_date'],

          ]);
          return $borrow;
    }




    public function returnBook(Borrow $borrow)
    {

        $borrow->returned_at = null;
        $borrow->due_date = null;
        $borrow->save();

        return $borrow;
    }
    public function showBorrow(int $id)
    {
        $borrow = Borrow::findOrFail($id);
        return $borrow;
    }

    public function updateBorrow(array $data,Borrow $borrow)
    {

            $borrow->update(array_filter($data));

            return $borrow;



    }

    public function deleteBorrow(Borrow $borrow)
    {
        $borrow->delete();

    }



}
