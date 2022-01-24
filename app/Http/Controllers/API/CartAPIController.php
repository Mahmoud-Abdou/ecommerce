<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\CreateCartRequest;
use App\Http\Requests\CreateFavoriteRequest;
use App\Models\Cart;
use App\Models\ProductTranslate;
use App\Repositories\CartRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Flash;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class CartController
 * @package App\Http\Controllers\API
 */

class CartAPIController extends Controller
{
    /** @var  CartRepository */
    private $cartRepository;

    public function __construct(CartRepository $cartRepo)
    {
        $this->cartRepository = $cartRepo;
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
     * Display a listing of the Cart.
     * GET|HEAD /carts
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $this->cartRepository->pushCriteria(new RequestCriteria($request));
            $this->cartRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            Flash::error($e->getMessage());
        }
        $carts = $this->cartRepository->get();

        // $newcarts=[];
        // foreach ($carts as $cart) {
        //     $newcart=$cart;
        //     $tr=ProductTranslate::where("product_id", $newcart->product->id)
        //     ->where("language_id", $this->language_id)->first();
        //     $newcart->product->name=$tr->name;
        //     $newcart->product->description=$tr->description;
        //     $newcarts[]=$newcart;
        // }
        // return $this->sendResponse($newcarts, 'Carts retrieved successfully');
        return $this->sendResponse($carts->toArray(), 'Carts retrieved successfully');
    }

    /**
     * Display a listing of the Cart.
     * GET|HEAD /carts
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(Request $request)
    {
        try {
            $this->cartRepository->pushCriteria(new RequestCriteria($request));
            $this->cartRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            Flash::error($e->getMessage());
        }
        $count = $this->cartRepository->count();

        return $this->sendResponse($count, 'Count retrieved successfully');
    }

    /**
     * Display the specified Cart.
     * GET|HEAD /carts/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var Cart $cart */
        if (!empty($this->cartRepository)) {
            $cart = $this->cartRepository->findWithoutFail($id);
        }

        if (empty($cart)) {
            return $this->sendError('Cart not found');
        }

        return $this->sendResponse($cart->toArray(), 'Cart retrieved successfully');
    }
    /**
     * Store a newly created Cart in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();
        try {
            if (isset($input['reset']) && $input['reset'] == '1') {
                // delete all items in the cart of current user
                $this->cartRepository->deleteWhere(['user_id'=> $input['user_id']]);
            }
           $old= $this->cartRepository
            ->where("product_id", $input["product_id"])
            ->where("product_id", $input["user_id"])->first();
            if($old != null ){
                $request["quantity"]= $old->quantity+$request["quantity"];
                return self::update($old->id, $request);
            }else{
                $cart = $this->cartRepository->create($input);
            }      
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($cart->toArray(), __('lang.saved_successfully', ['operator' => __('lang.cart')]));
    }

    /**
     * Update the specified Cart in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $cart = $this->cartRepository->findWithoutFail($id);

        if (empty($cart)) {
            return $this->sendError('Cart not found');
        }
        $input = $request->all();

        try {
            //$input['options'] = isset($input['options']) ? $input['options'] : [];
            $cart = $this->cartRepository->update($input, $id);
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($cart->toArray(), __('lang.saved_successfully', ['operator' => __('lang.cart')]));
    }

    /**
     * Remove the specified Favorite from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $cart = $this->cartRepository->findWithoutFail($id);

        if (empty($cart)) {
            return $this->sendError('Cart not found');
        }

        $cart = $this->cartRepository->delete($id);

        return $this->sendResponse($cart, __('lang.deleted_successfully', ['operator' => __('lang.cart')]));
    }
}
