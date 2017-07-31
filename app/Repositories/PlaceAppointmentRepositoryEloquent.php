<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PlaceAppointmentRepository;
use App\Models\PlaceAppointment;
use App\Validators\PlaceAppointmentValidator;

/**
 * Class PlaceAppointmentRepositoryEloquent
 * @package namespace App\Repositories;
 */
class PlaceAppointmentRepositoryEloquent extends BaseRepository implements PlaceAppointmentRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PlaceAppointment::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return PlaceAppointmentValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
