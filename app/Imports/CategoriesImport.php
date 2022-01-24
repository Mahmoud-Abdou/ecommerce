<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\CategoryTranslate;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use App\Repositories\UploadRepository;
use File;
use Illuminate\Http\UploadedFile;

class CategoriesImport implements ToCollection
{
    private $uploadRepository;
    public function set_repo($uploadRepo)
    {
        $this->uploadRepository = $uploadRepo;   
    }
    
    private $language_id;
    public function set_language_id($language_id)
    {
        $this->language_id = $language_id;
    }
    private $parent_id;
    public function set_parent_id($parent_id)
    {
        $this->parent_id = $parent_id;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Collection|null
    */
    public function collection(Collection $rows)
    {
        $i = 0;
        foreach ($rows as $row) {
            if($i == 0){
                $i++;
                continue;
            }
            if($row[0] == null)
                return ;
            $category = Category::create([
                'parent_id' => $this->parent_id,
            ]);

            if(isset($row[4]) && $row[4] != null){
                self::store_image($row[4], $row[5], $category);
            }
            CategoryTranslate::create([
                'name' => $row[0],
                'description' => $row[2],
                'category_id' => $category->id,
                'language_id' => 1
            ]);
            CategoryTranslate::create([
                'name' => $row[1],
                'description' => $row[3],
                'category_id' => $category->id,
                'language_id' => 2
            ]);
        }
    }

    public function store_image($image_path, $image_name, $category)
    {
        $input['uuid'] = Str::uuid()->toString();
        $input['field'] = "image";
        $path = storage_path($image_path);
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
        $mediaItem->copy($category, 'image');
    }
}
