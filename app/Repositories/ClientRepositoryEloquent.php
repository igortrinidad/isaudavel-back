<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ClientRepository;
use App\Models\Client;
use App\Validators\ClientValidator;

/**
 * Class ClientRepositoryEloquent
 * @package namespace App\Repositories;
 */
class ClientRepositoryEloquent extends BaseRepository implements ClientRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Client::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return ClientValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function create(array $attributes)
    {
        if(array_key_exists('password', $attributes)){
            $attributes['password'] = bcrypt($attributes['password']);
        }
        return parent::create($attributes);
    }

    public function update(array $attributes, $id)
    {
        if(array_key_exists('password', $attributes)){
            $attributes['password'] = bcrypt($attributes['password']);
        }

        return parent::update($attributes, $id);
    }
}
