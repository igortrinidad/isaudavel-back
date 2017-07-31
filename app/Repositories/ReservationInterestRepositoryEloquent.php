<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ReservationInterestRepository;
use App\Models\ReservationInterest;
use App\Validators\ReservationInterestValidator;

/**
 * Class ReservationInterestRepositoryEloquent
 * @package namespace App\Repositories;
 */
class ReservationInterestRepositoryEloquent extends BaseRepository implements ReservationInterestRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ReservationInterest::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return ReservationInterestValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
