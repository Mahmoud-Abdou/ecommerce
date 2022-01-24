<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Flash;

/**
 * Class CategoryController
 * @package App\Http\Controllers\API
 */

class CategoryAPIController extends Controller
{
    /** @var  CategoryRepository */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepository = $categoryRepo;
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
     * Display a listing of the Category.
     * GET|HEAD /categories
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $this->categoryRepository->pushCriteria(new RequestCriteria($request));
            $this->categoryRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            Flash::error($e->getMessage());
        }
        $categories = $this->categoryRepository;
        $categories = self::language_join($categories, $this->language_id);

        $categories = $categories->where("parent_id", 0)
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
        return $this->sendResponse($categories, 'Categories retrieved successfully');
    }

    /**
     * Display the specified Category.
     * GET|HEAD /categories/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var Category $category */
        if (!empty($this->categoryRepository)) {
            $category = $this->categoryRepository->where('categories.id', $id);
            $category = self::language_join($category, $this->language_id);
            $category = $category->select("categories.*", "category_translates.name", "category_translates.description")->first();
            $subcategory = $this->categoryRepository->where('categories.parent_id', $id);
            $subcategory = self::language_join($subcategory, $this->language_id);
            $subcategory = $subcategory->select("categories.*", "category_translates.name", "category_translates.description")->get();
            if ($subcategory == null) {
                $subcategory=[];
            }
            $category->sup_categories=$subcategory;
        }
        if (empty($category)) {
            return $this->sendError('Category not found');
        }
        return $this->sendResponse($category->toArray(), 'Category retrieved successfully');
    }
}
