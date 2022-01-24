<?php

namespace App\Http\Controllers;

use App\DataTables\ProductTranslateDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateProducttranslateRequest;
use App\Http\Requests\UpdateProducttranslateRequest;
use App\Repositories\ProductRepository;
use App\Repositories\ProductTranslateRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use App\Repositories\MarketRepository;
use App\Repositories\CategoryRepository;
use Flash;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class ProductTranslateController extends Controller
{
    /** @var  ProductRepository */
    private $productRepository;
    /** @var  ProductTranslateRepository */
    private $productTranslateRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UploadRepository
     */
    private $uploadRepository;
    /**
     * @var MarketRepository
     */
    private $marketRepository;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    private $language_id;

    
          
    public function __construct(ProductRepository $productRepo, ProductTranslateRepository $productTranslateRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo, MarketRepository $marketRepo, CategoryRepository $categoryRepo)
    {
        parent::__construct();
        $this->productRepository = $productRepo;
        $this->productTranslateRepository = $productTranslateRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
        $this->marketRepository = $marketRepo;
        $this->categoryRepository = $categoryRepo;
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
    * get all un translated languages with product id
    *
    *
    * @param $this->productTranslateRepository $product_translates, $language_id, $product_id
    * @return $this->productTranslateRepository products
    */

    public function get_untranslated_languages($product_translates, $language_id, $product_id)
    {
        $product_translates = $product_translates->select('language_id')->where('product_id', $product_id)->get();
        $all_languages = DB::table('languages')->select('id', 'name')->whereNotIn('id', $product_translates)->get();
        
        return $all_languages;
    }

    /**
     * Display a listing of the Product.
     *
     * @param ProductDataTable $productDataTable
     * @return Response
     */
    public function index(ProductTranslateDataTable $productTranslateDataTable)
    {
        $productTranslateDataTable->set_language($this->language_id);
        return $productTranslateDataTable->render('product_translates.index');
    }

    /**
     * Show the form for creating a new Product.
     *
     * @return Response
     */
    public function create()
    {
        $category =
         $this->categoryRepository
            ->join('category_translates', 'category_translates.category_id', 'categories.id')
            ->where('category_translates.language_id', $this->language_id)
            ->where("parent_id", 0)->pluck('category_translates.name', 'categories.id');
        if (auth()->user()->hasRole('admin')) {
            $market = $this->marketRepository->pluck('name', 'id');
        } else {
            $market = $this->marketRepository->myMarkets()
            ->pluck('name', 'id');
        }
        $hasCustomField = in_array($this->productRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->productRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('products.create')->with("customFields", isset($html) ? $html : false)->with("market", $market)->with("category", $category);
    }
   
    /**
     * Store a newly created Product in storage.
     *
     * @param CreateProductRequest $request
     *
     * @return Response
     */
    public function store(CreateProducttranslateRequest $request)
    {
        if ($request->sub_category != null) {
            $request['category_id']= $request['sub_category'];
        }
    
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->productRepository->model());
        try {
            $product = $this->ProductTranslateRepository->create($input);
            $product->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($product, 'image');
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.product')]));

        return redirect(route('products.index'));
    }

    /**
     * Display the specified Product.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $product = $this->productRepository->findWithoutFail($id);

        if (empty($product)) {
            Flash::error('Product not found');

            return redirect(route('products.index'));
        }

        return view('products.show')->with('product', $product);
    }

    /**
     * Show the form for editing the specified Product.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $product = $this->productRepository->findWithoutFail($id);
     
        $productTranslateLanguages = self::get_untranslated_languages($this->productTranslateRepository, $this->language_id, $id);

        if (empty($product)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.product_translate')]));

            return redirect(route('product_translation.index'));
        }
        
        $customFieldsValues = $product->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->productTranslateRepository->model());
        $hasCustomField = in_array($this->productTranslateRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('product_translates.edit')
        ->with('product', $product)
        ->with("customFields", isset($html) ? $html : false)
        ->with("productTranslateLanguages", $productTranslateLanguages);
    }

    /**
     * Update the specified Product in storage.
     *
     * @param int $id
     * @param UpdateProductRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProducttranslateRequest $request)
    {
        $product = $this->productRepository->findWithoutFail($id);

        if (empty($product)) {
            Flash::error('Product not found');
            return redirect(route('products.index'));
        }
        $input = $request->all();
        $input['product_id'] = $id;

        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->productRepository->model());
        try {
            $product = $this->productTranslateRepository->create($input);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.product')]));

        return redirect(route('products.index'));
    }

    /**
     * Remove the specified Product from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $product = $this->productRepository->findWithoutFail($id);

        if (empty($product)) {
            Flash::error('Product not found');

            return redirect(route('products.index'));
        }

        $this->productRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.product')]));

        return redirect(route('products.index'));
    }

    /**
     * Remove Media of Product
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $product = $this->productRepository->findWithoutFail($input['id']);
        try {
            if ($product->hasMedia($input['collection'])) {
                $product->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
