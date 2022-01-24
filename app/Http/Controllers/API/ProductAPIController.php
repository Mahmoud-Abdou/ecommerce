<?php

namespace App\Http\Controllers\API;

use App\Criteria\Products\NearCriteria;
use App\Criteria\Products\ProductsOfFieldsCriteria;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Media;
use App\Models\ProductReview;
use App\Repositories\CustomFieldRepository;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\UploadRepository;
use Flash;
use DB;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class ProductController
 * @package App\Http\Controllers\API
 */
class ProductAPIController extends Controller
{
    /** @var  CategoryRepository */
    private $categoryRepository;

    /** @var  ProductRepository */
    private $productRepository;
    
    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;
    /**
     * @var UploadRepository
     */
    private $uploadRepository;
    private $language_id;

    private $limit;
    private $offset;


    public function __construct(ProductRepository $productRepo, CategoryRepository $categoryRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->productRepository = $productRepo;
        $this->categoryRepository = $categoryRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
        // $this->language_id = request()->header('lang') == null ? 1: request()->header('lang');
        $language = request()->header('language');
        $language_id = 1;
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
    }

    /**
     * Get model and return the join o flanguages with language condition
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
     * get all sup cateories from each product
     *
     * @param $this->productRepository products, $this->categoryRepository categories, $language_id
     * @return $this->productRepository products
     */
    public function get_supCats($products, $categories, $language_id)
    {
        for ($i = 0; $i < sizeof($products) ;$i++) {
            $sup_categories = $categories->join('category_translates', 'category_translates.category_id', 'categories.id')
                                        ->select("categories.*", "category_translates.name", "category_translates.description")
                                        ->where('categories.parent_id', $products[$i]['category_id'])
                                        ->where('category_translates.language_id', $language_id)
                                        ->get();
            
            $products[$i]['sup_categories'] = $sup_categories;
        }
        return $products;
    }
    /**
     * get all sup cateories from each product
     *
     * @param $this->productRepository products, $this->categoryRepository categories, $language_id
     * @return $this->productRepository products
     */
    public function get_supCat_products(Request $request,$products, $categories, $language_id)
    {
        // $all_sup_products = [];
        // for ($i = 0; $i < sizeof($products) ;$i++) {
        //     $sup_categories = $categories->where('categories.parent_id', $products[$i]['category_id'])
        //                                 ->get();
        //     foreach ($sup_categories as $sup_category) {
        //         $sup_products = Product::join('product_translates', 'product_translates.product_id', 'products.id')
        //                             ->where('product_translates.language_id', $language_id)
        //                             ->where('products.category_id', $sup_category->id)
        //                             ->skip($this->offset*$this->limit)->take($this->limit)
        //                             ->get()->toArray();
        //         // for ($i = 0; $i < sizeof($sup_products) ;$i++) {
        //         //     $market = DB::table('markets')->where('id', $sup_products[$i]->market_id)->first();
        //         //     // dd($sup_products[$i]['maket']);
        //         //     $sup_products[$i]->market = $market;
        //         // }
        //         $all_sup_products = array_merge($all_sup_products, $sup_products);
        //     }
        // }
        // return $all_sup_products;
        $cats=[];
        if(isset(explode(":",$request["search"])[1])){
            $cats[]=(int) explode(":",$request["search"])[1];
        }

        $all_sup_products = [];
        
        // for ($i = 0; $i < sizeof($products) ;$i++) {
        //     $cats[]=(int) $products[$i]['category_id'];

        // }
        $sup_categories = $categories->whereIn('categories.parent_id', $cats)
        ->pluck("id");
        
        // dd($sup_categories);
        $sup_products = Product::with("market")
            ->join('product_translates', 'product_translates.product_id', 'products.id')
            ->where('product_translates.language_id', $language_id)
            ->whereIn('products.category_id',   $sup_categories)
            ->paginate($this->limit)
            ->toArray();
        return $sup_products;
    }


