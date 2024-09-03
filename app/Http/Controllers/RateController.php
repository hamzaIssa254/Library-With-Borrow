<?php

namespace App\Http\Controllers;

use App\Http\Requests\RatingRequest;
use App\Http\Requests\UpdateRatingRequest;
use App\Models\Rate;
use Illuminate\Http\Request;
use App\Services\RatingService;
use App\Services\ApiResponseService;
use App\Http\Resources\RatingResource;

class RateController extends Controller
{
     /**
     * Summary of ratingservice
     * @var
     */
    protected $ratingservice;
    /**
     * Summary of __construct
     * @param \App\Services\RatingService $rating
     */
    public function __construct(RatingService $rating)
    {
        $this->ratingservice = $rating;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $rates = $this->ratingservice->getAllRates($perPage);
        return ApiResponseService::paginated(RatingResource::collection($rates),'rates retrieve success');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RatingRequest $request)
    {
        $data = $request->validated();
        $rate = $this->ratingservice->createRating($data);
        return ApiResponseService::success(new RatingResource($rate),'rate add success',201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rate $rate)
    {
     $this->ratingservice->getRate($rate);
     return ApiResponseService::success(new RatingResource($rate),'rate retrieve success');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRatingRequest $request, Rate $rate)
    {
       $data = $request->validated();
       $rate = $this->ratingservice->updateRate($rate,$data);
       return ApiResponseService::success($rate,'rate update success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rate $rate)
    {
        return $this->ratingservice->deleteRate($rate);
    }
}
