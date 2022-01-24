<?php

namespace App\Models;

use Eloquent as Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

/**
 * Class Category
 * @package App\Models
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection Product
 * @property string name
 * @property string description
 */
class Category extends Model implements HasMedia
{
    use HasMediaTrait {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }

    public $table = 'categories';
    

    public $fillable = [

        'parent_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'name' => 'string',
        // 'description' => 'string',
        'image' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'description' => 'required'
    ];

    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'custom_fields',
        'name',
        // 'description',
        'has_media',
        'pdatedat'
    ];
    public function getPdatedatAttribute()
    {
        return getDateColumn($this, 'updated_at');
        ;
    }
    public function getNameAttribute()
    {
        if (request()->is('api/*')) {
            $language = request()->header('language');
            $language_id = 2;
            if (request()->headers->has('language')) {
                if ($language == 'AR' || $language == 'ar' || $language == 'Ar' || $language == 'aR') {
                    $this->language = 'arabic';
                    $this->language_id = 2;
                } elseif ($language == 'EN' || $language == 'en' || $language == 'En' || $language == 'eN') {
                    $this->language = 'english';
                    $this->language_id = 1;
                } else {
                    $this->language = 'english';
                    $this->language_id = 1;
                }
            } else {
                $this->language = 'english';
                $this->language_id = 1;
            }
        } else {
            if (session()->get('data_language') =='ar') {
                $this->language_id = 2;
            } elseif (session()->get('data_language') == 'en') {
                $this->language_id = 1;
            } elseif (setting('data_language') == 'ar') {
                $this->language_id = 2;
            } elseif (setting('data_language') == 'en') {
                $this->language_id = 1;
            } else {
                $this->language_id = 1;
            }
        }
        $name= CategoryTranslate::where("category_id", $this->id)->where("language_id", $this->language_id)->first();
        return isset($name->name)?$name->name:"not tranlsalted yet";
    }
    /**
     * @param Media|null $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 200, 200)
            ->sharpen(10);

        $this->addMediaConversion('icon')
            ->fit(Manipulations::FIT_CROP, 100, 100)
            ->sharpen(10);
    }

    public function customFieldsValues()
    {
        return $this->morphMany('App\Models\CustomFieldValue', 'customizable');
    }

    /**
     * to generate media url in case of fallback will
     * return the file type icon
     * @param string $conversion
     * @return string url
     */
    public function getFirstMediaUrl($collectionName = 'default', $conversion = '')
    {
        $url = $this->getFirstMediaUrlTrait($collectionName);
        $array = explode('.', $url);
        $extension = strtolower(end($array));
        if (in_array($extension, config('medialibrary.extensions_has_thumb'))) {
            return asset($this->getFirstMediaUrlTrait($collectionName, $conversion));
        } else {
            return asset(config('medialibrary.icons_folder').'/'.$extension.'.png');
        }
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

    /**
     * Add Media to api results
     * @return bool
     */
    public function getHasMediaAttribute()
    {
        return $this->hasMedia('image') ? true : false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function products()
    {
        return $this->hasMany(\App\Models\Product::class, 'category_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function category_translates()
    {
        return $this->hasMany(\App\Models\CategoryTranslate::class, 'category_id');
    }
}
