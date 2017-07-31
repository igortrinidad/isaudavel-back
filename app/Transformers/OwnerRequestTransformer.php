<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\OwnerRequest;

/**
 * Class OwnerRequestTransformer
 * @package namespace App\Transformers;
 */
class OwnerRequestTransformer extends TransformerAbstract
{

    /**
     * Transform the \OwnerRequest entity
     * @param \OwnerRequest $model
     *
     * @return array
     */
    public function transform(OwnerRequest $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
