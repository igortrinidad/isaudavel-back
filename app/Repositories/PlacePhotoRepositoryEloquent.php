<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PlacePhotoRepository;
use App\Models\PlacePhoto;
use App\Validators\PlacePhotoValidator;

/**
 * Class PlacePhotoRepositoryEloquent
 * @package namespace App\Repositories;
 */
class PlacePhotoRepositoryEloquent extends BaseRepository implements PlacePhotoRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PlacePhoto::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
