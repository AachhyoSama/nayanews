<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\SubAccount;
use Illuminate\Http\Request;
use DataTables;
use GuzzleHttp\Middleware;
use Illuminate\Support\Str;

class SubAccountController extends Controller
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
                $data = SubAccount::latest();
                return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('account', function($row) {
                            $account = Account::where('id', $row->account_id)->first();
                            if(!$account) {
                                $account = Account::where('id', $row->account_id)->onlyTrashed()->first();
                            }
                            return $account->title;
                        })
                        ->addColumn('action', function($row){
                            $editurl = route('sub_account.edit', $row->id);
                            $deleteurl = route('sub_account.destroy', $row->id);
                            $csrf_token = csrf_token();
                            $btn = "<a href='$editurl' class='edit btn btn-primary btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                    <button type='button' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deletesubaccount$row->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                                    <!-- Modal -->
                                        <div class='modal fade text-left' id='deletesubaccount$row->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
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
                        ->rawColumns(['account', 'action'])
                        ->make(true);
            }
            return view('backend.subaccount.index');
        }else{
            return view('backend.permission.permission');
        }

    }
    public function deletedsubindex(Request $request)
    {
        if($request->user()->can('view-accounts')){
            if ($request->ajax()) {
                $data = SubAccount::onlyTrashed()->latest();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $restoreurl = route('restoresubaccount', $row->id);
                        $btn = "<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#restoresubaccount$row->id' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-trash-restore'></i></button>
                                <!-- Modal -->
                                    <div class='modal fade text-left' id='restoresubaccount$row->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
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
                    ->addColumn('account', function($row) {
                        $account = Account::where('id', $row->account_id)->first();
                        if(!$account) {
                            $account = Account::where('id', $row->account_id)->onlyTrashed()->first();
                        }
                        return $account->title;
                    })
                    ->rawColumns(['account', 'action'])
                    ->make(true);
            }
            return view('backend.trash.accounttrash');
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
    //     $accounts = Account::all();
    //     return view('backend.subaccount.create', compact('accounts'));
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
                'subaccount_title' => 'required',
                'account_id' => 'required'
            ]);

            $new_subaccount = SubAccount::create([
                'title' => $request['subaccount_title'],
                'account_id' => $request['account_id'],
                'slug' => Str::slug($request['subaccount_title'])
            ]);

            $new_subaccount->save();

            return redirect()->back()->with('success', 'Sub Account information is saved successfully.');
        }else{
            return view('backend.permission.permission');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubAccount  $subAccount
     * @return \Illuminate\Http\Response
     */
    public function show(SubAccount $subAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SubAccount  $subAccount
     * @return \Illuminate\Http\Response
     */
    public function edit(SubAccount $subAccount, Request $request)
    {
        if($request->user()->can('edit-accounts')){
            $accounts = Account::all();
            return view('backend.subaccount.edit', compact('accounts', 'subAccount'));
        }else{
            return view('backend.permission.permission');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubAccount  $subAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubAccount $subAccount)
    {
        if($request->user()->can('edit-accounts')){
            $this->validate($request, [
                'title' => 'required',
                'account_id' => 'required'
            ]);

            $subAccount->update([
                'title' => $request['title'],
                'account_id' => $request['account_id'],
            ]);

            return redirect()->route('sub_account.index')->with('success', 'Sub Account information is updated successfully.');
        }else{
            return view('backend.permission.permission');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubAccount  $subAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubAccount $subAccount,Request $request)
    {
        if($request->user()->can('remove-accounts')){
            $subAccount->delete();
            return redirect()->route('sub_account.index')->with('success', 'Sub Account information is deleted successfully.');
        }else{
            return view('backend.permission.permission');
        }

    }


    public function restoresubaccount($id, Request $request)
    {
        if($request->user()->can('create-accounts'))
        {
            $sub_account = SubAccount::onlyTrashed()->findorFail($id);
            $main_account = Account::onlyTrashed()->where('id', $sub_account->account_id)->first();
            if($main_account)
            {
                return redirect()->back()->with('error', 'Main Account Type is not present or is soft deleted. Check Main Account.');
            }
            $sub_account->restore();
            return redirect()->route('sub_account.index')->with('success', 'Sub Account type is restored successfully.');
        }
        else
        {
            return view('backend.permission.permission');
        }
    }
}
