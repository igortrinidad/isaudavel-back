<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\PlaceTracking;

/**
 * Class PlaceTrackingTransformer
 * @package namespace App\Transformers;
 */
class PlaceTrackingTransformer extends TransformerAbstract
{

    /**
     * Transform the \PlaceTracking entity
     * @param \PlaceTracking $model
     *
     * @return array
     */
    public function transform(PlaceTracking $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
