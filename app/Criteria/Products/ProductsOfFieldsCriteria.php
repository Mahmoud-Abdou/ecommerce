<?php

namespace App\Criteria\Products;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class ProductsOfFieldsCriteria.
 *
 * @package namespace App\Criteria\Products;
 */
class ProductsOfFieldsCriteria implements CriteriaInterface
{
    /**
     * @var array
     */
    private $request;

    /**
     * ProductsOfFieldsCriteria constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply criteria in query repository
     *
     * @param string $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        if (!$this->request->has('fields')) {
            return $model;
        } else {
            $fields = $this->request->get('fields');
            if (in_array('0', $fields)) { // mean all fields
                return $model;
            }
            return $model
                ->join("categories", "categories.id", "products.category_id")
                ->join("category_translates", "categories.id", "category_translates.category_id")
                ->where("category_translates.language_id", $this->request["lang"])
                ->whereIn("categories.id", $this->request->get('fields'))
                // ->join('market_fields', 'market_fields.market_id', '=', 'products.market_id')
                // ->whereIn('market_fields.field_id', $this->request->get('fields'))
                ->groupBy('products.id');
        }
    }
}
