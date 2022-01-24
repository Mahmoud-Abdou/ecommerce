<?php

namespace App\Http\Controllers;

use App\Repositories\CurrencyRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UploadRepository;
use App\Repositories\UserRepository;
use App\Repositories\CustomFieldRepository;

use Flash;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use RachidLaasri\LaravelInstaller\Helpers\MigrationsHelper;
use Themsaid\Langman\Manager;
use App\Models\Order;
use App\Models\Product;
use DB;


class AppSettingController extends Controller
{
    use MigrationsHelper;
    /** @var  UserRepository */
    private $userRepository;
    private $customFieldRepository;

    /**
     * @var RoleRepository
     */
    private $roleRepository;

    private $langManager;
    private $uploadRepository;
    private $currencyRepository;

    public function __construct(CustomFieldRepository $customFieldRepo, UserRepository $userRepo, RoleRepository $roleRepo, UploadRepository $uploadRepository, CurrencyRepository $currencyRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepo;
        $this->roleRepository = $roleRepo;
        $this->currencyRepository = $currencyRepository;
        $this->langManager = new Manager(new Filesystem(), config('langman.path'), []);
        $this->uploadRepository = $uploadRepository;
        $this->customFieldRepository = $customFieldRepo;

    }

    public function changeLanguage(Request $request)
    {
        // dd($request);
        // $lang=["data_language" => $request['language_id']];
        // setting($lang)->save();
        session()->put('data_language', $request['language_id']);
        return back();
    }
    public function get_languages(Request $request)
    {
        if (session()->get('data_language') == null) {
            if (setting('data_language') == null) {
                session()->put('data_language', setting('language'));
            } else {
                session()->put('data_language', setting('data_language'));
            }
        }
        $data = [];
        $data['data_language'] =  trans('lang.'.session()->get('data_language'));
        $data['language'] =  trans('lang.'.setting('language'));
        return $data;
    }

    public function update(Request $request)
    {
        if (!env('APP_DEMO', false)) {
            $input = $request->except(['_method', '_token']);
            if (Str::endsWith(url()->previous(), "app/globals")) {
                if (empty($input['app_logo'])) {
                    unset($input['app_logo']);
                }
                if (empty($input['custom_field_models'])) {
                    setting()->forget('custom_field_models');
                }
                if (!isset($input['blocked_ips'])) {
                    unset($input['blocked_ips']);
                    setting()->forget('blocked_ips');
                }
            }

            // foreach($input as $in){

            //     if($in == null ){
                   
            //         Flash::error('Fill all data');
            //         return back();
            //     }
            // }
            $input = array_map(function ($value) {
                return is_null($value)? false : $value;
            }, $input);
          
            setting($input)->save();
            Flash::success(trans('lang.app_setting_global').' updated successfully.');
            Artisan::call("config:clear");
        } else {
            Flash::warning('This is only demo app you can\'t change this section ');
        }

        return redirect()->back();
    }

    public function syncTranslation(Request $request)
    {
        if (!env('APP_DEMO', false)) {
            Artisan::call('langman:sync');
        } else {
            Flash::warning('This is only demo app you can\'t change this section ');
        }
        return redirect()->back();
    }

    public function checkForUpdates(Request $request)
    {
        if (!env('APP_DEMO', false)) {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('cache:forget', ['key' => 'spatie.permission.cache']);
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            $executedMigrations = $this->getExecutedMigrations();
            $newMigrations = $this->getMigrations(config('installer.currentVersion', 'v100'));

            $containsUpdate = !empty($newMigrations) && count(array_intersect($newMigrations, $executedMigrations->toArray())) == count($newMigrations);
            if (!$containsUpdate) {
                return redirect(url('update/' . config('installer.currentVersion', 'v100')));
            }
        }
        Flash::warning(__('lang.app_setting_already_updated'));
        return redirect()->back();
    }

    public function clearCache(Request $request)
    {
        if (!env('APP_DEMO', false)) {
            Artisan::call('cache:forget', ['key' => 'spatie.permission.cache']);
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            Flash::success(__('lang.app_setting_cache_is_cleared'));
        } else {
            Flash::warning('This is only demo app you can\'t change this section ');
        }
        return redirect()->back();
    }

    public function translate(Request $request)
    {
        ini_set('max_input_vars', '4000');

        // dd($request);
        //translate only lang.php file

        // if(!env('APP_DEMO',false)) {
        $inputs = $request->except(['_method', '_token', '_current_lang']);
        $currentLang = $request->input('_current_lang');
        if (!$inputs && !count($inputs)) {
            Flash::error('Translate not loaded');
            return redirect()->back();
        }
        $langFiles = $this->langManager->files();
        $langFiles = array_filter($langFiles, function ($v, $k) {
            return $k == 'lang';
        }, ARRAY_FILTER_USE_BOTH);

        if (!$langFiles && !count($langFiles)) {
            Flash::error('Translate not loaded');
            return redirect()->back();
        }
        foreach ($langFiles as $filename => $items) {
            $path = $items[$currentLang];
            $needed = [];
            foreach ($inputs as $key => $input) {
                if (starts_with($key, $filename)) {
                    $langKeyWithoutFile = explode('|', $key, 2)[1];
                    $needed = array_merge_recursive($needed, getNeededArray('|', $langKeyWithoutFile, $input));
                }
            }
            ksort($needed);
            $this->langManager->writeFile($path, $needed);
        }
        // }else{
        //     Flash::warning('This is only demo app you can\'t change this section ');
        // }

        return redirect()->back();
    }


