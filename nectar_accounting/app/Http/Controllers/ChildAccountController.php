<?php

namespace App\Http\Controllers;

use App\Models\ChildAccount;
use App\Models\SubAccount;
use Illuminate\Http\Request;
use DataTables;
Use Illuminate\Support\Str;

class ChildAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        if($request->user()->can('view-accounts')){
            if ($request->ajax()) {
            $data = ChildAccount::latest();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('sub_account', function($row) {
                        $sub_account = $row->subAccount->title;
                        return $sub_account;
                    })
                    ->addColumn('action', function($row){
                        $editurl = route('child_account.edit', $row->id);
                        $deleteurl = route('child_account.destroy', $row->id);
                        $csrf_token = csrf_token();
                            $btn = "<a href='$editurl' class='edit btn btn-primary btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                    <button type='button' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deletechild$row->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                                    <!-- Modal -->
                                        <div class='modal fade text-left' id='deletechild$row->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
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
                    ->rawColumns(['sub_account', 'action'])
                    ->make(true);
            }
            return view('backend.childaccount.index');
        }else{
            return view('backend.permission.permission');
        }

    }

    public function deletedchildindex(Request $request)
    {
        if($request->user()->can('view-accounts')){
            if ($request->ajax()) {
                $data = ChildAccount::onlyTrashed()->latest();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){

                        $restoreurl = route('restorechildaccount', $row->id);
                        $btn = "<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#restorechild$row->id' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-trash-restore'></i></button>
                                <!-- Modal -->
                                    <div class='modal fade text-left' id='restorechild$row->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
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
                    ->addColumn('sub_account', function($row) {
                        $sub_account = SubAccount::where('id', $row->sub_account_id)->first();
                        if(!$sub_account) {
                            $sub_account = SubAccount::where('id', $row->sub_account_id)->onlyTrashed()->first();
                        }
                        return $sub_account->title;
                    })
                    ->rawColumns(['sub_account', 'action'])
                    ->make(true);
            }
            return view('backend.childaccount.index');
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     $sub_accounts = SubAccount::latest()->get();
    //     return view('backend.childaccount.create', compact('sub_accounts'));
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->user()->can('create-accounts')){
            $this->validate($request, [
                'childaccount_title' => 'required',
                'sub_account_id' => 'required'
            ]);

            $new_childaccount = ChildAccount::create([
                'title' => $request['childaccount_title'],
                'sub_account_id' => $request['sub_account_id'],
                'slug' => Str::slug($request['childaccount_title'])
            ]);

            $new_childaccount->save();

            return redirect()->back()->with('success', 'Child Account information is saved successfully.');
        }else{
            return view('backend.permission.permission');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ChildAccount  $childAccount
     * @return \Illuminate\Http\Response
     */
    public function show(ChildAccount $childAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ChildAccount  $childAccount
     * @return \Illuminate\Http\Response
     */
    public function edit(ChildAccount $childAccount,Request $request)
    {
        if($request->user()->can('edit-accounts')){
            $sub_accounts = SubAccount::latest()->get();
            return view('backend.childaccount.edit', compact('sub_accounts', 'childAccount'));
        }else{
            return view('backend.permission.permission');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ChildAccount  $childAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChildAccount $childAccount)
    {
        if($request->user()->can('edit-accounts')){
            $this->validate($request, [
                'title' => 'required',
                'sub_account_id' => 'required'
            ]);

            $childAccount->update([
                'title' => $request['title'],
                'sub_account_id' => $request['sub_account_id'],
            ]);

            return redirect()->route('child_account.index')->with('success', 'Child Account information is updated successfully.');
        }else{
            return view('backend.permission.permission');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ChildAccount  $childAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChildAccount $childAccount, Request $request)
    {
        if($request->user()->can('remove-accounts')){
            $childAccount->delete();
            return redirect()->route('child_account.index')->with('success', 'Child Account information is deleted successfully.');
        }else{
            return view('backend.permission.permission');
        }
    }

    public function restorechildaccount($id, Request $request)
    {
        if($request->user()->can('create-accounts'))
        {
            $child_account = ChildAccount::onlyTrashed()->findorFail($id);

            $sub_account = SubAccount::onlyTrashed()->where('id', $child_account->sub_account_id)->first();
            if($sub_account)
            {
                return redirect()->back()->with('error', 'Sub Account Type is not present or is soft deleted. Check Sub Account.');
            }
            $child_account->restore();
            return redirect()->route('child_account.index')->with('success', 'Child Account type is restored successfully.');
        }
        else
        {
            return view('backend.permission.permission');
        }
    }
}
