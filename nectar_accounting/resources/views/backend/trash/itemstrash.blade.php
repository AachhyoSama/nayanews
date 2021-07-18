@extends('backend.layouts.app')
@push('styles')
@endpush
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Items (Trashed) <a href="{{ route('product.index') }}" class="btn btn-primary">View All Items</a></h1>
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
                            <div class="card-header">
                                <h1 class="text-center mt-4">Products</h1>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered data-table-1 text-center mt-2">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Product Name</th>
                                            <th>Product Category</th>
                                            <th>In stock (Unit)</th>
                                            <th>Sale Price</th>
                                            <th>Purchase Price</th>
                                            <th width="100px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h1 class="text-center mt-4">Services</h1>
                            </div>

                            <div class="card-body">

                                <table class="table table-bordered data-table-3 text-center" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Service Name</th>
                                            <th>Sale Price</th>
                                            <th width="100px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
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

 <script type="text/javascript">
     $(function () {

        var table = $('.data-table-1').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('deletedproduct') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'product_name', name: 'product_name'},
                {data: 'category', name: 'category'},
                {data: 'stock', name: 'stock'},
                {data: 'sale_price', name: 'sale_price'},
                {data: 'purchase_price', name: 'purchase_price'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

    });
   </script>

<script type="text/javascript">
    $(function () {

        var table = $('.data-table-3').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('deletedservice') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'service_name', name: 'service_name'},
                {data: 'sale_price', name: 'sale_price'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

    });
  </script>
  @endpush
