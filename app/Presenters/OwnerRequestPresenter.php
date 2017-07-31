<?php

namespace App\Presenters;

use App\Transformers\OwnerRequestTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class OwnerRequestPresenter
 *
 * @package namespace App\Presenters;
 */
class OwnerRequestPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new OwnerRequestTransformer();
    }
}
