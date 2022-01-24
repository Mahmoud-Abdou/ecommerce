<?php

namespace App\Http\Controllers;

use App\DataTables\CategoryDataTable;
use App\DataTables\SubCategoryDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Repositories\CategoryRepository;
use App\Repositories\CategoryTranslateRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use Flash;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

use App\Imports\CategoriesImport;
use Maatwebsite\Excel\Facades\Excel;

class CategoryController extends Controller
{
    /** @var  CategoryRepository */
    private $categoryRepository;

    /** @var  CategoryTranslateRepository */
    private $categoryTranslateRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
  * @var UploadRepository
  */
    private $uploadRepository;

    private $language_id;

    public function __construct(CategoryRepository $categoryRepo, CategoryTranslateRepository $categoryTranslateRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->categoryRepository = $categoryRepo;
        $this->categoryTranslateRepository = $categoryTranslateRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
    }

    /**
        * Get model and return the join o flanguages with language condition
        *
        *
        * @param $this->categoryRepository catigories, $language_id
        * @return $this->categoryRepository catigories
        */

    public function language_join($categories, $language_id)
    {
        // dd($language_id);

        $categories = $categories->join('category_translates', 'category_translates.category_id', 'categories.id')
                                ->where('category_translates.language_id', $language_id);
        return $categories;
    }
    public function set_lang()
    {
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
    /**
        * select the data with the main id not translate id
        *
        *
        * @param $this->categoryRepository catigories
        * @return $this->categoryRepository catigories
        */

    public function select_main_id($categories)
    {
        $categories = $categories->select('categories.*', 'category_translates.name', 'category_translates.description');
        return $categories;
    }
    public function newCategories(Request $request)
    {
        // order[0][column]: 2
        // order[0][dir]: asc
        $columns=array(
            0 =>"name",
            1 => "image",
            2 => "updated_at"
        );
        $tableorder=array(
            0 =>"category_translates",
            1 => "categories",
            2 => "categories"
        );
        self::set_lang();
        // dd($request);
        if ($request->start ==0) {
            $request['page']=1;
        } else {
            $request['page']= ($request->start / $request->length)+1;
        }
            
        $categories = self::language_join($this->categoryRepository, $this->language_id);
        $categories = self::select_main_id($categories);
        if ($request['id'] != null) {
            $categories = $categories->where("parent_id", $request['id']);
        } else {
            $categories = $categories->where("parent_id", 0);
        }
        
        if ($request['search']['value'] != null && $request['search']['value'] != 'null') {
            $categories = $categories->where('category_translates.name', 'like', '%'.$request['search']['value'].'%');
        }
        if (isset($request->order[0]) && $request->order[0]["column"] != 1) {
            $categories = $categories->orderBy($tableorder[$request->order[0]['column']].".".$columns[$request->order[0]['column']], $request->order[0]['dir']);
        }
        $count=$categories->count();
     
        $categories=$categories->paginate($request['length']);
        $newcats=[];
        foreach ($categories as $cat) {
            $cate=$cat;
            $cate->image=getMediaColumn($cat, 'image');
            $newcats[]=$cate;
        }
        $data["data"]=$newcats;
        $data["recordsTotal"] =  $count;
        $data["recordsFiltered"] = $count;
        
        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }
    /**
     * Display a listing of the Category.
     *
     * @param CategoryDataTable $categoryDataTable
     * @return Response
     */
    public function index(Request $request, CategoryDataTable $categoryDataTable)
    {
        self::set_lang();
        $idofcategeory=null;
        if ($request->id != null) {
            $idofcategeory=$request->id;
        }
        $categoryDataTable->set_language($this->language_id);
        $categories = $this->categoryRepository
            ->join('category_translates', 'category_translates.category_id', 'categories.id')
            ->where('category_translates.language_id', $this->language_id)
            ->select('category_translates.name', 'categories.id')->get();
        return view("categories.index", [
            "idofcategeory"=>$idofcategeory,
            'categories' => $categories
         ]);
        // return $categoryDataTable->render('categories.index', [
        //     'categories' => $categories
        // ]);
    }
    public function subcat($id, SubCategoryDataTable $subcategoryDataTable)
    {
        self::set_lang();

        $subcategoryDataTable->set_id($id);
        return $subcategoryDataTable->render('categories.subcat');
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
    public function create()
    {
        self::set_lang();

        $hasCustomField = in_array($this->categoryRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->categoryRepository->model());
            $html = generateCustomField($customFields);
        }
        $category = $this->categoryRepository;
        $category = self::language_join($category, $this->language_id);
        $category = self::select_main_id($category);
        $category = $category->pluck('id', 'name');
        $newcats=[];
        foreach ($category as $key=>$value) {
            $newcats[$value]= $key;
        }
        return view('categories.create', [
            "customFields"=> isset($html) ? $html : false,
            "categories"=> $newcats
            ])
        // ->with("category", $category)
        // ->with("customFields", isset($html) ? $html : false)
        ;
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateCategoryRequest $request)
    {
        self::set_lang();

        $input = $request->all();
        $input['language_id'] = $this->language_id;
        // dd($input);
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->categoryRepository->model());
        try {
            $category = $this->categoryRepository->create($input);
            $category->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
            
            $input['category_id'] = $category->id;
            $categoryTranslate = $this->categoryTranslateRepository->create($input);
            // $category->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
            
            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                if ($mediaItem != null) {
                    $mediaItem->copy($category, 'image');
                }
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.category')]));

        return redirect(route('categories.index'));
    }

    /**
     * Display the specified Category.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        self::set_lang();

        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('categories.index'));
        }

        return view('categories.show')->with('category', $category);
    }

    /**
     * Show the form for editing the specified Category.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        self::set_lang();

        $category_data = $this->categoryRepository;
        $category_data = self::language_join($category_data, $this->language_id);
        $category_data = $category_data->where('categories.id', $id);
        $category_data = self::select_main_id($category_data);
        $category_data = $category_data->first();
        
        $category = $this->categoryRepository->findWithoutFail($id);
        $categories = $this->categoryRepository;
        $categories = self::language_join($categories, $this->language_id);
        $categories = $categories->where("categories.id", "!=", $id)->pluck('category_translates.name', 'categories.id');

        if (empty($category)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.category')]));

            return redirect(route('categories.index'));
        }
        $customFieldsValues = $category->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->categoryRepository->model());
        $hasCustomField = in_array($this->categoryRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('categories.edit')
        ->with('category', $category_data)
        ->with('categories', $categories)
        ->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Update the specified Category in storage.
     *
     * @param  int              $id
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCategoryRequest $request)
    {
        self::set_lang();

        $category = $this->categoryRepository->findWithoutFail($id);
        
        $categoryTranslate = $this->categoryTranslateRepository->where('category_id', $id)->where('language_id', $this->language_id)->first();
        
        if (empty($category)) {
            Flash::error('Category not found');
            return redirect(route('categories.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->categoryRepository->model());
        try {
            $category = $this->categoryRepository->update($input, $id);
            $categoryTranslate = $this->categoryTranslateRepository->update($input, $categoryTranslate->id);
            
            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($category, 'image');
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $category->customFieldsValues()
                    ->updateOrCreate(['custom_field_id'=>$value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.category')]));

        return redirect(route('categories.index'));
    }

    /**
     * Remove the specified Category from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        self::set_lang();

        $category = $this->categoryRepository->findWithoutFail($id);

        $categoryTranslate = $this->categoryTranslateRepository->where('category_id', $id)->where('language_id', $this->language_id)->first();
        
        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('categories.index'));
        }

        $this->categoryRepository->delete($id);
        $this->categoryTranslateRepository->delete($categoryTranslate->id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.category')]));

        return redirect(route('categories.index'));
    }

    /**
     * Remove Media of Category
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        self::set_lang();

        $input = $request->all();
        $category = $this->categoryRepository->findWithoutFail($input['id']);
        try {
            if ($category->hasMedia($input['collection'])) {
                $category->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function import(Request $request)
    {
        self::set_lang();

        if (request()->file('file') == null || !request()->file('file')->isValid()) {
            return back();
        }
        
        $categoriesImport = new CategoriesImport;
        $categoriesImport->set_language_id($this->language_id);
        $categoriesImport->set_parent_id($request->parent_id);
        $categoriesImport->set_repo($this->uploadRepository);
        Excel::import($categoriesImport, request()->file('file'));
           
        return back();
    }

    /**
    * select the data with the main id not translate id
    *
    *
    * @param $this->categoryRepository catigories
    * @return $this->categoryRepository catigories
    */

    /**
     *
     */
    public function get_categories(Request $request)
    {
        self::set_lang();

        $categories = self::language_join($this->categoryRepository, $this->language_id);
        $categories = self::select_main_id($categories);
        if ($request['id'] != null) {
            $categories = $categories->where("parent_id", $request['id']);
        }
        
        return response()->json($categories->paginate(10), 200, [], JSON_UNESCAPED_UNICODE);
    }
}
