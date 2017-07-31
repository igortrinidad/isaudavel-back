<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\PlaceVideo;

/**
 * Class PlaceVideoTransformer
 * @package namespace App\Transformers;
 */
class PlaceVideoTransformer extends TransformerAbstract
{

    /**
     * Transform the \PlaceVideo entity
     * @param \PlaceVideo $model
     *
     * @return array
     */
    public function transform(PlaceVideo $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
