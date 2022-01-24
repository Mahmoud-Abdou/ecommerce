<?php

namespace App\Models;

use App;
use Eloquent as Model;

/**
 * Class Orderstate
 * @package App\Models
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @property string state
 */
class OrderStatus extends Model
{
    public $table = 'order_statuses';
    


    public $fillable = [
        'state'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'state' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'state' => 'required'
    ];

    // protected $maps = [
    //     'state'
    // ];

    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'custom_fields',
        'status',
        
    ];

    public function customFieldsValues()
    {
        return $this->morphMany('App\Models\CustomFieldValue', 'customizable');
    }

    public function getStatusAttribute()
    {
        App::setlocale(strtolower(request()->header('language') == null ? setting('language') : request()->header('language')));
        return __("lang.".$this->state);
    }
    public function getCustomFieldsAttribute()
    {
        $hasCustomField = in_array(static::class, setting('custom_field_models', []));
        if (!$hasCustomField) {
            return [];
        }
        $array = $this->customFieldsValues()
            ->join('custom_fields', 'custom_fields.id', '=', 'custom_field_values.custom_field_id')
            ->where('custom_fields.in_table', '=', true)
            ->get()->toArray();

        return convertToAssoc($array, 'name');
    }
}
