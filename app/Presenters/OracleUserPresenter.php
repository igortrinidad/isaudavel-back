<?php

namespace App\Presenters;

use App\Transformers\OracleUserTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class OracleUserPresenter
 *
 * @package namespace App\Presenters;
 */
class OracleUserPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new OracleUserTransformer();
    }
}
