<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PlaceDocumentRepository;
use App\Models\PlaceDocument;
use App\Validators\PlaceDocumentValidator;

/**
 * Class PlaceDocumentRepositoryEloquent
 * @package namespace App\Repositories;
 */
class PlaceDocumentRepositoryEloquent extends BaseRepository implements PlaceDocumentRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PlaceDocument::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return PlaceDocumentValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