    public function index($type = null, $tab = null)
    {
        if (empty($type)) {
            Flash::error(trans('lang.app_setting_global').'not found');
            return redirect()->back();
        }
        $executedMigrations = $this->getExecutedMigrations();
        $newMigrations = $this->getMigrations(config('installer.currentVersion', 'v100'));

        $containsUpdate = !empty($newMigrations) && count(array_intersect($newMigrations, $executedMigrations->toArray())) == count($newMigrations);

        $langFiles = [];
        $languages = getAvailableLanguages();
        $mobileLanguages = getLanguages();
        if ($type && $type === 'translation' && $tab) {
            if (!array_has($languages, $tab)) {
                Flash::error('Translate not loaded');
                return redirect()->back();
            }
            $langFiles = $this->langManager->files();
            return view('settings.' . $type . '.index', compact(['languages', 'type', 'tab', 'langFiles']));
        }

        foreach (timezone_abbreviations_list() as $abbr => $timezone) {
            foreach ($timezone as $val) {
                if (isset($val['timezone_id'])) {
                    $group = preg_split('/\//', $val['timezone_id'])[0];
                    $timezones[$group][$val['timezone_id']] = $val['timezone_id'];
                }
            }
        }
        $upload = $this->uploadRepository->findByField('uuid', setting('app_logo'))->first();

        $currencies = $this->currencyRepository->all()->pluck('name_symbol', 'symbol');

        $customFieldModels = getModelsClasses(app_path('Models'));

        return view('settings.' . $type . '.' . $tab . '', compact(['languages', 'type', 'tab', 'langFiles', 'timezones', 'upload', 'customFieldModels', 'currencies', 'mobileLanguages', 'containsUpdate']));
    }

    public function initFirebase()
    {
        return response()->view('vendor.notifications.sw_firebase')->header('Content-Type', 'application/javascript');
    }
    public function checkout()
    {
        $role = ["client"=>"client"];

        $rolesSelected = [];
        $hasCustomField = in_array($this->userRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
            $html = generateCustomField($customFields);
        }

        return view('products.checkout')
            ->with("role", $role)
            ->with("customFields", isset($html) ? $html : false)
            ->with("rolesSelected", $rolesSelected);
     
     
    }


    public function setlang($langu)
    {
        // $languages = getAvailableLanguages();

        $language=$langu;
        //  dd( $language);
        $lang=["language" => $language];
        setting($lang)->save();
        return back();
    }

    public function get_drivers_report(Request $request){
        //   $driver_id=$request->driverid==null?'drivers.id':$request->driverid;
        //   dd($driver_id);
        $columns=array(
            0 =>"paid_date",
            1 => "order_status_id",
            2 => "total_orders",
            3 => "price",
            4 => "delivery_fee",
            5 => "earning"
        );
        $tableorder=array(
            0 =>"paid_date",
            1 => "order_status_id",
            2 => "total_orders",
            3 => "price",
            4 => "delivery_fee",
            5 => "earning"
        );
        // self::set_lang();
        // dd($request->get('driverid'));
        if ($request->start ==0) {
            $request['page']=1;
        } else {
            $request['page']= ($request->start / $request->length)+1;
        }

        $drivers= Order::select(
            'orders.id',
            'orders.updated_at as paid_date',
            'orders.order_status_id as order_status_id',
            // 'drivers.total_orders as total_orders',
            'payments.price as price',
            'orders.delivery_fee as delivery_fee',
            // 'drivers.earning as earning'
        )
               
        ->join('payments', 'payments.id', 'orders.payment_id')
        // ->join('drivers', 'drivers.id', 'orders.driver_id')
        // ->when($request->get('driverid'), function($query) use ($request) {
        //     // dd( $request->get('driverid'));
        //     $query->where('drivers.id', '=', $request->get('driverid')); 
        // })
        //  ->when($request->get('to'), function($query) use ($request) {
        //     $query ->whereBetween('orders.updated_at', [$request['from']." 00:00:00",$request['to']." 23:59:59"]);
        // })
        ;
     
        $count=$drivers->count();
        $drivers=$drivers->paginate($request['length']);
    //    dd($drivers);
       
        $data["data"]=$drivers;
        $data["recordsTotal"] =  $count;
        $data["recordsFiltered"] = $count;
        
        return response()->json($drivers, 200, [], JSON_UNESCAPED_UNICODE);
        
    }

    public function get_product_orders(Request $request){
       
       

        $drivers= Product::select(
             'products.id',
            'product_translates.name as name',
            'products.price as price',
            'product_orders.quantity as quantity',
            // 'drivers.earning as earning'
        )
               
        ->join('product_orders', 'products.id', 'product_orders.product_id')
        ->where('product_orders.order_id', $request->get('id'))        
        ->join('product_translates', 'products.id', 'product_translates.product_id')
        ->groupBy('products.id')
        ->get()
        ;
     
        // $count=$drivers->count();
        // $drivers=$drivers->paginate($request['length']);
    //    dd($drivers);
       
        // $data["data"]=$drivers;
        // $data["recordsTotal"] =  $count;
        // $data["recordsFiltered"] = $count;
        
        return response()->json($drivers, 200, [], JSON_UNESCAPED_UNICODE);
        
    }

    
    public function get_drivers(Request $request){
        $drivers=DB::table('driver_markets')
        ->join("users", "users.id","driver_markets.user_id")
        
        ->select(
            "users.id",
            "users.name",
            )
        ->get()->toArray();
       
        $data["data"]=$drivers;
     
        
        return response()->json($drivers, 200, [], JSON_UNESCAPED_UNICODE);
    }
}

