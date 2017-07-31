<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\OwnerRequestDocument;

/**
 * Class OwnerRequestDocumentTransformer
 * @package namespace App\Transformers;
 */
class OwnerRequestDocumentTransformer extends TransformerAbstract
{

    /**
     * Transform the \OwnerRequestDocument entity
     * @param \OwnerRequestDocument $model
     *
     * @return array
     */
    public function transform(OwnerRequestDocument $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
