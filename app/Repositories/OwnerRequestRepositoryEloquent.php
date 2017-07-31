<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ownerRequestRepository;
use App\Models\OwnerRequest;
use App\Validators\OwnerRequestValidator;

/**
 * Class OwnerRequestRepositoryEloquent
 * @package namespace App\Repositories;
 */
class OwnerRequestRepositoryEloquent extends BaseRepository implements OwnerRequestRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return OwnerRequest::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return OwnerRequestValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
