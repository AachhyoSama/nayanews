@extends('backend.layouts.app')
@push('styles')
<style>
    .wrapp {
        position: relative;
    }

    .absolutebtn {
        position: absolute;
        top: 0;
    }
</style>
@endpush
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Edit Service information <a href="{{ route('product.index') }}" class="btn btn-primary">View All Items</a></h1>
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
            @if (session('success'))
                <div class="col-sm-12">
                    <div class="alert  alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="col-sm-12">
                    <div class="alert  alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                </div>
            @endif
            <div class="ibox">
                <div class="row ibox-body">
                    <div class="col-sm-12 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('service.update', $service->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method("PUT")
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="service_name">Service Name:</label>
                                                <input type="text" id="service_name" name="service_name" class="form-control" value="{{ $service->service_name }}" placeholder="Service Name"/>
                                                <p class="text-danger">
                                                    {{ $errors->first('service_name') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="service_sale_price">Sale Price (Rs.):</label>
                                                <input type="number" name="service_sale_price" class="form-control" value="{{ $service->sale_price }}" placeholder="Allocated Sale Price">
                                                <p class="text-danger">
                                                    {{ $errors->first('service_sale_price') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="description">Service Description: </label>
                                                <textarea name="service_description" class="form-control" cols="30" rows="10" placeholder="Something about product..." value="{{ old('service_description') }}">{{ $service->description }}</textarea>
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


                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <form action="{{ route('service.update', $service->id) }}"
                                            id="validate" enctype="multipart/form-data" method="POST">
                                            @csrf
                                            @method("PUT")

                                            <div class="form-group">
                                                <label for="service_image">Attach file (Bills, etc.)</label>
                                                <input type="file" name="service_image[]" id="service_image" class="form-control"
                                                    onchange="loadFile(event)" multiple>
                                            </div>

                                            {{-- <p class="text-danger">Note*: Don't upload new if you want previously uploaded file.</p> --}}

                                                <input type="submit" id="add_receive" class="btn btn-success btn-large"
                                                    name="update" value="Save" tabindex="9" />
                                        </form>
                                    </div>
                                    <hr>

                                    <div class="col-md-12 mt-5 mb-5">
                                        @if (count($service_images) > 0)
                                            <div class="row">
                                                @foreach ($service_images as $image)
                                                    <div class="col-md-3 wrapp">
                                                        <img src="{{ Storage::disk('uploads')->url($image->location) }}"
                                                            alt="" style="width:100%;">
                                                        <form action="{{ route('deleteserviceimage', $image->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" data-id="{{ $image->id }}"
                                                                class="btn btn-danger py-0 px-1 absolutebtn"
                                                                class="remove">x</button>
                                                        </form>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <img src="{{ Storage::disk('uploads')->url('noimage.jpg') }}" alt=""
                                                style="width:200px; max-height: 200px;">
                                        @endif
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
