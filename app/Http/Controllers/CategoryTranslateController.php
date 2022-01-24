<?php

namespace App\Http\Controllers;

use App\DataTables\CategoryDataTable;
use App\DataTables\CategoryTranslateDataTable ;
use App\DataTables\SubCategoryDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Repositories\CategoryRepository;
use App\Repositories\CategoryTranslateRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use Flash;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class CategoryTranslateController extends Controller
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
        if (session()->get('data_language') =='ar') {
            $this->language_id = 2;
        } elseif (session()->get('data_language') == 'en') {
            $this->language_id = 1;
        } elseif (setting('language') == 'ar') {
            $this->language_id = 2;
        } elseif (setting('language') == 'en') {
            $this->language_id = 1;
        } else {
            $this->language_id = 1;
        }
    }

    /**
    * get all un translated languages with category id
    *
    *
    * @param $this->categoryTranslateRepository $category_translates, $language_id, $category_id
    * @return $this->categoryTranslateRepository catigories
    */

    public function gat_untranslated_languages($category_translates, $language_id, $category_id)
    {
        
        $category_translates = $category_translates->select('language_id')->where('category_id', $category_id)->get(); 
        $all_languages = DB::table('languages')->select('id', 'name')->whereNotIn('id', $category_translates)->get();
        
        return $all_languages;
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
        $categories = $categories->join('category_translates', 'category_translates.category_id', 'categories.id')
                                ->where('category_translates.language_id', $language_id);
        return $categories;
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

    /**
     * Display a listing of the Category.
     *
     * @param CategoryTranslateDataTable $categoryTranslateDataTable
     * @return Response
     */
    public function index(Request $request, CategoryTranslateDataTable $categoryTranslateDataTable)
    {
        if ($request->id != null) {
            $categoryTranslateDataTable->set_id($request->id);
        }
        
        $categoryTranslateDataTable->set_language($this->language_id);
        return $categoryTranslateDataTable->render('category_translates.index');
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
    public function create()
    {
        $category = $this->categoryRepository;
        $category = self::language_join($category, $this->language_id);
        $category = self::select_main_id($category);
        
        // dd(self::language_join($this->categoryRepository->findWithoutFail(12), 2)->get());

        $category = $category->pluck('name', 'categories.id');
        $hasCustomField = in_array($this->categoryRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->categoryRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('categories.create', [
            "customFields"=> isset($html) ? $html : false,
            "categories"=> $category
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
                $mediaItem->copy($category, 'image');
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
        // dd($id);
        $categoryTranslate = $this->categoryRepository->findWithoutFail($id);
        // $categoryTranslate = $categoryTranslate->pluck('name', 'id', 'description');
        $categoryTranslateLanguages = self::gat_untranslated_languages($this->categoryTranslateRepository, $this->language_id, $id);

        if (empty($categoryTranslate)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.category')]));

            return redirect(route('category_translation.index'));
        }
        $customFieldsValues = $categoryTranslate->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->categoryRepository->model());
        $hasCustomField = in_array($this->categoryRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('category_translates.edit')
        ->with('categoryTranslate', $categoryTranslate)
        ->with('categoryTranslateLanguages', $categoryTranslateLanguages)
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
        // dd($request);
        $category = $this->categoryRepository->findWithoutFail($id);
        
        if (empty($category)) {
            Flash::error('Category not found');
            return redirect(route('category_translation.index'));
        }
        $input = $request->all();
        $input['category_id'] = $id;
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->categoryRepository->model());
        try {
            $categoryTranslate = $this->categoryTranslateRepository->create($input);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.category_translates')]));

        return redirect(route('category_translation.index'));
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
}
