<?php

namespace App\Http\Controllers\API;

use App\Events\OrderChangedEvent;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductTranslate;
use App\Notifications\NewOrder;
use App\Notifications\StatusChangedOrder;
use App\Repositories\CartRepository;
use App\Repositories\ProductOrderRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\UserRepository;
use Braintree\Gateway;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use Stripe\Stripe;
use Stripe\Token;

/**
 * Class OrderController
 * @package App\Http\Controllers\API
 */
class OrderAPIController extends Controller
{
    /** @var  OrderRepository */
    private $orderRepository;
    /** @var  ProductOrderRepository */
    private $productOrderRepository;
    /** @var  CartRepository */
    private $cartRepository;
    /** @var  UserRepository */
    private $userRepository;
    /** @var  PaymentRepository */
    private $paymentRepository;
    /** @var  NotificationRepository */
    private $notificationRepository;

    public function __construct(OrderRepository $orderRepo, ProductOrderRepository $productOrderRepository, CartRepository $cartRepo, PaymentRepository $paymentRepo, NotificationRepository $notificationRepo, UserRepository $userRepository)
    {
        $this->orderRepository = $orderRepo;
        $this->productOrderRepository = $productOrderRepository;
        $this->cartRepository = $cartRepo;
        $this->userRepository = $userRepository;
        $this->paymentRepository = $paymentRepo;
        $this->notificationRepository = $notificationRepo;
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
     * Display a listing of the Order.
     * GET|HEAD /orders
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        Log::error("get orders");
        try {
            //    $this->orderRepository->setlang($this->language_id);
            $this->orderRepository->pushCriteria(new RequestCriteria($request));
            // $this->orderRepository->with()
            $this->orderRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            Flash::error($e->getMessage());
        }

       // dd($request);
        if ($request->is('api/manager/*')) {
            $user=$this->userRepository->where("api_token", $request->api_token)->first();
            if ($user == null) {
                return $this->sendResponse(["error" => "error"], 'Orders retrieved successfully');
            } else {
                $orders = Order::with("user")
                ->with("orderStatus")
                ->with("productOrders.product")
                ->join("product_orders", "orders.id", "=", "product_orders.order_id")
                ->join("products", "products.id", "=", "product_orders.product_id")
                ->join("user_markets", "user_markets.market_id", "=", "products.market_id")
                ->where('user_markets.user_id', $user->id)
                ->groupBy('orders.id')
                ->select('orders.*');
                if($request->order_status_id != null){
                   $orders=$orders->where("orders.order_status_id", $request->order_status_id);
               }else{
                   $orders=$orders->where("orders.order_status_id","!=",5);
               }
                $orders=$orders->get()->toArray();
            }
        } else {
            $this->orderRepository->pushCriteria(new RequestCriteria($request));
            // $this->orderRepository->with()
            $this->orderRepository->pushCriteria(new LimitOffsetCriteria($request));
            $orders = $this->orderRepository;
            if($request->order_status_id != null){
                $orders=$orders->where("orders.order_status_id", $request->order_status_id);
            }
           $orders=$orders->get()->toArray();
            
        }
        // $neworders=[];
        // foreach ($orders->product_orders as $order) {
        //     $neworder=$order;
        //     $tr=ProductTranslate::where("product_id", $neworder->product->id)
        //     ->where("language_id", $this->language_id)->first();
        //     $neworder->product->name=$tr->name;
        //     $neworder->product->description=$tr->description;
        //     $neworders[]=$neworder;
        // }
        return $this->sendResponse($orders, 'Orders retrieved successfully');
    }

    /**
     * Display the specified Order.
     * GET|HEAD /orders/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        /** @var Order $order */
        if (!empty($this->orderRepository)) {
            try {
                $this->orderRepository->pushCriteria(new RequestCriteria($request));
                $this->orderRepository->pushCriteria(new LimitOffsetCriteria($request));
            } catch (RepositoryException $e) {
                Flash::error($e->getMessage());
            }
            $order = $this->orderRepository->findWithoutFail($id);
        }

        if (empty($order)) {
            return $this->sendError('Order not found');
        }

