<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\CustomFieldRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UploadRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use DB;
class UserAPIController extends Controller
{
    private $userRepository;
    private $uploadRepository;
    private $roleRepository;
    private $customFieldRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository, UploadRepository $uploadRepository, RoleRepository $roleRepository, CustomFieldRepository $customFieldRepo)
    {
        $this->userRepository = $userRepository;
        $this->uploadRepository = $uploadRepository;
        $this->roleRepository = $roleRepository;
        $this->customFieldRepository = $customFieldRepo;
    }

    public function social_login(Request $request)
    {
        if (auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            $user = auth()->user();
            $user->device_token = $request->input('device_token', '');
            $user->save();
            return $this->sendResponse($user, 'User retrieved successfully');
        } else {
            self::register($request);
            if (auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
                $user = auth()->user();
                $user->device_token = $request->input('device_token', '');
                $user->save();
                return $this->sendResponse($user, 'User retrieved successfully');
            }
        }
    }

    public function login(Request $request)
    {
        if (auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            // Authentication passed...
            $user = auth()->user();
            $user->device_token = $request->input('device_token', '');
            $user->save();
            $admin=DB::table('model_has_roles')
            ->where("role_id", 2)
            ->where("model_id",  $user->id)->first();
            if($admin != null){
                $user->is_admin=1;
            }else{
                $user->is_admin=0;
            }
            return $this->sendResponse($user, 'User retrieved successfully');
        }

        return $this->sendResponse([
            'error' => 'Unauthenticated user',
            'code' => 401,
        ], 'User not logged');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return
     */
    public function register(Request $request)
    {
        // $user =User::where("email", $request->input('email'))->first();
        // if ($user != null) {
        //     return $this->sendResponse(["dublicated"], 'User retrieved successfully');
        // }

        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->api_token = str_random(60);
        $user->device_token = $request->input('device_token', '');
        $user->save();

        $defaultRoles = $this->roleRepository->findByField('default', '1');
        $defaultRoles = $defaultRoles->pluck('name')->toArray();
        $user->assignRole($defaultRoles);


        if (copy(public_path('images/avatar_default.png'), public_path('images/avatar_default_temp.png'))) {
            $user->addMedia(public_path('images/avatar_default_temp.png'))
                ->withCustomProperties(['uuid' => bcrypt(str_random())])
                ->toMediaCollection('avatar');
        }


        return $this->sendResponse($user, 'User retrieved successfully');
    }

    public function logout(Request $request)
    {
        $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();
        if (!$user) {
            return $this->sendResponse([
                'error' => true,
                'code' => 404,
            ], 'User not found');
        }
        try {
            auth()->logout();
        } catch (ValidatorException $e) {
            return $this->sendResponse([
                'error' => true,
                'code' => 404,
            ], 'User not found');
        }
        return $this->sendResponse($user['name'], 'User logout successfully');
    }

    public function user(Request $request)
    {
        $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();

        if (!$user) {
            return $this->sendResponse([
                'error' => true,
                'code' => 404,
            ], 'User not found');
        }

        return $this->sendResponse($user, 'User retrieved successfully');
    }

    public function settings(Request $request)
    {
        $settings = setting()->all();
        //Log::warning($settings);
        $settings = array_intersect_key(
            $settings,
            [
                'default_tax' => '',
                'default_currency' => '',
                'app_name' => '',
                'currency_right' => '',
                'enable_paypal' => '',
                'enable_stripe' => '',
                'main_color' => '',
                'main_dark_color' => '',
                'second_color' => '',
                'second_dark_color' => '',
                'accent_color' => '',
                'accent_dark_color' => '',
                'scaffold_dark_color' => '',
                'scaffold_color' => '',
                'google_maps_key' => '',
                'mobile_language' => '',
                'app_version' => '',
                'enable_version' => '',
                'distance_unit' => '',
            ]
        );

        if (!$settings) {
            return $this->sendResponse([
                'error' => true,
                'code' => 404,
            ], 'Settings not found');
        }

        return $this->sendResponse($settings, 'Settings retrieved successfully');
    }

    /**
     * Update the specified User in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            return $this->sendResponse([
                'error' => true,
                'code' => 404,
            ], 'User not found');
        }
        $input = $request->except(['password', 'api_token']);
        try {
            if ($request->has('device_token')) {
                $user = $this->userRepository->update($request->only('device_token'), $id);
            } else {
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
                $user = $this->userRepository->update($input, $id);
                
                foreach (getCustomFieldsValues($customFields, $request) as $value) {
                    $user->customFieldsValues()
                        ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
                }
            }
        } catch (ValidatorException $e) {
            return $this->sendResponse([
                'error' => true,
                'code' => 404,
            ], $e->getMessage());
        }

        return $this->sendResponse($user, __('lang.updated_successfully', ['operator' => __('lang.user')]));
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $response = Password::broker()->sendResetLink(
            $request->only('email')
        );

        if ($response == Password::RESET_LINK_SENT) {
            return $this->sendResponse(true, 'Reset link was sent successfully');
        } else {
            return $this->sendError([
                'error' => 'Reset link not sent',
                'code' => 401,
            ], 'Reset link not sent');
        }
    }
}
