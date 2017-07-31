<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ownerRequestDocumentRepository;
use App\Models\OwnerRequestDocument;
use App\Validators\OwnerRequestDocumentValidator;

/**
 * Class OwnerRequestDocumentRepositoryEloquent
 * @package namespace App\Repositories;
 */
class OwnerRequestDocumentRepositoryEloquent extends BaseRepository implements OwnerRequestDocumentRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return OwnerRequestDocument::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return OwnerRequestDocumentValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
