<?php

namespace App\Services;

use Exception;
use App\Models\Book;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;



class RatingService
{

/**
 * Summary of createRating
 * @param array $data
 * @return Rate
 */
public function createRating(array $data)
{

    DB::beginTransaction();
    try{

        $rate = Rate::create($data);
        DB::commit();
        return $rate;

    }
    catch (Exception $e) {
        // Rollback the transaction on failure
        DB::rollBack();

        throw $e;
    }
}

public function getAllRates(int $perPage)
{
    // Generate a unique cache key based on filters and pagination
    $cacheKey = 'books_' . md5($perPage . request('page', 1));

    // Check if the cached result exists
    $rate = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($perPage)
     {
        $rateQuery = Rate::query();
        $rateQuery->join('books','rates.book_id','=','books.id')
        ->select('book_id','user_id','rating','books.title as book_title');

        return $rateQuery->paginate($perPage);

     });
     return $rate;
}

public function getRate(Rate $rate)
{
    return $rate;
}

public function updateRate(Rate $rate,array $data)
{

    $rate->update(array_filter($data));
    return $rate;
}

public function deleteRate(Rate $rate)
{  if ($rate->user_id == Auth::user()->id) {
    $rate->delete();
    return ApiResponseService::success(null, 'Rate deleted successfully', 200);
} else {
    return ApiResponseService::error('It is not your rate to delete it', 403);
}

}

}