    /**
     * Display a listing of the Product.
     * GET|HEAD /products
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $request["lang"]=$this->language_id;
        if ($request['limit'] == null) {
            $request['limit'] =10;
        }
        if ($request['offset'] == null) {
            $request['offset'] = 0;
        }
        if ($request['offset'] == 0) {
            $request['page']= 1;
        } else {
            $request['page']= (($request['offset'] * $request['limit']) / $request['limit'])+1;
        }
        try {
            $this->productRepository->pushCriteria(new RequestCriteria($request));
            // dd($this->productRepository);
            // $this->productRepository->pushCriteria(new LimitOffsetCriteria($request));
            $this->productRepository->pushCriteria(new ProductsOfFieldsCriteria($request));

            $products = $this->productRepository;
            $products = self::language_join($this->productRepository, $this->language_id);
            if ($request->featured == 1) {
                $products = $products->where('products.featured', 1);
            }
            if ($request->discounts == 1) {
                $productsp = $products->where('products.discount_price', '!=', 0)
                ->select(
                    "products.*",
                    "product_translates.name",
                    "product_translates.description"
                );
               
                $products = self::convert_object($productsp->paginate($request['limit'])->toArray());
            } elseif ($request->discounts == 2) {
                $products = 
                    $products
                    ->where('products.discount_price', '!=', 0)
                    ->where(DB::raw('((products.price - products.discount_price ) / products.price )* 100'), '>=', $request->discounts_value == null ? 20 : $request->discounts_value)
                    ->select("products.*", "product_translates.name", "product_translates.description")
                     ->paginate($request['limit'])->toArray();
            } else {
                $products = $products
                ->select(
                    "products.*",
                    "product_translates.name",
                    "product_translates.description"
                )
                ->paginate($request['limit'])->toArray();
            }
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        if (isset($request["limit"])) {
            $this->limit=$request["limit"];
        } else {
            $this->limit=10;
        }
        if (isset($request["offset"])) {
            $this->offset=$request["offset"];
        } else {
            $this->offset=0;
        }
        
        // $products = self::get_supCats($products, $this->categoryRepository, $this->language_id);
        $sup_products = self::get_supCat_products($request, $products["data"], $this->categoryRepository, $this->language_id);
        $newSup_product = [];
        foreach ($sup_products["data"] as $key => $value) {
            $newSup_product[$key] = (array)$value;
        }
        $products = array_merge($products["data"], $newSup_product);
        //dd($newSup_product);
         $newproducts=[];
        foreach ($products as $product) {
            // dd($product);
            unset($product["media"]);
            $prod=$product;
            $prod["media"]=Media::where("model_type", $this->productRepository->model())->where("model_id", $product['id'])->get();

            $rev=ProductReview::where("product_id", $product['id'])->pluck('rate');
            if ($rev == null) {
                $prod['total_reviews']="0.0";
                $prod['total_num_reviews']=0;
            } else {
                $prod['total_reviews']=number_format($rev->avg(), 1);
                $prod['total_num_reviews']=count($rev);
            }
            $newproducts[]=$prod;
        }
        return $this->sendResponse($newproducts, 'Products retrieved successfully');
    }
       public function searchproduct(Request $request)
    {
        $request["lang"]=$this->language_id;
        // $this->productRepository->pushCriteria(new RequestCriteria($request));
        $this->productRepository->pushCriteria(new LimitOffsetCriteria($request));
        // $this->productRepository->pushCriteria(new ProductsOfFieldsCriteria($request));
        $products=$this->productRepository
            // ->where("products.slider", 0)
            ->join('product_translates', 'product_translates.product_id', 'products.id');
        if ($request["search"] != null) {
            $arrayofsearch=explode(';', $request["search"]);
            $key=explode(':', $arrayofsearch[0]);
            $products = $products
                        ->Where($key[0], 'like', '%' . $key[1] . '%')
                        ->Where("product_translates.language_id", $this->language_id);
            if (isset($arrayofsearch[1])) {
                $key=explode(':', $arrayofsearch[1]);
                $products = $products
                        ->orWhere($key[0], 'like', '%' . $key[1] . '%')
                        // ->where("products.slider", 0)
                        ->Where("product_translates.language_id", $this->language_id);
            }
        }
        $products =
                $products ->Where("product_translates.language_id", $this->language_id)
                ->select(
                    "products.*",
                    "product_translates.name",
                    "product_translates.language_id",
                    "product_translates.description"
                )
                ->get()
                ->toArray();
        return $this->sendResponse($products, 'Products retrieved successfully');
    }
    public function convert_object($response)
    {
        $data = [];
        foreach ($response as $key => $value) {
            array_push($data, $value);
        }
        $response = $data;
        return $response;
    }
    /**
     * Display the specified Product.
     * GET|HEAD /products/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        /** @var Product $product */
        if (!empty($this->productRepository)) {
            try {
                $this->productRepository->pushCriteria(new RequestCriteria($request));
                $this->productRepository->pushCriteria(new LimitOffsetCriteria($request));
            } catch (RepositoryException $e) {
                return $this->sendError($e->getMessage());
            }
            
            $product=self::language_join($this->productRepository, $this->language_id);
            $product= $product->where("products.id", $id)->select(
                "products.*",
                "product_translates.name",
                "product_translates.description"
            )->first();
            if (isset($product->category->id)) {
                $cattrans= $product->category->join("category_translates", "category_translates.category_id", "categories.id")
                ->where("category_translates.language_id", $this->language_id)
                ->select("category_translates.description", "category_translates.name")
                ->first();
                $product->category->name=isset($cattrans->name)?$cattrans->name:"-";
                $product->category->description=isset($cattrans->description)?$cattrans->description:"-";
            };
        }

