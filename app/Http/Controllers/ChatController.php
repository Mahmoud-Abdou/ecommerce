<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Response;
use App\Repositories\UploadRepository;

class ChatController extends Controller
{
    public function chat_page(Request $request)
    {
        $chats= Chat::where("to", 0)
           ->join("users", "users.id", "chats.user_id")
            ->groupBy("chats.user_id")
            ->orderBy("chats.id")
            ->select("users.name", "users.id")
            ->get();
        // ->paginate($request["length"]);
        return view('chat.index')->with("chats", $chats);
    }
    public function get_chat_list(Request $request)
    {
        $chats= Chat::where("to", 0)
            ->join("users", "users.id", "chats.user_id")
            ->groupBy("chats.user_id")
            ->orderBy("chats.id")
            ->select("users.name", "users.id")
            ->get();
        // ->paginate($request["length"]);
        return $this->sendResponse($chats, 'Done successfully');
    }
    
    public function sendmessage(Request $request)
    {
        $data=[];
        try {
            $chat = new Chat;
            $chat->mess=$request["mess"];
            $chat->user_id=$request["user_id"];
            $chat->to=$request["to"];
            $chat->save();
            $data=[];
            $data["code"]=200;
            $data["message"]="Sent";
            $oldmessage=Chat::
            where("user_id", $request["user_id"])
            ->where("id", ">", $request["id"])
            ->orWhere("to", $request["user_id"])
            ->where("id", ">", $request["id"])
            ->get();

            $userfcm=User::whereId($request['to'] == 0?1:$request['to'])->first()->device_token;
            if ($userfcm) {
                self::sendGCM($request["mess"], $userfcm);
            }

            $data["old"]=$oldmessage;
        } catch (Exception $e) {
            $data["code"]=200;
            $data["error"]=$e;
        }
        return $this->sendResponse($data, 'Done successfully');
    }
    public function getmessage(Request $request)
    {
        $data=[];
        try {
            $data["code"]=200;
            $oldmessage=Chat::
            where("user_id", $request["user_id"])
            ->where("id", ">", $request["id"])
            ->orWhere("to", $request["user_id"])
            ->where("id", ">", $request["id"])
            ->get();
            $data["old"]=$oldmessage;
        } catch (Exception $e) {
            $data["code"]=200;
            $data["error"]=$e;
        }
        return $this->sendResponse($data, 'Done successfully');
    }
    public function test()
    {
        self::sendGCM("test-maesaage", "clmY5Jl6QNG9q09OmLmJqo:APA91bG5qTg8I9aea-ZOb6Vz6ibRm2mymHeFXAQFgvi78KJBeJ_n1vq_IA6Ier0vrS3rIoAPSD9uJp1odv8pUJ-F5jBRGm6bPmZl4wrhorGqpyRMB03iMOES6TY4t8WkeGzq349_gpMv");
    }
    public function sendGCM($message, $user_fsm)
    {
        $this->uploadRepository = new UploadRepository(app());
        $upload = $this->uploadRepository->findByField('uuid', setting('app_logo', ''))->first();
        $appLogo = asset('images/logo_default.png');
        if ($upload && $upload->hasMedia('app_logo')) {
            $appLogo = $upload->getFirstMediaUrl('app_logo');
        }
             $apppps=
        [ 
            "payload"=> [
                "aps"=> [
                    "mutable-content"=> 1
                ]
            ],
            "fcm_options"=>[
                "image"=> $appLogo 
            ]
        ];
        $notification = [
            'title'        => "new message",
            'body'         => $message,
            'icon'         =>  $appLogo,
            'click_action' => "FLUTTER_NOTIFICATION_CLICK",
            'id' => '2',
        ];
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'to' =>$user_fsm,
            'notification' =>$notification,
            'data' =>$notification,
            "apns" =>$apppps,

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
        return 1;
    }
}
