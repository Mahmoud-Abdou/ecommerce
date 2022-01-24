<?php

namespace App\Repositories;

use App\Models\PromoCode;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class PromoCodeRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method PromoCode findWithoutFail($id, $columns = ['*'])
 * @method PromoCode find($id, $columns = ['*'])
 * @method PromoCode first($columns = ['*'])
*/
class PromoCodeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'code',
        'value',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PromoCode::class;
    }
}
