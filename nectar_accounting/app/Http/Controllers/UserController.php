<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if($request->user()->can('view-user')){
            if ($request->ajax()) {
                $data = User::latest();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('roles', function($row) {
                        $userrole = UserRole::where('user_id', $row->id)->first();
                        $role = Role::where('id', $userrole->role_id)->first();
                        return $role->name;
                    })
                    ->addColumn('action', function($row){
                        $editurl = route('user.edit', $row->id);
                        $deleteurl = route('user.destroy', $row->id);
                        $csrf_token = csrf_token();
                            $btn = "<a href='$editurl' class='edit btn btn-primary btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                    <button type='button' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deleteuser$row->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                                    <!-- Modal -->
                                        <div class='modal fade text-left' id='deleteuser$row->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                            <div class='modal-dialog' role='document'>
                                                <div class='modal-content'>
                                                    <div class='modal-header'>
                                                    <h5 class='modal-title' id='exampleModalLabel'>Delete Confirmation</h5>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                        <span aria-hidden='true'>&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class='modal-body text-center'>
                                                        <form action='$deleteurl' method='POST' style='display:inline-block;'>
                                                        <input type='hidden' name='_token' value='$csrf_token'>
                                                        <label for='reason'>Are you sure you want to delete??</label><br>
                                                        <input type='hidden' name='_method' value='DELETE' />
                                                            <button type='submit' class='btn btn-danger' title='Delete'>Confirm Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    ";
                            return $btn;
                    })
                    ->rawColumns(['roles', 'action'])
                    ->make(true);
                }

            return view('backend.users.index');
        }else{
            return view('backend.permission.permission');
        }
    }

    public function deletedusers(Request $request)
    {
        //
        if($request->user()->can('view-user')){
            if ($request->ajax()) {
                $data = User::onlyTrashed()->latest();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('roles', function($row) {
                        $userrole = UserRole::where('user_id', $row->id)->first();
                        $role = Role::where('id', $userrole->role_id)->first();
                        if(!$role) {
                            $role = Role::where('id', $userrole->role_id)->onlyTrashed()->first();
                        }
                        return $role->name;
                    })
                    ->addColumn('action', function($row){
                        $restoreurl = route('restoreusers', $row->id);
                        $btn = "<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#restoreuser$row->id' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-trash-restore'></i></button>
                                <!-- Modal -->
                                    <div class='modal fade text-left' id='restoreuser$row->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                        <div class='modal-dialog' role='document'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                <h5 class='modal-title' id='exampleModalLabel'>Restore Confirmation</h5>
                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                    <span aria-hidden='true'>&times;</span>
                                                </button>
                                                </div>
                                                <div class='modal-body text-center'>
                                                    <label for='reason'>Are you sure you want to restore??</label><br>
                                                    <a href='$restoreurl' class='edit btn btn-primary btn-sm' title='Restore'>Confirm Restore</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ";
                                return $btn;
                    })
                    ->rawColumns(['roles', 'action'])
                    ->make(true);
                }

            return view('backend.trash.userroletrash');
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        if($request->user()->can('create-user')){
            $roles = Role::all();
            return view('backend.users.create', compact('roles'));
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if($request->user()->can('create-user')){
            $data = $this->validate($request, [
                'name'=>'required|string|max:255',
                'email'=>'required|string|email|max:255|unique:users',
                'role_id'=>'required',
                'password' => 'sometimes|min:8|confirmed',
            ]);
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            $user->roles()->attach($data['role_id']);
            $permissions = RolePermission::where('role_id', $data['role_id'])->get();
            $selectedperm = array();
                foreach($permissions as $permission){
                    $selectedperm[] = $permission->permission_id;
                }
            $user->permissions()->attach($selectedperm);
            $user->save();
            return redirect()->route('user.index')->with('success', 'User Successfully Created');
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        //
        if($request->user()->can('edit-user')){
            $roles = Role::all();
            $userrole = UserRole::where('user_id', $id)->first();
            $user = User::findorfail($id);
            return view('backend.users.edit', compact('roles', 'userrole', 'user'));
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        if($request->user()->can('edit-user')){
            $user = User::findorfail($id);
            if(isset($_POST['updatedetails'])){
                $data = $this->validate($request, [
                    'name'=>'required|string|max:255',
                    'email'=>'required|string|email|max:255|unique:users,email,'.$user->id,
                    'role_id'=>'required',
                ]);
                $user->update([
                    'name' => $data['name'],
                    'email' => $data['email'],
                ]);
                $user->roles()->sync($data['role_id']);
                $permissions = RolePermission::where('role_id', $data['role_id'])->get();
                $selectedperm = array();
                    foreach($permissions as $permission){
                        $selectedperm[] = $permission->permission_id;
                    }
                $user->permissions()->sync($selectedperm);
                $user->save();
                return redirect()->route('user.index')->with('success', 'UserDetails Successfully updated');
            }
            elseif(isset($_POST['updatepassword'])){
                $data = $this->validate($request, [
                    'oldpassword' => 'required',
                    'new_password' => 'sometimes|min:8|confirmed|different:password',
                ]);

                if (Hash::check($data['oldpassword'], $user->password)) {
                    if (!Hash::check($data['new_password'] , $user->password)) {
                        $newpass = Hash::make($data['new_password']);

                        $user->update([
                            'password' => $newpass,
                        ]);
                        $user->save;
                        session()->flash('success','password updated successfully');
                        return redirect()->route('user.index');
                    }

                    else{
                            session()->flash('error','new password can not be the old password!');
                            return redirect()->back();
                        }

                    }

                else {
                    session()->flash('errorpass', 'Password does not match');
                    return redirect()->back();
                }
            }

        }else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        //
        if($request->user()->can('remove-user')){
            $user = User::findorfail($id);
            $user->delete();
            return redirect()->route('user.index')->with('success', "User Successfully Deleted");
        }else{
            return view('backend.permission.permission');
        }
    }

    public function restoreusers($id)
    {
        $user = User::onlyTrashed()->findorFail($id);
        $userrole = UserRole::where('user_id', $user->id)->first();
        $role = Role::where('id', $userrole->role_id)->onlyTrashed()->first();
        if($role)
        {
            return redirect()->back()->with('error', 'Role for user is not present or is soft deleted. Check Roles.');
        }
        $user->restore();
        return redirect()->route('user.index')->with('success', 'User is restored successfully.');
    }
}
