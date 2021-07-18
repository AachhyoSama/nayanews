@extends('backend.layouts.app')
@push('styles')
    {{-- Nepali Date picker --}}
    <link href="http://nepalidatepicker.sajanmaharjan.com.np/nepali.datepicker/css/nepali.datepicker.v3.6.min.css" rel="stylesheet" type="text/css"/>
@endpush
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Items information <a href="{{ route('product.index') }}" class="btn btn-primary">View All Items</a></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Items</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="ibox">
                <div class="row ibox-body">
                    <div class="col-sm-12 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true" style="font-size: 25px;">Product</a>
                                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false" style="font-size: 25px;">Service</a>
                                    </div>
                                </nav>
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method("POST")
                                            <div class="row mt-3">
                                                <div class="col-md-12 mb-3 text-center">
                                                    <h2>Fill Product Information</h2>
                                                    <hr>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="product_name">Product Name:</label>
                                                        <input type="text" id="product_name" name="product_name" class="form-control" value="{{ old('product_name') }}" placeholder="Product Name"/>
                                                        <p class="text-danger">
                                                            {{ $errors->first('product_name') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="category">Product Category:</label>
                                                        <select name="category" class="form-control" onChange="if(this.value=='{{ route('category.create') }}') window.location.href=this.value">
                                                            <option value="">--Select a category--</option>
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                                            @endforeach
                                                            <option value="{{ route('category.create') }}" style="font-weight: bold; text-align: center;">Add New Category</option>
                                                        </select>
                                                        <p class="text-danger">
                                                            {{ $errors->first('category') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="stock">Entry Stock:</label>
                                                        <input type="number" id="stock" name="stock" class="form-control" value="{{ old('stock') }}" placeholder="Stock"/>
                                                        <p class="text-danger">
                                                            {{ $errors->first('stock') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="image">Product image (Multiple images can be selected):</label>
                                                        <input type="file" name="product_image[]" class="form-control" multiple>
                                                        <p class="text-danger">
                                                            {{ $errors->first('product_image') }}
                                                        </p>
                                                    </div>
                                                </div>


                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sale_price">Sale Price (Rs.):</label>
                                                        <input type="number" name="sale_price" class="form-control" value="{{ old('sale_price') }}" placeholder="Allocated Sale Price">
                                                        <p class="text-danger">
                                                            {{ $errors->first('sale_price') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="purchase_price">Purchase Price (Rs.):</label>
                                                        <input type="number" name="purchase_price" class="form-control" value="{{ old('purchase_price') }}" placeholder="Allocated Purchase Price">
                                                        <p class="text-danger">
                                                            {{ $errors->first('purchase_price') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="description">Product Description: </label>
                                                        <textarea name="description" class="form-control" cols="30" rows="10" placeholder="Something about product..." value="{{ old('description') }}"></textarea>
                                                        <p class="text-danger">
                                                            {{ $errors->first('description') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <button type="submit" class="btn btn-success">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                        <form action="{{ route('service.store') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method("POST")
                                            <div class="row mt-3">
                                                <div class="col-md-12 mb-3 text-center">
                                                    <h2>Fill Service Information</h2>
                                                    <hr>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="service_name">Service Name:</label>
                                                        <input type="text" id="service_name" name="service_name" class="form-control" value="{{ old('service_name') }}" placeholder="Service Name"/>
                                                        <p class="text-danger">
                                                            {{ $errors->first('service_name') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6"></div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="image">Images (Multiple images can be selected):</label>
                                                        <input type="file" name="service_image[]" class="form-control" multiple>
                                                        <p class="text-danger">
                                                            {{ $errors->first('service_image') }}
                                                        </p>
                                                    </div>
                                                </div>


                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="service_sale_price">Sale Price (Rs.):</label>
                                                        <input type="number" name="service_sale_price" class="form-control" value="{{ old('service_sale_price') }}" placeholder="Allocated Sale Price">
                                                        <p class="text-danger">
                                                            {{ $errors->first('service_sale_price') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="description">Service Description: </label>
                                                        <textarea name="service_description" class="form-control" cols="30" rows="10" placeholder="Something about product..." value="{{ old('service_description') }}"></textarea>
                                                        <p class="text-danger">
                                                            {{ $errors->first('service_description') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <button type="submit" class="btn btn-success">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @endsection
  @push('scripts')

  @endpush
