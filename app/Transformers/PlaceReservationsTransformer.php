<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\PlaceReservations;

/**
 * Class PlaceReservationsTransformer
 * @package namespace App\Transformers;
 */
class PlaceReservationsTransformer extends TransformerAbstract
{

    /**
     * Transform the \PlaceReservations entity
     * @param \PlaceReservations $model
     *
     * @return array
     */
    public function transform(PlaceReservations $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
