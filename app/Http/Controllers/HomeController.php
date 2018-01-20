<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Permission;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function getAdminPage()
    {
        $users = User::get();
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin',['users' => $users,'roles' => $roles,'permissions' => $permissions]);
    }

    // Assign Role to User
    public function assignRole(Request $request)
    {
        $roles = Role::all();
        $user = User::where('email', $request['user_email'])->first();
        $user->roles()->detach();
        foreach ($roles as $role) {
            if ($request['role_'.$role->name]) {
                $user->roles()->attach(Role::where('name', $role->name)->first());
            }    
        }
        return redirect()->back();
    }

    // Add one Role
    public function addRole(Request $request)
    {
        $request->validate([    
            'name' => 'required|max:10',
            'slug' => 'required|max:10',
            'description' => 'required|max:30'
        ]);

        Role::create($request->all());
        
        return redirect()->back();
    }

    // Assign Permission to Role
    public function assignPermission(Request $request)
    {
        $permissions = Permission::all();
        $role = Role::where('name', $request['role_name'])->first();
        $role->permissions()->detach();
        foreach ($permissions as $permission) {
            if ($request['permission_'.$permission->name]) {
                $role->permissions()->attach(Permission::where('name', $permission->name)->first());
            }    
        }
        return redirect()->back();
    }

    // Add one Role
    public function addPermission(Request $request)
    {
        $request->validate([    
            'name' => 'required|max:10',
            'slug' => 'required|max:10',
            'description' => 'required|max:30'
        ]);

        Permission::create($request->all());
        
        return redirect()->back();
    }
}
