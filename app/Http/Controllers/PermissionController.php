<?php

namespace App\Http\Controllers;

use App\DataTables\PermissionDataTable;
use App\Http\Requests;
use Spatie\Permission\Models\Role;
use App\Http\Requests\CreatePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Repositories\PermissionRepository;
use Flash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Response;
use DB;

class PermissionController extends Controller
{
    /** @var  PermissionRepository */
    private $permissionRepository;

    public function __construct(PermissionRepository $permissionRepo)
    {
        parent::__construct();
        $this->permissionRepository = $permissionRepo;
    }

    /**
     * Display a listing of the Permission.
     *
     * @param PermissionDataTable $permissionDataTable
     * @return Response
     */
    public function index(PermissionDataTable $permissionDataTable)
    {
        $roles = Role::select('id', 'name')->get();
        
        return $permissionDataTable->render('settings.permissions.index')->with('roles', $roles);
    }

    public function refreshPermissions(Request $request)
    {
//        dd('ff');
        Artisan::call('db:seed', ['--class'=> 'DemoPermissionsPermissionsTableSeeder']);
        redirect()->back();
        //Flash::success('Permission refreshed successfully.');

        //return redirect(route('permissions.index'));
    }

    public function givePermissionToRole(Request $request)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning('This is only demo app you can\'t change this section ');
        } else {
            $input = $request->all();
            $this->permissionRepository->givePermissionToRole($input);
        }
    }

    public function revokePermissionToRole(Request $request)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning('This is only demo app you can\'t change this section ');
        } else {
            $input = Request::all();
            $this->permissionRepository->revokePermissionToRole($input);
        }
    }

    public function roleHasPermission(Request $request)
    {
        $input = Request::all();
        //dd($input);
        $result = $this->permissionRepository->roleHasPermission($input);
        return json_encode($result);
    }

    public function get_roles()
    {
        $roles = Role::select('id', 'name')->get();
        $data['roles'] = $roles;
        return $data;
    }

    /**
     * Show the form for creating a new Permission.
     *
     * @return Response
     */
    public function create()
    {
        return view('settings.permissions.create');
    }

    /**
     * Store a newly created Permission in storage.
     *
     * @param CreatePermissionRequest $request
     *
     * @return Response
     */
    public function store(CreatePermissionRequest $request)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning('This is only demo app you can\'t change this section ');
            return redirect(route('permissions.index'));
        }
        $input = $request->all();

        $permission = $this->permissionRepository->create($input);

        Flash::success('Permission saved successfully.');

        return redirect(route('permissions.index'));
    }

    /**
     * Display the specified Permission.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $permission = $this->permissionRepository->findWithoutFail($id);

        if (empty($permission)) {
            Flash::error('Permission not found');

            return redirect(route('permissions.index'));
        }

        return view('settings.permissions.show')->with('permission', $permission);
    }

    /**
     * Show the form for editing the specified Permission.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $permission = $this->permissionRepository->findWithoutFail($id);

        if (empty($permission)) {
            Flash::error('Permission not found');

            return redirect(route('permissions.index'));
        }

        return view('settings.permissions.edit')->with('permission', $permission);
    }

    /**
     * Update the specified Permission in storage.
     *
     * @param  int              $id
     * @param UpdatePermissionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePermissionRequest $request)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning('This is only demo app you can\'t change this section ');
            return redirect(route('permissions.index'));
        }
        $permission = $this->permissionRepository->findWithoutFail($id);

        if (empty($permission)) {
            Flash::error('Permission not found');

            return redirect(route('permissions.index'));
        }

        $permission = $this->permissionRepository->update($request->all(), $id);

        Flash::success('Permission updated successfully.');

        return redirect(route('permissions.index'));
    }

    /**
     * Remove the specified Permission from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning('This is only demo app you can\'t change this section ');
            return redirect(route('permissions.index'));
        }
        $permission = $this->permissionRepository->findWithoutFail($id);

        if (empty($permission)) {
            Flash::error('Permission not found');

            return redirect(route('permissions.index'));
        }

        $this->permissionRepository->delete($id);

        Flash::success('Permission deleted successfully.');

        return redirect(route('permissions.index'));
    }

    public function check_role($permission_id)
    {
        $data = [];
        $permission = DB::table('role_has_permissions')->get();
        foreach ($permission as $per) {
            $data[$per->permission_id][] = $per->role_id;
        }
        return $data;
    }
    
    public function all_permissions(Request $request)
    {
        if ($request->start ==0) {
            $request['page']=1;
        } else {
            $request['page']= ($request->start / $request->length)+1;
        }
        if ($request['search']['value'] != null) {
            $permissions = DB::table('permissions')
            ->where('name', 'like', '%'.$request['search']['value'].'%');
            $count= $permissions->count();
            $permissions= $permissions->paginate($request->length);
        } else {
            $permissions = DB::table('permissions');
            $count= $permissions->count();
            $permissions= $permissions->paginate($request->length);
        }
        $roles_table = Role::select('id', 'name')->get();
        $roles = [];
        $all_permissions = [];
        foreach ($roles_table as $role) {
            $roles[$role->id] = $role->name;
        }
        foreach ($permissions as $permission) {
            $data = [];
            $data['permission_name'] = $permission->name;
            $data['gaurd_name'] = $permission->guard_name;
            $role_permissions = DB::table('role_has_permissions')->where('permission_id', $permission->id)->get();
            foreach ($roles_table as $role) {
                $d = '<div class=\'checkbox icheck\'><label><input  type=\'checkbox\' name=\'namehere\' class=\'permission\' data-role-id=\''.$role->id.'\' data-permission=\''.$permission->name.'\'></label></div>';
                $data[$role->name] = $d;
            }
            foreach ($role_permissions as $role_permission) {
                $d = '<div class=\'checkbox icheck\'><label><input  type=\'checkbox\' name=\'namehere\' class=\'permission\' data-role-id=\''.$role->id.'\' data-permission=\''.$permission->name.'\' checked></label></div>';
                
                $data[$roles[$role_permission->role_id]] = $d;
            }
            $all_permissions[] = $data;
        }
        
        $output = [];
        $output['data'] = $all_permissions;
        $output["recordsTotal"] =  $count;
        $output["recordsFiltered"] = $count;
        return response()->json($output, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
