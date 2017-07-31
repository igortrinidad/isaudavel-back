<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PlaceCalendarSettingsRepository;
use App\Models\PlaceCalendarSettings;
use App\Validators\PlaceCalendarSettingsValidator;

/**
 * Class PlaceCalendarSettingsRepositoryEloquent
 * @package namespace App\Repositories;
 */
class PlaceCalendarSettingsRepositoryEloquent extends BaseRepository implements PlaceCalendarSettingsRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PlaceCalendarSettings::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return PlaceCalendarSettingsValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
