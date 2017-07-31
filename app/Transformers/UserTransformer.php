<?php
namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract as Transformer;
/**
 * Class UserTransformer.
 */
class UserTransformer extends Transformer
{
    /**
     * Transform (normalize) the User class for api resources.
     *
     * @param User $user The user instance
     *
     * @return array Normalized array with user's data
     */
    public function transform(User $user)
    {
        $avaliableFields = ['id','name','last_name', 'full_name', 'email', 'created_at','updated_at', 'blank_password', 'social_providers', 'role'];

        return array_only($user->toArray(), $avaliableFields);

    }
}