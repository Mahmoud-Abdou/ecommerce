<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CategoryRepository;
use Woocommerce;

class SolidController extends Controller
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    
    private $language_id;

    public function __construct(CategoryRepository $categoryRepo)
    {
        parent::__construct();
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
    public function get_sub_category(Request $request)
    {
        $category = $this->categoryRepository->join('category_translates', 'category_translates.category_id', 'categories.id')
                                            ->where('category_translates.language_id', $this->language_id)
                                            ->where("parent_id", $request->id)->select('category_translates.name', 'categories.id')
                                            ->get();
        return response()->json($category, 200, [], JSON_UNESCAPED_UNICODE);
    }
    public function sendGCM($user_fsm)
    {
        $notification = [
            'title'        => "New Order #",
            'body'         => "111",
            // 'icon'         => $order->productOrders[0]->product->market->getFirstMediaUrl('image', 'thumb'),
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
            'Authorization: key=' . "AAAAj-DVUWA:APA91bEb__JdYfDuDUe3tyyZdLoFP3Td-qepe3m8lAL6vgDfvy4n7xgUEOBS4WnI4W0K5h-HdfCRzoZx8kT6kxUYDeFeY0GIQTp1vOXNWuPQIxT9zccX1yoLNgt4PbWrrpkjaMu5lHC7",
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

        $result = curl_exec($ch);
        dd($result);
        curl_close($ch);
    }
    public function test()
    {
        self::sendGCM("e_DaEt9ERa2jF3jVbgsl7E:APA91bGGBwAb-2NzozPj76uGyG0IYsh64g9vv4J3IdfuAFXrnGxWMmY9axNaVtrtBnRV_eHXvxnRuAcH1MNrTSJZnQC6oUekLIY0LNyjY2H0aDNLpT9qmThrltefwDuip6dfAHctIDsb");
    }

   public function public(){
    //   return Woocommerce::get('');
        $data = [
            'status' => 'completed',
            'filter' => [
                'created_at_min' => '2016-01-14'
            ]
        ];

        // $result = Woocommerce::get('orders', $data);
        // dd($result );
        // foreach($result['orders'] as $order)
        // {
        //     // do something with $order
        // }

        // you can also use array access
        $orders = Woocommerce::get('orders', $data)['orders'];
            dd($orders);
        foreach($orders as $order)
        {
            // do something with $order
        }
   }

}