        if (empty($product)) {
            return $this->sendError('Product not found');
        }
        $product=$product->toArray();
     
        if(isset($product['option_groups']) && sizeof($product['option_groups']) > 0){
            $idss=[];
            $newoptions=[];
            foreach ($product['option_groups'] as  $value) {
             
                if (in_array($value['id'], $idss)) {
                  
                }else{
                    $idss[]=$value['id'];
                    $newoptions[]=$value;
                }
                
            }
           
           $product['option_groups']=$newoptions;
        }
        return $this->sendResponse($product, 'Product retrieved successfully');
    }

    /**
     * Store a newly created Product in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->productRepository->model());
        try {
            $product = $this->productRepository->create($input);
            $product->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($product, 'image');
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($product->toArray(), __('lang.saved_successfully', ['operator' => __('lang.product')]));
    }

    /**
     * Update the specified Product in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $product = $this->productRepository->findWithoutFail($id);

        if (empty($product)) {
            return $this->sendError('Product not found');
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->productRepository->model());
        try {
            $product = $this->productRepository->update($input, $id);

            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($product, 'image');
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $product->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($product->toArray(), __('lang.updated_successfully', ['operator' => __('lang.product')]));
    }

    /**
     * Remove the specified Product from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $product = $this->productRepository->findWithoutFail($id);

        if (empty($product)) {
            return $this->sendError('Product not found');
        }

        $product = $this->productRepository->delete($id);

        return $this->sendResponse($product, __('lang.deleted_successfully', ['operator' => __('lang.product')]));
    }

    public function hekaya(Request $request)
    {
        $out=[];
        $request["with"]="market";
        $request["limit"]="6";
        $request["lang"]=$this->language_id;
        try {
            $this->productRepository->pushCriteria(new RequestCriteria($request));
            $this->productRepository->pushCriteria(new LimitOffsetCriteria($request));
            $this->productRepository->pushCriteria(new ProductsOfFieldsCriteria($request));

            $products = $this->productRepository;
            $products = self::language_join($this->productRepository, $this->language_id);

            $products = $products
            ->select(
                "products.*",
                "product_translates.name",
                "product_translates.description"
            )
            ->get()->toArray();
            $newproducts=[];
            foreach ($products as $product) {
                $prod=$product;
                $rev=ProductReview::where("product_id", $product['id'])->pluck('rate');
                if ($rev == null) {
                    $prod['total_reviews']="0.0";
                    $prod['total_num_reviews']=0;
                } else {
                    $prod['total_reviews']=number_format($rev->avg(), 1);
                    $prod['total_num_reviews']=count($rev);
                }
                $newproducts[]=$prod;
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        $out["trending"]=$newproducts;
        $request["with"]=null;
        $request["limit"]=null;

        // cats
        try {
            $this->categoryRepository->pushCriteria(new RequestCriteria($request));
            $this->categoryRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            Flash::error($e->getMessage());
        }
        $categories = $this->categoryRepository;
        $categories = self::cat_language_join($categories, $this->language_id);
        $categories = $categories->select(
            "categories.*",
            "category_translates.name",
            "category_translates.description"
        )->get();
        if ($request->id != null) {
            $categories =  self::convert_object($categories->where("parent_id", $request->id));
        } else {
            $categories = $categories->toArray();
        }

        $out["categories"]=$categories;

        $request["discounts"]=2;
        $request["discounts_value"]=0;
        $products =null;

        try {
            // $this->productRepository= new ProductRepository;
            $this->productRepository->pushCriteria(new RequestCriteria($request));
            $this->productRepository->pushCriteria(new LimitOffsetCriteria($request));
            $this->productRepository->pushCriteria(new ProductsOfFieldsCriteria($request));
 
            $products = $this->productRepository;
            $products = self::convert_object(
                $products
                ->where('products.discount_price', '!=', 0)
                ->select("products.*", "product_translates.name", "product_translates.description")->get()
            );
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }
        
        $out["discounts"]= $products;
        return $this->sendResponse($out, "home OK ");
    }
    
    public function cat_language_join($categories, $language_id)
    {
        $categories = $categories->join('category_translates', 'category_translates.category_id', 'categories.id')
                                ->where('category_translates.language_id', $language_id);
        return $categories;
    }


    public function sweetJana(Request $request)
    {
        $out=[];
        $request["with"]="market";
        $request["limit"]="6";
        $request["lang"]=$this->language_id;
        try {
            $this->productRepository->pushCriteria(new RequestCriteria($request));
            $this->productRepository->pushCriteria(new LimitOffsetCriteria($request));
            $this->productRepository->pushCriteria(new ProductsOfFieldsCriteria($request));

            $products = $this->productRepository;
            $products = self::language_join($this->productRepository, $this->language_id);

            $products = $products
            ->select(
                "products.*",
                "product_translates.name",
                "product_translates.description"
            )
            ->get()->toArray();
            $newproducts=[];
            foreach ($products as $product) {
                $prod=$product;
                $rev=ProductReview::where("product_id", $product['id'])->pluck('rate');
                if ($rev == null) {
                    $prod['total_reviews']="0.0";
                    $prod['total_num_reviews']=0;
                } else {
                    $prod['total_reviews']=number_format($rev->avg(), 1);
                    $prod['total_num_reviews']=count($rev);
                }
                $newproducts[]=$prod;
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        $out["trending"]=$newproducts;
        $request["with"]=null;
        $request["limit"]=null;

        // cats
        try {
            $this->categoryRepository->pushCriteria(new RequestCriteria($request));
            $this->categoryRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            Flash::error($e->getMessage());
        }
        $categories = $this->categoryRepository;
        $categories = self::cat_language_join($categories, $this->language_id);
        $categories = $categories
        ->where("parent_id", 0)
        ->select(
            "categories.*",
            "category_translates.name",
            "category_translates.description"
        )->get();
        if ($request->id != null) {
            $categories =  self::convert_object($categories->where("parent_id", $request->id));
        } else {
            $categories = $categories->toArray();
        }
        $out["categories"]=$categories;
        $request["discounts"]=2;
        $request["discounts_value"]=0;
        $products =null;

        try {
            // $this->productRepository= new ProductRepository;
            // $this->productRepository->pushCriteria(new RequestCriteria($request));
            // $this->productRepository->pushCriteria(new LimitOffsetCriteria($request));
            // $this->productRepository->pushCriteria(new ProductsOfFieldsCriteria($request));
 
            $products = Product::join('product_translates', 'product_translates.product_id', 'products.id')
                    ->where('product_translates.language_id', $this->language_id);
            $products = self::convert_object(
                $products
                ->where('products.discount_price', '!=', 0)
                ->select(
                    "products.*",
                    "product_translates.name",
                    "product_translates.description"
                )
                ->get()
            );
            $newproducts=[];
            foreach ($products as $product) {
                $prod=$product;
                $rev=ProductReview::where("product_id", $product['id'])->pluck('rate');
                if ($rev == null) {
                    $prod['total_reviews']="0.0";
                    $prod['total_num_reviews']=0;
                } else {
                    $prod['total_reviews']=number_format($rev->avg(), 1);
                    $prod['total_num_reviews']=count($rev);
                }
                $newproducts[]=$prod;
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }
        
        $out["discounts"]= $newproducts;
        $products =null;

        try {
            // $this->productRepository= new ProductRepository;
            $this->productRepository->pushCriteria(new RequestCriteria($request));
            $this->productRepository->pushCriteria(new LimitOffsetCriteria($request));
            $this->productRepository->pushCriteria(new ProductsOfFieldsCriteria($request));
 
            $products = $this->productRepository;
            $products = self::convert_object(
                $products
                ->where('products.featured', 1)
                ->select(
                    "products.*",
                    "product_translates.name",
                    "product_translates.description"
                )
                ->get()
            );
            $newproducts=[];
            foreach ($products as $product) {
                $prod=$product;
                $rev=ProductReview::where("product_id", $product['id'])->pluck('rate');
                if ($rev == null) {
                    $prod['total_reviews']="0.0";
                    $prod['total_num_reviews']=0;
                } else {
                    $prod['total_reviews']=number_format($rev->avg(), 1);
                    $prod['total_num_reviews']=count($rev);
                }
                $newproducts[]=$prod;
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }
        
        $out["slider"]= $newproducts;



        return $this->sendResponse($out, "home OK ");
    }
    public function pethome(Request $request)
    {
        $out=[];
        $request["with"]="market";
        $request["limit"]="6";
        $request["lang"]=$this->language_id;
        try {
            $this->productRepository->pushCriteria(new RequestCriteria($request));
            $this->productRepository->pushCriteria(new LimitOffsetCriteria($request));
            $this->productRepository->pushCriteria(new ProductsOfFieldsCriteria($request));

            $products = $this->productRepository;
            $products = self::language_join($this->productRepository, $this->language_id);

            $products = $products
            ->select(
                "products.*",
                "product_translates.name",
                "product_translates.description"
            )
            ->get()->toArray();
            $newproducts=[];
            foreach ($products as $product) {
                $prod=$product;
                $rev=ProductReview::where("product_id", $product['id'])->pluck('rate');
                if ($rev == null) {
                    $prod['total_reviews']="0.0";
                    $prod['total_num_reviews']=0;
                } else {
                    $prod['total_reviews']=number_format($rev->avg(), 1);
                    $prod['total_num_reviews']=count($rev);
                }
                $newproducts[]=$prod;
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        $out["trending"]=$newproducts;
        $request["with"]=null;
        $request["limit"]=null;

        // cats
        try {
            $this->categoryRepository->pushCriteria(new RequestCriteria($request));
            $this->categoryRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            Flash::error($e->getMessage());
        }
        $categories = $this->categoryRepository;
        $categories = self::cat_language_join($categories, $this->language_id);
        $categories = $categories
        ->where("parent_id", 0)
        ->where("categories.id", "!=", 10)
        ->select(
            "categories.*",
            "category_translates.name",
            "category_translates.description"
        )->get();
        if ($request->id != null) {
            $categories =  self::convert_object($categories->where("parent_id", $request->id));
        } else {
            $categories = $categories->toArray();
        }
        $out["categories"]=$categories;
        // Animal cats
        try {
            $this->categoryRepository->pushCriteria(new RequestCriteria($request));
            $this->categoryRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            Flash::error($e->getMessage());
        }
        $categories = $this->categoryRepository;
        $categories = self::cat_language_join($categories, $this->language_id);
        $categories = $categories
        ->where("categories.id", 10)
        ->orWhere("categories.parent_id", 10)
        ->select(
            "categories.*",
            "category_translates.name",
            "category_translates.description"
        )->get();
        if ($request->id != null) {
            $categories =  self::convert_object($categories->where("parent_id", $request->id));
        } else {
            $categories = $categories->toArray();
        }
        $out["categories_animals"]=$categories;
        $request["discounts"]=2;
        $request["discounts_value"]=0;
        $products =null;

        try {
            // $this->productRepository= new ProductRepository;
            // $this->productRepository->pushCriteria(new RequestCriteria($request));
            // $this->productRepository->pushCriteria(new LimitOffsetCriteria($request));
            // $this->productRepository->pushCriteria(new ProductsOfFieldsCriteria($request));
 
            $products = Product::join('product_translates', 'product_translates.product_id', 'products.id')
                    ->where('product_translates.language_id', $this->language_id);
            $products = self::convert_object(
                $products
                ->where('products.discount_price', '!=', 0)
                ->select(
                    "products.*",
                    "product_translates.name",
                    "product_translates.description"
                )
                ->get()
            );
            $newproducts=[];
            foreach ($products as $product) {
                $prod=$product;
                $rev=ProductReview::where("product_id", $product['id'])->pluck('rate');
                if ($rev == null) {
                    $prod['total_reviews']="0.0";
                    $prod['total_num_reviews']=0;
                } else {
                    $prod['total_reviews']=number_format($rev->avg(), 1);
                    $prod['total_num_reviews']=count($rev);
                }
                $newproducts[]=$prod;
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }
        
        $out["discounts"]= $newproducts;
        $products =null;

        try {
            // $this->productRepository= new ProductRepository;
            $this->productRepository->pushCriteria(new RequestCriteria($request));
            $this->productRepository->pushCriteria(new LimitOffsetCriteria($request));
            $this->productRepository->pushCriteria(new ProductsOfFieldsCriteria($request));
 
            $products = $this->productRepository;
            $products = self::convert_object(
                $products
                ->where('products.featured', 1)
                ->select(
                    "products.*",
                    "product_translates.name",
                    "product_translates.description"
                )
                ->get()
            );
            $newproducts=[];
            foreach ($products as $product) {
                $prod=$product;
                $rev=ProductReview::where("product_id", $product['id'])->pluck('rate');
                if ($rev == null) {
                    $prod['total_reviews']="0.0";
                    $prod['total_num_reviews']=0;
                } else {
                    $prod['total_reviews']=number_format($rev->avg(), 1);
                    $prod['total_num_reviews']=count($rev);
                }
                $newproducts[]=$prod;
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }
        
        $out["slider"]= $newproducts;



        return $this->sendResponse($out, "home OK ");
    }
}
