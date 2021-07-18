<?php

namespace App\Http\Controllers;

use App\Models\DailyExpenses;
use App\Models\Vendor;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Storage;

class DailyExpensesController extends Controller
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
        if($request->user()->can('view-daily-expenses')){
            if ($request->ajax()) {
                $data = DailyExpenses::latest();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('bill_amount', function($row) {
                        $bill_amount = 'Rs. '. $row->bill_amount;
                        return $bill_amount;
                    })
                    ->addColumn('bill_image', function($row) {
                        if ($row->bill_image == null) {
                            $location = Storage::disk('uploads')->url('noimage.jpg');
                        } else {
                            $location = Storage::disk('uploads')->url($row->bill_image);
                        }
                        $bill_image = "<a href='$location' target='_blank'><img src='$location' style='max-height: 100px;'></a>";
                        return $bill_image;
                    })
                    ->addColumn('paid_amount', function($row) {
                        $paid_amount = 'Rs. '. $row->paid_amount;
                        return $paid_amount;
                    })

                    ->addColumn('vendor_name', function($row) {
                        $vendor_name = $row->vendor->company_name;
                        return $vendor_name;
                    })
                    ->addColumn('action', function($row){
                        $editurl = route('dailyexpenses.edit', $row->id);
                        $deleteurl = route('dailyexpenses.destroy', $row->id);
                        $csrf_token = csrf_token();
                        $btn = "<a href='$editurl' class='edit btn btn-primary btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                <button type='button' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deleteexpenses$row->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                                <!-- Modal -->
                                    <div class='modal fade text-left' id='deleteexpenses$row->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
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
                    ->rawColumns(['bill_amount', 'bill_image', 'paid_amount', 'vendor_name', 'action'])
                    ->make(true);
            }
            return view('backend.dailyexpenses.index');
        }else{
            return view('backend.permission.permission');
        }
    }

    public function deletedexpenses(Request $request)
    {
        if($request->user()->can('view-daily-expenses')){
            if ($request->ajax()) {
                $data = DailyExpenses::onlyTrashed()->latest();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('bill_amount', function($row) {
                        $bill_amount = 'Rs. '. $row->bill_amount;
                        return $bill_amount;
                    })
                    ->addColumn('bill_image', function($row) {
                        if ($row->bill_image == null) {
                            $location = Storage::disk('uploads')->url('noimage.jpg');
                        } else {
                            $location = Storage::disk('uploads')->url($row->bill_image);
                        }
                        $bill_image = "<a href='$location' target='_blank'><img src='$location' style='max-height: 100px;'></a>";
                        return $bill_image;
                    })
                    ->addColumn('paid_amount', function($row) {
                        $paid_amount = 'Rs. '. $row->paid_amount;
                        return $paid_amount;
                    })

                    ->addColumn('vendor_name', function($row) {
                        $vendor = Vendor::where('id', $row->vendor_id)->first();
                        if(!$vendor) {
                            $vendor = Vendor::where('id', $row->vendor_id)->onlyTrashed()->first();
                        }
                        return $vendor->company_name;
                    })
                    ->addColumn('action', function($row){
                        $restoreurl = route('restoreexpenses', $row->id);
                            $btn = "<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#cancellation$row->id' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-trash-restore'></i></button>
                                <!-- Modal -->
                                    <div class='modal fade text-left' id='cancellation$row->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
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
                    ->rawColumns(['bill_amount', 'bill_image', 'paid_amount', 'vendor_name', 'action'])
                    ->make(true);
            }
            return view('backend.trash.expensestrash');
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
        if($request->user()->can('create-daily-expenses')){
            $vendors = Vendor::latest()->get();
            return view('backend.dailyexpenses.create', compact('vendors'));
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
        if($request->user()->can('create-daily-expenses')){
            $this->validate($request, [
                'bill_number' => 'required',
                'date' => 'required',
                'vendor_name' => 'required',
                'bill_amount' => 'required',
                'paid_amount' => 'required',
                'purpose' => 'required',
                'bill_image' => ''
            ]);

            if($request->hasfile('bill_image')) {
                $image = $request->file('bill_image');
                $imagename = $image->store('bill_images', 'uploads');
                $dailyExpenses = DailyExpenses::create([
                    'vendor_id' => $request['vendor_name'],
                    'date' => $request['date'],
                    'bill_image' => $imagename,
                    'bill_number' => $request['bill_number'],
                    'bill_amount' => $request['bill_amount'],
                    'paid_amount' => $request['paid_amount'],
                    'purpose' => $request['purpose'],
                ]);
            } else {
                $dailyExpenses = DailyExpenses::create([
                    'vendor_id' => $request['vendor_name'],
                    'date' => $request['date'],
                    'bill_number' => $request['bill_number'],
                    'bill_amount' => $request['bill_amount'],
                    'paid_amount' => $request['paid_amount'],
                    'purpose' => $request['purpose'],
                ]);
            }

            $dailyExpenses->save();
            return redirect()->route('dailyexpenses.index')->with('success', 'Daily expenses is saved successfully.');
        }else{
            return view('backend.permission.permission');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DailyExpenses  $dailyExpenses
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DailyExpenses  $dailyExpenses
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        if($request->user()->can('edit-daily-expenses')){
            $dailyExpenses = DailyExpenses::findorFail($id);
            $vendors = Vendor::latest()->get();
            return view('backend.dailyexpenses.edit', compact('dailyExpenses', 'vendors'));
        }else{
            return view('backend.permission.permission');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DailyExpenses  $dailyExpenses
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->user()->can('edit-daily-expenses')){
            $dailyExpenses = DailyExpenses::findorFail($id);

            $this->validate($request, [
                'bill_number' => 'required',
                'date' => 'required',
                'bill_image' => '',
                'vendor_name' => 'required',
                'bill_amount' => 'required',
                'paid_amount' => 'required',
                'purpose' => 'required'
            ]);

            if($request->hasfile('bill_image')) {
                // Storage::disk('uploads')->delete($dailyExpenses->bill_image);
                $image = $request->file('bill_image');
                $imagename = $image->store('bill_images', 'uploads');
                $dailyExpenses->update([
                    'vendor_id' => $request['vendor_name'],
                    'date' => $request['date'],
                    'bill_image' => $imagename,
                    'bill_number' => $request['bill_number'],
                    'bill_amount' => $request['bill_amount'],
                    'paid_amount' => $request['paid_amount'],
                    'purpose' => $request['purpose'],
                ]);
            } else {
                $dailyExpenses->update([
                    'vendor_id' => $request['vendor_name'],
                    'date' => $request['date'],
                    'bill_number' => $request['bill_number'],
                    'bill_amount' => $request['bill_amount'],
                    'paid_amount' => $request['paid_amount'],
                    'purpose' => $request['purpose'],
                ]);
            }
            return redirect()->route('dailyexpenses.index')->with('success', 'Daily expenses is updated successfully.');
        }else{
            return view('backend.permission.permission');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DailyExpenses  $dailyExpenses
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if($request->user()->can('remove-daily-expenses')){
            $dailyExpenses = DailyExpenses::findorFail($id);
            $dailyExpenses->delete();

            return redirect()->route('dailyexpenses.index')->with('success', 'Daily expenses is deleted successfully.');
        }else{
            return view('backend.permission.permission');
        }
    }

    public function restoreexpenses($id, Request $request)
    {
        $deleted_expenses = DailyExpenses::onlyTrashed()->findorFail($id);
        $vendor = Vendor::onlyTrashed()->where('id', $deleted_expenses->vendor_id)->first();
        if($vendor)
        {
            return redirect()->back()->with('error', 'Supplier is not present or is soft deleted. Check Suppliers.');
        }

        $deleted_expenses->restore();
        return redirect()->route('dailyexpenses.index')->with('success', 'Expenses information is restored successfully.');
    }
}
