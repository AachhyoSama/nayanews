<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\SubAccount;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Str;

class AccountController extends Controller
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
                $data = Account::latest();
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($row){

                            $editurl = route('account.edit', $row->id);
                            $deleteurl = route('account.destroy', $row->id);
                            $csrf_token = csrf_token();
                            $btn = "<a href='$editurl' class='edit btn btn-primary btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                    <button type='button' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deleteaccount$row->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                                    <!-- Modal -->
                                        <div class='modal fade text-left' id='deleteaccount$row->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
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
                        ->rawColumns(['action'])
                        ->make(true);
            }
            return view('backend.account.index');
        }else{
            return view('backend.permission.permission');
        }
    }

    public function deletedindex(Request $request)
    {
        if($request->user()->can('view-accounts')){
            if ($request->ajax()) {
                $data = Account::onlyTrashed()->latest();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){

                        $restoreurl = route('restore', $row->id);
                        $btn = "<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#restoreaccount$row->id' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-trash-restore'></i></button>
                                <!-- Modal -->
                                    <div class='modal fade text-left' id='restoreaccount$row->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
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
                    ->rawColumns(['action'])
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
    public function create(Request $request)
    {
        if($request->user()->can('create-accounts')){
            $accounts = Account::all();
            $sub_accounts = SubAccount::latest()->get();
            return view('backend.account.create', compact('accounts', 'sub_accounts'));
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
        if($request->user()->can('create-accounts')){
            $this->validate($request, [
                'account_title' => 'required'
            ]);

            $new_account = Account::create([
                'title' => $request['account_title'],
                'slug' => Str::slug($request['account_title'], '-')
            ]);

            $new_account->save();
            return redirect()->back()->with('success', 'Account type is saved successfully.');
        }else{
            return view('backend.permission.permission');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account, Request $request)
    {
        if($request->user()->can('edit-accounts')){
            return view('backend.account.edit', compact('account'));
        }else{
            return view('backend.permission.permission');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $account)
    {
        if($request->user()->can('edit-accounts')){
            $this->validate($request, [
                'title' => 'required'
            ]);

            $account->update([
                'title' => $request['title'],
                'slug' => Str::slug($request['title'], '-')
            ]);

            return redirect()->route('account.index')->with('success', 'Account type is updated successfully.');
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account, Request $request)
    {
        if($request->user()->can('create-accounts')){
            $account->delete();
            return redirect()->route('account.index')->with('success', 'Account type is deleted successfully. Check Trash.');
        }else{
            return view('backend.permission.permission');
        }
    }

    public function restore($id, Request $request)
    {
        if($request->user()->can('create-accounts')){
            $account = Account::onlyTrashed()->findorFail($id);
            $account->restore();
            return redirect()->route('account.index')->with('success', 'Account type is restored successfully.');
        }else{
            return view('backend.permission.permission');
        }
    }
}
