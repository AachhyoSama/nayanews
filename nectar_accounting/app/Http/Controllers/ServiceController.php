<?php

namespace App\Http\Controllers;

use App\Models\ProductImages;
use App\Models\Service;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Service::latest();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('sale_price', function($row) {
                        $sale_price = 'Rs. '. $row->sale_price;
                        return $sale_price;
                    })
                    ->addColumn('action', function($row){

                        $editurl = route('service.edit', $row->id);
                        $deleteurl = route('service.destroy', $row->id);
                        $csrf_token = csrf_token();
                        $btn = "<a href='$editurl' class='edit btn btn-primary btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                <button type='button' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deletionservice$row->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                                <!-- Modal -->
                                    <div class='modal fade text-left' id='deletionservice$row->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
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
                    ->rawColumns(['sale_price', 'action'])
                    ->make(true);
        }
        return view('backend.product_service.index');
    }

    public function deletedservice(Request $request)
    {
        if ($request->ajax()) {
            $data = Service::onlyTrashed()->latest();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('sale_price', function($row) {
                        $sale_price = 'Rs. '. $row->sale_price;
                        return $sale_price;
                    })
                    ->addColumn('action', function($row){

                        $restoreurl = route('restoreservice', $row->id);
                            $btn = "<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#restoration$row->id' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-trash-restore'></i></button>
                                <!-- Modal -->
                                    <div class='modal fade text-left' id='restoration$row->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
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
                    ->rawColumns(['sale_price', 'action'])
                    ->make(true);
        }
        return view('backend.trash.itemstrash');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'service_name' => 'required',
            'service_sale_price' => 'required',
            'service_description' => 'required',
            'service_image' => 'required',
            'service_image.*' => 'image|mimes:jpg,jpeg,png',
        ]);

        $service = Service::create([
            'service_name' => $request['service_name'],
            'sale_price' => $request['service_sale_price'],
            'description' => $request['service_description']
        ]);

        $imagename = '';
        if($request->hasfile('service_image')) {
            $images = $request->file('service_image');
            foreach($images as $image){
                $imagename = $image->store('item_images', 'uploads');
                $service_image = ProductImages::create([
                    'service_id' => $service['id'],
                    'location' => $imagename,
                ]);
                $service_image->save();
            }
        }

        $service->save();

        return redirect()->route('product.index')->with('success', 'Service information is successfully inserted.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $service = Service::findorFail($id);
        $service_images = ProductImages::where('service_id', $service->id)->get();
        return view('backend.product_service.editservice', compact('service', 'service_images'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $service = Service::findorFail($id);


        if(isset($_POST['submit'])){

            $this->validate($request, [
                'service_name' => 'required',
                'service_sale_price' => 'required',
                'service_description' => 'required'
            ]);

            $service->update([
                'service_name' => $request['service_name'],
                'sale_price' => $request['service_sale_price'],
                'description' => $request['service_description']
            ]);
        }
        elseif(isset($_POST['update'])) {
            $this->validate($request, [
                'service_image'=>'',
                'service_image.*' => 'mimes:png,jpg,jpeg',
            ]);

            $imagename = '';
            if($request->hasfile('service_image')) {

                $images = $request->file('service_image');
                foreach($images as $image){
                    $imagename = $image->store('item_images', 'uploads');
                    $service_images = ProductImages::create([
                        'service_id' => $service->id,
                        'location' => $imagename,
                    ]);
                    $service_images->save();
                }
            }
        }

        return redirect()->route('service.index')->with('success', 'Service information is successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = Service::findorFail($id);

        $service_images = ProductImages::where('service_id', $service->id)->get();
        if (count($service_images) > 0) {
            foreach ($service_images as $images) {
                Storage::disk('uploads')->delete($images->location);
                $images->delete();
            }
        }
        $service->delete();

        return redirect()->route('service.index')->with('success', 'Service information is successfully deleted.');
    }

    public function deleteserviceimage($id)
    {
        $service_image = ProductImages::findorfail($id);
        $images = ProductImages::where('product_id', $service_image->product_id)->get();
        if(count($images) < 2){
            return redirect()->back()->with('error', 'Only one image cannot be deleted.');
        }

        $service_image->delete();

        return redirect()->back()->with('success', 'Image Removed Successfully');
    }

    public function restoreservice($id, Request $request)
    {
        $deleted_service = Service::onlyTrashed()->findorFail($id);
        $deleted_service->restore();
        return redirect()->route('service.index')->with('success', 'Service information is restored successfully.');
    }
}
