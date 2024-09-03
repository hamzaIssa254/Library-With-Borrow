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
     * Summary of index
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $rates = $this->ratingservice->getAllRates($perPage);
        return ApiResponseService::paginated(RatingResource::collection($rates),'rates retrieve success');
    }

    /**
     * Summary of store
     * @param \App\Http\Requests\RatingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RatingRequest $request)
    {
        $data = $request->validated();
        $rate = $this->ratingservice->createRating($data);
        return ApiResponseService::success(new RatingResource($rate),'rate add success',201);
    }

    /**
     * Summary of show
     * @param \App\Models\Rate $rate
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Rate $rate)
    {
     $this->ratingservice->getRate($rate);
     return ApiResponseService::success(new RatingResource($rate),'rate retrieve success');

    }

    /**
     * Summary of update
     * @param \App\Http\Requests\UpdateRatingRequest $request
     * @param \App\Models\Rate $rate
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRatingRequest $request, Rate $rate)
    {
       $data = $request->validated();
       $rate = $this->ratingservice->updateRate($rate,$data);
       return ApiResponseService::success($rate,'rate update success');
    }

    /**
     * Summary of destroy
     * @param \App\Models\Rate $rate
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Rate $rate)
    {
        return $this->ratingservice->deleteRate($rate);
    }
}
