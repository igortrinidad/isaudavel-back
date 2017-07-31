<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\ReservationInterest;

/**
 * Class ReservationInterestTransformer
 * @package namespace App\Transformers;
 */
class ReservationInterestTransformer extends TransformerAbstract
{

    /**
     * Transform the \ReservationInterest entity
     * @param \ReservationInterest $model
     *
     * @return array
     */
    public function transform(ReservationInterest $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
