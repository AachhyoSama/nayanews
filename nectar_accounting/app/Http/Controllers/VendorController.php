<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Province;
use App\Models\Vendor;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
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
        if($request->user()->can('view-vendor')){
            if ($request->ajax()) {
                $data = Vendor::latest();
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('pan_vat', function($row) {
                        if($row->pan_vat == null){
                            $pan = "Not provided";
                        } else {
                            $pan = $row->pan_vat;
                        }
                            return $pan;
                        })
                        ->addColumn('action', function($row){
                            $showurl = route('vendors.show', $row->id);
                            $editurl = route('vendors.edit', $row->id);
                            $deleteurl = route('vendors.destroy', $row->id);
                            $csrf_token = csrf_token();
                            $btn = "<a href='$showurl' class='edit btn btn-success btn-sm' title='View'><i class='fa fa-eye'></i></a>
                                    <a href='$editurl' class='edit btn btn-primary btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                    <button type='button' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deletevendor$row->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                                    <!-- Modal -->
                                        <div class='modal fade text-left' id='deletevendor$row->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
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
                        ->rawColumns(['pan_vat', 'action'])
                        ->make(true);
            }
        }else{
            return view('backend.permission.permission');
        }

        return view('backend.vendors.index');
    }

    public function deletedvendor(Request $request)
    {
        if($request->user()->can('view-vendor')){
            if ($request->ajax()) {
                $data = Vendor::onlyTrashed()->latest();
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('pan_vat', function($row) {
                        if($row->pan_vat == null){
                            $pan = "Not provided";
                        } else {
                            $pan = $row->pan_vat;
                        }
                            return $pan;
                        })
                        ->addColumn('action', function($row){
                            $restoreurl = route('restorevendor', $row->id);
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
                        ->rawColumns(['pan_vat', 'action'])
                        ->make(true);
            }
        }else{
            return view('backend.permission.permission');
        }
        return view('backend.trash.suppliertrash');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request->user()->can('create-vendor')){
            $provinces = Province::all();
            return view('backend.vendors.create', compact('provinces'));
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
        // dd($request['district']);
        if($request->user()->can('create-vendor')){
            $this->validate($request, [
                'company_name' => 'required',
                'company_email' => 'required',
                'company_phone' => 'required',
                'province' => 'required',
                'district' => 'required',
                'company_address' => 'required',
                'pan_vat' => '',
                'concerned_name' => 'required',
                'concerned_phone' => 'required',
                'concerned_email' => 'required',
                'designation' => 'required',
            ]);

            $new_vendor = Vendor::create([
                'company_name' => $request['company_name'],
                'company_email' => $request['company_email'],
                'company_phone' => $request['company_phone'],
                'province_id' => $request['province'],
                'district_id' => $request['district'],
                'company_address' => $request['company_address'],
                'pan_vat' => $request['pan_vat'],
                'concerned_name' => $request['concerned_name'],
                'concerned_phone' => $request['concerned_phone'],
                'concerned_email' => $request['concerned_email'],
                'designation' => $request['designation'],
            ]);

            $new_vendor->save();

            return redirect()->route('vendors.index')->with('success', 'Vendor information successfully inserted.');
        }else{
            return view('backend.permission.permission');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        //
        if($request->user()->can('view-vendor')){
            $vendor = Vendor::find($id);
            return view('backend.vendors.view', compact('vendor'));
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        if($request->user()->can('edit-vendor')){
            $vendor = Vendor::findorFail($id);
            $provinces = Province::all();
            $district = District::where('id', $vendor->district_id)->first();
            $district_group = District::where('province_id', $district->province_id)->get();
            return view('backend.vendors.edit', compact('vendor', 'provinces', 'district_group'));
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->user()->can('edit-vendor')){
            $vendor = Vendor::findorFail($id);
                $this->validate($request, [
                    'company_name' => 'required',
                    'company_email' => 'required',
                    'company_phone' => 'required',
                    'province' => 'required',
                    'district' => 'required',
                    'company_address' => 'required',
                    'pan_vat' => '',
                    'concerned_name' => 'required',
                    'concerned_phone' => 'required',
                    'concerned_email' => 'required',
                    'designation' => 'required',
                ]);

                $vendor->update([
                    'company_name' => $request['company_name'],
                    'company_email' => $request['company_email'],
                    'company_phone' => $request['company_phone'],
                    'province_id' => $request['province'],
                    'district_id' => $request['district'],
                    'company_address' => $request['company_address'],
                    'pan_vat' => $request['pan_vat'],
                    'concerned_name' => $request['concerned_name'],
                    'concerned_phone' => $request['concerned_phone'],
                    'concerned_email' => $request['concerned_email'],
                    'designation' => $request['designation'],
                ]);

            return redirect()->route('vendors.index')->with('success', 'Vendor information successfully updated.');
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,Request $request)
    {
        if($request->user()->can('remove-vendor')){
            $vendor = Vendor::findorFail($id);
            $vendor->delete();

            return redirect()->route('vendors.index')->with('success', 'Vendor information successfully deleted.');
        }else{
            return view('backend.permission.permission');
        }
    }

    public function getdistricts($id)
    {
        $districts = District::where('province_id', $id)->get();
        return response()->json($districts);
    }

    public function restorevendor($id, Request $request)
    {
        if($request->user()->can('view-vendor')){
            $deleted_vendor = Vendor::onlyTrashed()->findorFail($id);
            $deleted_vendor->restore();
            return redirect()->route('vendors.index')->with('success', 'Vendor information is restored successfully.');
        }else{
            return view('backend.permission.permission');
        }
    }
}
