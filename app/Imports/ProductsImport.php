<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductTranslate;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use App\Repositories\UploadRepository;
use File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductsImport implements ToCollection
{
    private $uploadRepository;
    public function set_repo($uploadRepo)
    {
        $this->uploadRepository = $uploadRepo;   
    }

    private $category_id;
    private $market_id;
    private $language_id;

    public function set_category_id($id)
    {
        $this->category_id = $id;
    }

    public function set_market_id($id)
    {
        $this->market_id = $id;
    }
    public function set_language_id($language_id)
    {
        $this->language_id = $language_id;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection  $rows)
    {
        $i=0;
        foreach ($rows as $row) {
            if($i == 0){
                $i++;
                continue;
            }
            if($row[0] == null)
                return ;
            $product = Product::create([
                'discount_price' => $row[4],
                'price' => $row[5] == null ? 0.1 : $row[5],
                'capacity' => $row[6],
                'package_items_count' => $row[7],
                'unit' => $row[8],
                'featured' => $row[9],
                'deliverable' => $row[10],
                'market_id' => $this->market_id,
                'category_id' => $this->category_id
            ]);
            if(isset($row[11]) && $row[11] != null){
                self::store_image($row[11], $row[12], $product);
            }
            ProductTranslate::create([
                'name' => $row[0],
                'description' => $row[2],
                'product_id' => $product->id,
                'language_id' =>1
            ]);
            ProductTranslate::create([
                'name' => $row[1],
                'description' => $row[3],
                'product_id' => $product->id,
                'language_id' => 2
            ]);
        }
    }

    public function store_image($image_path, $image_name, $product)
    {
        $input['uuid'] = Str::uuid()->toString();
        $input['field'] = "image";
        $path = storage_path($image_path);
      
        $isExists = File::exists($path);
        if($isExists){
             $file = new UploadedFile($path, $image_name, 'image/png');
             $input['file'] = $file;

             try {
             $upload = $this->uploadRepository->create($input);
             $upload->addMedia($input['file'])
             ->withCustomProperties(['uuid' => $input['uuid']])
             ->toMediaCollection($input['field']);
             } catch (ValidatorException $e) {
             Flash::error($e->getMessage());
             }

             $cacheUpload = $this->uploadRepository->getByUuid($input['uuid']);
             $mediaItem = $cacheUpload->getMedia('image')->first();
             $mediaItem->copy($product, 'image');
        }
       
    }
}