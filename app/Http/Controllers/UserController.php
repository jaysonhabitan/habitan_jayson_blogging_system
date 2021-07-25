<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['role:Super Admin|Admin'], ['except' => ['index', 'update','create','show', 'store', 'destroy']]); 
        $this->middleware(['permission:manage-user']); 
        $this->middleware(['permission:create-user'], ['only' => ['create', 'store']]); 
        $this->middleware(['permission:update-user'], ['only' => ['show', 'update']]); 
        $this->middleware(['permission:delete-user'], ['only' => ['destroy']]); 

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::when($request->filled('search'), function ($query) use ($request) {
                return $query
                    ->where('name', 'like', "%{$request->query('search')}%");
            })
            ->where('id', '!=', Auth::user()->id)
            ->where('id', '!=', 1)
            ->latest()
            ->with('roles.permissions')
            ->paginate(10);
        
        return view('pages.user.list', [
            'users' => $users,
            'searchQuery' => $request->query('search') ?? '',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('id', '!=', 1)
            ->with('permissions')->get();
        $permissions = Permission::get();

        return view('pages.user.add', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'data' => 'required',
            'data.attributes' => 'required',
            'data.attributes.name' => 'required|string',
            'data.attributes.email' => [
                'required',
                'email',
                Rule::unique('users', 'email'),
            ],
            'data.relationships.permissions' => 'required|array',
            'data.relationships.permissions.*' => 'required|exists:permissions,name'
        ]);
        
        return DB::transaction(function () use ($request) {
            $user = User::make(Arr::except($request->input('data.attributes'), 'password'));
            $user->password = bcrypt($request->input('data.attributes.password'));
            $user->save();

            $role = Role::where('name', $request->input('data.relationships.role'))
                ->with('permissions')
                ->first();
            $permissions = Permission::whereIn('name', $request->input('data.relationships.permissions'))
                ->get();

            $user->syncRoles($role);
            $user->syncPermissions($permissions);
            
            return $this->okResponse();
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $id)
    {
        $user = User::find($id);

        $roles = Role::where('id', '!=', 1)
            ->with('permissions')
            ->get();
        $permissions = Permission::get();
            
        if (!$user) {
            throw (new ModelNotFoundException)->setModel(User::class);
        }

        return view('pages.user.add', [
            'user' => $user->load(['roles', 'permissions']),
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'data' => 'required',
            'data.attributes' => 'required',
            'data.attributes.name' => 'required|string',
            'data.attributes.email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'data.relationships.role' => 'required|string|exists:roles,name',
            'data.relationships.permissions' => 'required|array',
            'data.relationships.permissions.*' => 'required|exists:permissions,name'
        ]);
        
        return DB::transaction(function () use ($request, $user) {
            $user->fill(Arr::except($request->input('data.attributes'), 'password'));
            if ($request->filled('data.attributes.password')) {
                $user->password = bcrypt($request->input('data.attributes.password'));
            }
            $user->update();

            $role = Role::where('name', $request->input('data.relationships.role'))
                ->with('permissions')
                ->first();
            $permissions = Permission::whereIn('name', $request->input('data.relationships.permissions'))
                ->get();

            $user->syncRoles($role);
            $user->syncPermissions($permissions);
            
            return $this->okResponse();
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        if (!optional($user)->delete()) {
            throw (new ModelNotFoundException)->setModel(User::class);
        }

        return redirect(route('users.index'));
    }
}
