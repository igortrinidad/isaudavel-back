<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PlaceVideoRepository;
use App\Models\PlaceVideo;
use App\Validators\PlaceVideoValidator;

/**
 * Class PlaceVideoRepositoryEloquent
 * @package namespace App\Repositories;
 */
class PlaceVideoRepositoryEloquent extends BaseRepository implements PlaceVideoRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PlaceVideo::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return PlaceVideoValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
