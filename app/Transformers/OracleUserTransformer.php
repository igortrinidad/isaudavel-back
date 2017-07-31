<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\OracleUser;

/**
 * Class OracleUserTransformer
 * @package namespace App\Transformers;
 */
class OracleUserTransformer extends TransformerAbstract
{

    /**
     * Transform the \OracleUser entity
     * @param \OracleUser $model
     *
     * @return array
     */
    public function transform(OracleUser $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
