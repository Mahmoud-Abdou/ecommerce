<?php

namespace App\Http\Controllers;

use App\DataTables\ProductDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Repositories\ProductRepository;
use App\Repositories\ProductTranslateRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use App\Repositories\MarketRepository;
use App\Repositories\CategoryRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;


use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Media;
use App\Models\Product;

class ProductController extends Controller
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
        } elseif (setting('data_language') == 'ar') {
            $this->language_id = 2;
        } elseif (setting('data_language') == 'en') {
            $this->language_id = 1;
        } else {
            $this->language_id = 1;
        }
    }


    /**
    * Get model and return the join o flanguages with language condition
    *
    *
    * @param $this->productRepository products, $language_id
    * @return $this->productRepository products
    */

    public function language_join($products, $language_id)
    {
        $products = $products->join('product_translates', 'product_translates.product_id', 'products.id')
                                ->where('product_translates.language_id', $language_id);
        return $products;
    }

    /**
    * select the data with the main id not translate id
    *
    *
    * @param $this->categoryRepository catigories
    * @return $this->categoryRepository catigories
    */

    public function select_main_id($products)
    {
        $products = $products->select('products.*', 'product_translates.name', 'product_translates.description');
        return $products;
    }


    public function get_productTranslate_id($id, $language_id)
    {
        return $this->productTranslateRepository->where('product_id', $id)
                                                            ->where('language_id', $language_id)
                                                            ->first();
    }
    
    /**
     * Display a listing of the Product.
     *
     * @param ProductDataTable $productDataTable
     * @return Response
     */
    public function index(ProductDataTable $productDataTable)
    {
        $categories = $this->categoryRepository
            ->join('category_translates', 'category_translates.category_id', 'categories.id')
            ->where('category_translates.language_id', $this->language_id)
            ->select('category_translates.name', 'categories.id')->get();
            
        $productDataTable->set_language($this->language_id);
        return $productDataTable->render('products.index', [
            'categories' => $categories
        ]);
    }
    
    public function all_products(Request $request)
    {
        // dd($request);
        if ($request->start ==0) {
            $request['page']=1;
        } else {
            $request['page']= ($request->start / $request->length)+1;
        }
        
        $products = $this->productRepository
                //->join("user_markets", "user_markets.market_id", "=", "products.market_id")
                ->join('product_translates', 'product_translates.product_id', 'products.id')
                ->join('categories', 'categories.id', 'products.category_id')
                ->join('category_translates', 'categories.id', 'category_translates.category_id')
                ->where('category_translates.language_id', $this->language_id)
                ->where('product_translates.language_id', $this->language_id)
                // ->where('user_markets.user_id', auth()->id())
                ->select(
                    'products.*',
                    'product_translates.name as product_name',
                    'product_translates.description as product_description',
                    'category_translates.name as category_name'
                );
        if ($request['search']['value'] != null) {
            $products = $products->where('product_translates.name', 'like', '%'.$request['search']['value'].'%');
        }
        if (sizeof($request['order']) > 0) {
            if ($request['order'][0]['column'] == 0) {
                $products = $products->orderBy('product_translates.name', $request['order'][0]['dir']);
            } elseif ($request['order'][0]['column'] == 6) {
                // $products = $products->orderBy('markets.name', $request['order'][0]['dir']);
            } elseif ($request['order'][0]['column'] == 7) {
                $products = $products->orderBy('categories.name', $request['order'][0]['dir']);
            } elseif ($request['order'][0]['column'] != 1) {
                $products = $products->orderBy('products.'.$request['columns'][$request['order'][0]['column']]['data'], $request['order'][0]['dir']);
            }
        }

        $count = $products->count();
        $products = $products->paginate($request['length']);
        $newcats=[];
        foreach ($products as $cat) {
            $cate=$cat;
            $cate->image=getMediaColumn($cat, 'image');
            // $cate->updated_at = getDateColumn($cate);
            $newcats[]=$cate;
        }
        // dd($newcats);
        $data["recordsTotal"]=$count;
        $data["recordsFiltered"]=$count;
        $data["data"]=$newcats;
        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
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
    public function store(CreateProductRequest $request)
    {
        if ($request->sub_category != null) {
            $request['category_id']= $request['sub_category'];
        }
    
        if ($request->discount_price == null) {
            $request['discount_price']= 0;
        }
        if ($request->capacity == null) {
            $request['capacity']= 1;
        }
        if ($request->package_items_count == null) {
            $request['package_items_count']= 1;
        }
        if ($request->package_items_count == null) {
            $request['package_items_count']= 1;
        }
    
        $input = $request->all();
        $input['language_id'] = $this->language_id;
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->productRepository->model());
     

        try {
            $product = $this->productRepository->create($input);

            $input['product_id'] = $product->id;
            $productTranslate = $this->productTranslateRepository->create($input);
            
            $product->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
            if (isset($input['image']) && $input['image']) {
                foreach (explode(",", $input['image']) as $image) {
                    $cacheUpload = $this->uploadRepository->getByUuid($image);
                    $mediaItem = $cacheUpload->getMedia('image')->first();
                    $mediaItem->copy($product, 'image');
                }
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
        $product_data = $this->productRepository;
        $product_data = self::language_join($product_data, $this->language_id);
        $product_data = $product_data->where('products.id', $id);
        $product_data = self::select_main_id($product_data);
        $product_data = $product_data->first();

        $product = $this->productRepository->findWithoutFail($id);
        if (empty($product)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.product')]));

            return redirect(route('products.index'));
        }
        $category = $this->categoryRepository
        ->join('category_translates', 'category_translates.category_id', 'categories.id')
            ->where('category_translates.language_id', $this->language_id)
            ->where('categories.parent_id', 0)
            ->pluck('category_translates.name', 'categories.id');
        if (auth()->user()->hasRole('admin')) {
            $market = $this->marketRepository->pluck('name', 'id');
        } else {
            $market = $this->marketRepository->myMarkets()->pluck('name', 'id');
        }
        $customFieldsValues = $product->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->productRepository->model());
        $hasCustomField = in_array($this->productRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }
        // dd($product_data);

        return view('products.edit')
        ->with('product', $product_data)
        ->with("customFields", isset($html) ? $html : false)
        ->with("market", $market)
        ->with("category", $category);
    }

    /**
     * Update the specified Product in storage.
     *
     * @param int $id
     * @param UpdateProductRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProductRequest $request)
    {
        if ($request->sub_category != null) {
            $request['category_id']= $request['sub_category'];
        }
    
        $product = $this->productRepository->findWithoutFail($id);

        if (empty($product)) {
            Flash::error('Product not found');
            return redirect(route('products.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->productRepository->model());
        try {
            $product = $this->productRepository->update($input, $id);
            
            $productTranslateId = self::get_productTranslate_id($id, $this->language_id);
            $productTranslate = $this->productTranslateRepository->where("idoftable", $productTranslateId->idoftable)->update(
                [
                    'name'=>$input['name'],
                    'description'=> $input['description']
                ]
            );
            // // dd($productTranslate);
            // $productTranslate['name']=$input['name'];
            // $productTranslate['description']=$input['description'];
            // $productTranslate->save();
            
            
            if (isset($input['image']) && $input['image']) {
                foreach (explode(",", $input['image']) as $image) {
                    $cacheUpload = $this->uploadRepository->getByUuid($image);
                    $mediaItem = $cacheUpload->getMedia('image')->first();
                    $mediaItem->copy($product, 'image');
                }
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $product->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
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
        
        $productTranslateId = self::get_productTranslate_id($id, $this->language_id);
        
        if (empty($product) || $productTranslateId == null) {
            Flash::error('Product not found');
            
            return redirect(route('products.index'));
        }
        $productTranslate = $this->productTranslateRepository->where("idoftable", $productTranslateId->idoftable)->delete();

        $this->productRepository->delete($id);
        // $this->productTranslateRepository->delete($productTranslateId->id);

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

    /**
    * @return \Illuminate\Support\Collection
    */
    public function import()
    {
        set_time_limit(8000000);
        ini_set('memory_limit', '-1');
        if (request()->file('file') == null || !request()->file('file')->isValid()) {
            return back();
        }
        
        $productsImport = new ProductsImport;
        $productsImport->set_category_id(request('category_id'));
        $productsImport->set_market_id(1);
        $productsImport->set_language_id($this->language_id);
        $productsImport->set_repo($this->uploadRepository);
        Excel::import($productsImport, request()->file('file'));
           
        return back();
    }

    public function getProductImages(Request $request)
    {
        // $product_data = $this->productRepository;
        // $product_data = self::language_join($product_data, $this->language_id);
        // $product_data = $product_data->where('products.id',$request->id);
        // $product_data = self::select_main_id($product_data);
        // $product_data = $product_data->first();
        //$product = $this->productRepository->findWithoutFail($request->id);
        $images = Media::select('media.file_name')->where('model_type', 'App\Models\Product')->where('model_id', $request->id)->get();
        
        //dd($images);

        // $count = $products->count();
        $product = Product::where('id', $request->id)->first();
        $allMedias = $this->uploadRepository->allProductMedia($request->id);
        $newmedias=[];
        //    dd(newmedias)
        // foreach ($allMedias as $newmedia) {
        //     $imgObj=$newmedia;
        //     $imgObj->image=$newmedia->url;
        //     // $cate->updated_at = getDateColumn($cate);
        //     $newmedias[]=$imgObj;
        // }
      
        $data["data"]=$allMedias;
        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
