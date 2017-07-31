<?php

namespace App\Presenters;

use App\Transformers\ReservationInterestTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ReservationInterestPresenter
 *
 * @package namespace App\Presenters;
 */
class ReservationInterestPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ReservationInterestTransformer();
    }
}
