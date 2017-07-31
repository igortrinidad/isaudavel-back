<?php

namespace App\Presenters;

use App\Transformers\OwnerRequestDocumentTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class OwnerRequestDocumentPresenter
 *
 * @package namespace App\Presenters;
 */
class OwnerRequestDocumentPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new OwnerRequestDocumentTransformer();
    }
}
