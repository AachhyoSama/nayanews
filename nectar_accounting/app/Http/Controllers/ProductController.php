<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImages;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::latest();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('category', function($row) {
                        $category = $row->category->category_name;
                        return $category;
                    })
                    ->addColumn('sale_price', function($row) {
                        $sale_price = 'Rs. '. $row->sale_price;
                        return $sale_price;
                    })
                    ->addColumn('purchase_price', function($row) {
                        $purchase_price = 'Rs. '. $row->purchase_price;
                        return $purchase_price;
                    })
                    ->addColumn('action', function($row){

                        $editurl = route('product.edit', $row->id);
                        $deleteurl = route('product.destroy', $row->id);
                        $csrf_token = csrf_token();
                        $btn = "<a href='$editurl' class='edit btn btn-primary btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                <button type='button' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deleteproduct$row->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                                <!-- Modal -->
                                    <div class='modal fade text-left' id='deleteproduct$row->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
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
                    ->rawColumns(['category', 'sale_price', 'purchase_price', 'action'])
                    ->make(true);
        }
        return view('backend.product_service.index');
    }

    public function deletedproduct(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::onlyTrashed()->latest();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('category', function($row) {
                        $category = $row->category->category_name;
                        return $category;
                    })
                    ->addColumn('sale_price', function($row) {
                        $sale_price = 'Rs. '. $row->sale_price;
                        return $sale_price;
                    })
                    ->addColumn('purchase_price', function($row) {
                        $purchase_price = 'Rs. '. $row->purchase_price;
                        return $purchase_price;
                    })
                    ->addColumn('action', function($row){

                        $restoreurl = route('restoreproduct', $row->id);
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
                    ->rawColumns(['category', 'sale_price', 'purchase_price', 'action'])
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
        $categories = Category::latest()->get();
        return view('backend.product_service.create', compact('categories'));
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
            'product_name' => 'required',
            'category' => 'required',
            'stock' => 'required',
            'sale_price' => 'required',
            'purchase_price' => 'required',
            'description' => 'required',
            'product_image' => 'required',
            'product_image.*' => 'image|mimes:png,jpg,jpeg',
        ]);

        $product = Product::create([
            'product_name' => $request['product_name'],
            'category_id' => $request['category'],
            'stock' => $request['stock'],
            'sale_price' => $request['sale_price'],
            'purchase_price' => $request['purchase_price'],
            'description' => $request['description']
        ]);

        $imagename = '';
        if($request->hasfile('product_image')) {
            $images = $request->file('product_image');
            foreach($images as $image){
                $imagename = $image->store('item_images', 'uploads');
                $product_images = ProductImages::create([
                    'product_id' => $product['id'],
                    'location' => $imagename,
                ]);
                $product_images->save();
            }
        }

        $product->save();

        return redirect()->route('product.index')->with('success', 'Product information is successfully inserted.');
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
    public function edit($id)
    {
        $product = Product::findorFail($id);
        $categories = Category::latest()->get();
        $product_images = ProductImages::where('product_id', $product->id)->get();
        return view('backend.product_service.editproduct', compact('product', 'categories', 'product_images'));
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

        $product = Product::findorFail($id);
        if(isset($_POST['submit'])){

            $this->validate($request, [
                'product_name' => 'required',
                'category' => 'required',
                'stock' => 'required',
                'sale_price' => 'required',
                'purchase_price' => 'required',
                'description' => 'required'
            ]);

            $product->update([
                'product_name' => $request['product_name'],
                'category_id' => $request['category'],
                'stock' => $request['stock'],
                'sale_price' => $request['sale_price'],
                'purchase_price' => $request['purchase_price'],
                'description' => $request['description']
            ]);
        }
        elseif(isset($_POST['update'])){

            $this->validate($request, [
                'product_image'=>'',
                'product_image.*' => 'mimes:png,jpg,jpeg',
            ]);

            $imagename = '';
            if($request->hasfile('product_image')) {

                $images = $request->file('product_image');
                foreach($images as $image){
                    $imagename = $image->store('item_images', 'uploads');
                    $product_images = ProductImages::create([
                        'product_id' => $product->id,
                        'location' => $imagename,
                    ]);
                    $product_images->save();
                }
            }
        }

        return redirect()->route('product.index')->with('success', 'Product information is successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findorFail($id);

        $product_images = ProductImages::where('product_id', $product->id)->get();
        if (count($product_images) > 0) {
            foreach ($product_images as $images) {
                Storage::disk('uploads')->delete($images->location);
                $images->delete();
            }
        }
        $product->delete();

        return redirect()->route('product.index')->with('success', 'Product information is successfully deleted.');
    }

    public function deleteproductimage($id)
    {
        $product_image = ProductImages::findorfail($id);
        $images = ProductImages::where('product_id', $product_image->product_id)->get();
        if(count($images) < 2){
            return redirect()->back()->with('error', 'Only one image cannot be deleted.');
        }

        $product_image->delete();

        return redirect()->back()->with('success', 'Image Removed Successfully');
    }

    public function restoreproduct($id, Request $request)
    {
        $deletedproduct = Product::onlyTrashed()->findorFail($id);
        $deletedproduct->restore();
        return redirect()->route('product.index')->with('success', 'Product information is restored successfully.');
    }
}
