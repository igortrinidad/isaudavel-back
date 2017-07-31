<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\UserRepository;
use App\Models\User;
use App\Validators\UserValidator;

/**
 * Class UserRepositoryEloquent
 * @package namespace App\Repositories;
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
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
