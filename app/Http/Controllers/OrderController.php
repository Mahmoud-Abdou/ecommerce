<?php

namespace App\Http\Controllers;

use App\Criteria\Users\ClientsCriteria;
use App\Criteria\Users\DriversCriteria;
use App\Criteria\Users\DriversOfMarketCriteria;
use App\DataTables\ProductOrderDataTable;
use App\DataTables\OrderDataTable;
use App\Events\OrderChangedEvent;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Notifications\AssignedOrder;
use App\Notifications\StatusChangedOrder;
use App\Repositories\CustomFieldRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\OrderRepository;
use App\Repositories\OrderStatusRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\UserRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Models\Product;

class OrderController extends Controller
{
    /** @var  OrderRepository */
    private $orderRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var OrderStatusRepository
     */
    private $orderStatusRepository;
    /** @var  NotificationRepository */
    private $notificationRepository;
    /** @var  PaymentRepository */
    private $paymentRepository;

    public function __construct(OrderRepository $orderRepo, CustomFieldRepository $customFieldRepo, UserRepository $userRepo, OrderStatusRepository $orderStatusRepo, NotificationRepository $notificationRepo, PaymentRepository $paymentRepo)
    {
        parent::__construct();
        $this->orderRepository = $orderRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->userRepository = $userRepo;
        $this->orderStatusRepository = $orderStatusRepo;
        $this->notificationRepository = $notificationRepo;
        $this->paymentRepository = $paymentRepo;
    }

    /**
     * Display a listing of the Order.
     *
     * @param OrderDataTable $orderDataTable
     * @return Response
     */
    public function index(OrderDataTable $orderDataTable)
    {
        return $orderDataTable->render('orders.index');
    }

