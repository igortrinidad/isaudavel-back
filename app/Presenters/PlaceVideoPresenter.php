<?php

namespace App\Presenters;

use App\Transformers\PlaceVideoTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class PlaceVideoPresenter
 *
 * @package namespace App\Presenters;
 */
class PlaceVideoPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new PlaceVideoTransformer();
    }
}
