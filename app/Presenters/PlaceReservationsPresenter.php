<?php

namespace App\Presenters;

use App\Transformers\PlaceReservationsTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class PlaceReservationsPresenter
 *
 * @package namespace App\Presenters;
 */
class PlaceReservationsPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new PlaceReservationsTransformer();
    }
}