    /**
     * Show the form for creating a new Order.
     *
     * @return Response
     */
    public function create()
    {
        $user = $this->userRepository->getByCriteria(new ClientsCriteria())->pluck('name', 'id');
        $driver = $this->userRepository->getByCriteria(new DriversCriteria())->pluck('name', 'id');

        $orderStatus = $this->orderStatusRepository->pluck('status', 'id');

        $hasCustomField = in_array($this->orderRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->orderRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('orders.create')->with("customFields", isset($html) ? $html : false)->with("user", $user)->with("driver", $driver)->with("orderStatus", $orderStatus);
    }

    /**
     * Store a newly created Order in storage.
     *
     * @param CreateOrderRequest $request
     *
     * @return Response
     */
    public function store(CreateOrderRequest $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->orderRepository->model());
        try {
            $order = $this->orderRepository->create($input);
            $order->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.order')]));

        return redirect(route('orders.index'));
    }

    /**
     * Display the specified Order.
     *
     * @param int $id
     * @param ProductOrderDataTable $productOrderDataTable
     *
     * @return Response
     */

    public function show( $id)
    {
        $order = $this->orderRepository->findWithoutFail($id);
        $subtotal = 0;

        if (empty($order)) {
            Flash::error('Order not found');

            return redirect(route('orders.index'));
        }
        foreach ($order->productOrders as $productOrder) {
            $subtotal += $productOrder->price * $productOrder->quantity;
        }

        $total = $subtotal + $order['delivery_fee'];
        $total += ($total * $order['tax'] / 100);
        // $productOrderDataTable->id = $id;

        $products= Product::with("media")->select(
            'products.*',
            'product_translates.name as name',
            'products.price as price',
            'product_orders.quantity as quantity',
        //    'products.media as media'
       )
              
       ->join('product_orders', 'products.id', 'product_orders.product_id')
       ->where('product_orders.order_id', $id)        
       ->join('product_translates', 'products.id', 'product_translates.product_id')
       ->groupBy('products.id')
       ->get();
        // dd($products);
        return view('orders.show', ["order" => $order, "total" => $total, "subtotal" => $subtotal,"products"=>$products]);
    }

    /**
     * Show the form for editing the specified Order.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $order = $this->orderRepository->findWithoutFail($id);
        $market = $order->productOrders()->first();
        $market = isset($market) ? $market->product['market_id'] : 0;
        
        $user = $this->userRepository->where('id',  $order->user->id)->pluck('name', 'id');

        $driver = $this->userRepository->getByCriteria(new DriversOfMarketCriteria($market))->pluck('name', 'id');
        $driver = $driver->put(null, trans('lang.order_driver_id_placeholder'))->sortKeysDesc();
        $orderStatus = $this->orderStatusRepository->pluck('state', 'id');


        if (empty($order)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.order')]));

            return redirect(route('orders.index'));
        }
        $customFieldsValues = $order->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->orderRepository->model());
        $hasCustomField = in_array($this->orderRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('orders.edit')->with('order', $order)->with("customFields", isset($html) ? $html : false)->with("user", $user)->with("driver", $driver)->with("orderStatus", $orderStatus);
    }

    /**
     * Update the specified Order in storage.
     *
     * @param int $id
     * @param UpdateOrderRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateOrderRequest $request)
    {
        $oldOrder = $this->orderRepository->findWithoutFail($id);
        $payment = $this->paymentRepository->where('id', $oldOrder->payment_id)->first();
        if($payment != null && $payment->status == 'Paid'){
            Flash::error('Can not edit');
            return redirect(route('orders.index'));
        }
        
        if (empty($oldOrder)) {
            Flash::error('Order not found');
            return redirect(route('orders.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->orderRepository->model());
        try {
            if (isset($input['driver_id']) && $input['driver_id'] == "0") {
                unset($input['driver_id']);
            }
            $order = $this->orderRepository->update($input, $id);

            if (setting('enable_notifications', false)) {
                if ($input['order_status_id'] != $oldOrder->order_status_id) {
                    self::sendGCM($order->user->device_token, $order);
                    Notification::send([$order->user], new StatusChangedOrder($order));
                }

                if (isset($input['driver_id']) && ($input['driver_id'] != $oldOrder['driver_id'])) {
                    $driver = $this->userRepository->findWithoutFail($input['driver_id']);
                    if (!empty($driver)) {
                        Notification::send([$driver], new AssignedOrder($order));
                    }
                }
            }

            $this->paymentRepository->update([
                "status" => $input['status'],
            ], $order['payment_id']);

            event(new OrderChangedEvent($order));

            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $order->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.order')]));

        return redirect(route('orders.index'));
    }
    public function sendGCM($user_fsm, $order)
    {
        $notification = [
            'title'        => "New Order #".$order->id." to ".$order->productOrders[0]->product->market->name,
            'body'         => $order->user->name,
            'icon'         => $order->productOrders[0]->product->market->getFirstMediaUrl('image', 'thumb'),
            'click_action' => "FLUTTER_NOTIFICATION_CLICK",
            'id' => '1',
            'status' => 'done',
        ];

        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
           'to' =>$user_fsm,
            'notification' =>$notification,
            'data' =>$notification,
            "content_available" => true
        );
        $fields = json_encode($fields);

        $headers = array(
            'Authorization: key=' . setting('fcm_key'),
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

        $result = curl_exec($ch);
        // dd($result);
        curl_close($ch);
    }

       


    /**
     * Remove the specified Order from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $order = $this->orderRepository->findWithoutFail($id);
        $payment = $this->paymentRepository->where('id', $order->payment_id)->first();
        if($payment != null && $payment->status == 'Paid'){
            Flash::error('Can not delete');
            return redirect(route('orders.index'));
        }
        if (empty($order)) {
            Flash::error('Order not found');

            return redirect(route('orders.index'));
        }

        $this->orderRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.order')]));

        return redirect(route('orders.index'));
    }

    /**
     * Remove Media of Order
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $order = $this->orderRepository->findWithoutFail($input['id']);
        try {
            if ($order->hasMedia($input['collection'])) {
                $order->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}