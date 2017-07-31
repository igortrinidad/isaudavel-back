<?php

namespace App\Presenters;

use App\Transformers\PlaceTrackingTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class PlaceTrackingPresenter
 *
 * @package namespace App\Presenters;
 */
class PlaceTrackingPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new PlaceTrackingTransformer();
    }
}
