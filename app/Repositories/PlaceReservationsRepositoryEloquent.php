<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PlaceReservationsRepository;
use App\Models\PlaceReservations;
use App\Validators\PlaceReservationsValidator;

/**
 * Class PlaceReservationsRepositoryEloquent
 * @package namespace App\Repositories;
 */
class PlaceReservationsRepositoryEloquent extends BaseRepository implements PlaceReservationsRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PlaceReservations::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return PlaceReservationsValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
