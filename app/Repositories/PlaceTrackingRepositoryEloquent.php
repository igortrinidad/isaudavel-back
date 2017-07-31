<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PlaceTrackingRepository;
use App\Models\PlaceTracking;
use App\Validators\PlaceTrackingValidator;

/**
 * Class PlaceTrackingRepositoryEloquent
 * @package namespace App\Repositories;
 */
class PlaceTrackingRepositoryEloquent extends BaseRepository implements PlaceTrackingRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PlaceTracking::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return PlaceTrackingValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
