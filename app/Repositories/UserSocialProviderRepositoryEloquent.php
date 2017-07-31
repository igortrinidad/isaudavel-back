<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\UserSocialProviderRepository;
use App\Models\UserSocialProvider;
use App\Validators\UserSocialProviderValidator;

/**
 * Class UserSocialProviderRepositoryEloquent
 * @package namespace App\Repositories;
 */
class UserSocialProviderRepositoryEloquent extends BaseRepository implements UserSocialProviderRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return UserSocialProvider::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