        return $this->sendResponse($order->toArray(), 'Order retrieved successfully');
    }

    /**
     * Store a newly created Order in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $payment = $request->only('payment');
        if (isset($payment['payment']) && $payment['payment']['method']) {
            if ($payment['payment']['method'] == "Credit Card (Stripe Gateway)") {
                return $this->stripPayment($request);
            } else {
                return $this->cashPayment($request);
            }
        }
    }

    private function stripPayment(Request $request)
    {
        $input = $request->all();
        $amount = 0;
        try {
            $user = $this->userRepository->findWithoutFail($input['user_id']);
            if (empty($user)) {
                return $this->sendError('User not found');
            }
            $stripeToken = Token::create(array(
                "card" => array(
                    "number" => $input['stripe_number'],
                    "exp_month" => $input['stripe_exp_month'],
                    "exp_year" => $input['stripe_exp_year'],
                    "cvc" => $input['stripe_cvc'],
                    "name" => $user->name,
                )
            ));
            if ($stripeToken->created > 0) {
                $order = $this->orderRepository->create(
                    $request->only('user_id', 'order_status_id', 'tax', 'delivery_address_id', 'delivery_fee')
                );
                foreach ($input['products'] as $productOrder) {
                    $productOrder['order_id'] = $order->id;
                    $amount += $productOrder['price'] * $productOrder['quantity'];
                    $this->productOrderRepository->create($productOrder);
                }
                $amountWithTax = $amount + ($amount * $order->tax / 100);
                $charge = $user->charge((int)($amountWithTax * 100), ['source' => $stripeToken]);
                $payment = $this->paymentRepository->create([
                    "user_id" => $input['user_id'],
                    "description" => trans("lang.payment_order_done"),
                    "price" => $amountWithTax,
                    "status" => $charge->status, // $charge->status
                    "method" => $input['payment']['method'],
                ]);
                $this->orderRepository->update(['payment_id' => $payment->id], $order->id);

                $this->cartRepository->deleteWhere(['user_id' => $order->user_id]);

                Notification::send($order->productOrders[0]->product->market->users, new NewOrder($order));
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($order->toArray(), __('lang.saved_successfully', ['operator' => __('lang.order')]));
    }

    private function cashPayment(Request $request)
    {
        $input = $request->all();
        $amount = 0;
        try {
            $order = $this->orderRepository->create(
                $request->only('user_id', 'order_status_id', 'tax', 'delivery_address_id', 'delivery_fee')
            );
            foreach ($input['products'] as $productOrder) {
                $productOrder['order_id'] = $order->id;
                $amount += $productOrder['price'] * $productOrder['quantity'];
                $this->productOrderRepository->create($productOrder);
            }
            $amountWithTax = $amount + ($amount * $order->tax / 100);
            $payment = $this->paymentRepository->create([
                "user_id" => $input['user_id'],
                "description" => trans("lang.payment_order_waiting"),
                "price" => $amountWithTax,
                "status" => 'Waiting for Client',
                "method" => $input['payment']['method'],
            ]);

            $this->orderRepository->update(['payment_id' => $payment->id], $order->id);

            $this->cartRepository->deleteWhere(['user_id' => $order->user_id]);

            Notification::send($order->productOrders[0]->product->market->users, new NewOrder($order));
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($order->toArray(), __('lang.saved_successfully', ['operator' => __('lang.order')]));
    }

    /**
     * Update the specified Order in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $order = $this->orderRepository->findWithoutFail($id);

        if (empty($order)) {
            return $this->sendError('Order not found');
        }
        $input = $request->all();

        try {
            $order = $this->orderRepository->update($input, $id);

            if (isset($input['order_status_id'])) {
                if ($input['order_status_id'] == 5 && !empty($order)) {
                    $this->paymentRepository->update(['status' => 'Paid'], $order['payment_id']);
                    event(new OrderChangedEvent($order));
                } else {
                    $this->paymentRepository->update(['status' => $input['status']], $order['payment_id']);
                }
            }
            Notification::send([$order->user], new StatusChangedOrder($order));
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($order->toArray(), __('lang.saved_successfully', ['operator' => __('lang.order')]));
    }

    public function cancelorder(Request $request)
    {
        $order = $this->orderRepository->findWithoutFail($request->id);

        if (empty($order)) {
            return $this->sendError('Order not found');
        }
        $input = $request->all();

        try {
            $order = $this->orderRepository->update($input, $request->id);
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($order->toArray(), __('lang.saved_successfully', ['operator' => __('lang.order')]));
    }
}
