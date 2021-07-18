@extends('backend.layouts.app')
@push('styles')
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Our Suppliers <a href="{{ route('vendors.create') }}" class="btn btn-success">Add New Suppliers</a> <a href="{{ route('vendors.index') }}" class="btn btn-primary">View All Suppliers</a></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item active">Suppliers</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
        @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <h2>{{$vendor->company_name}}</h2>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                    <p><b>Company E-mail:</b> {{$vendor->company_email}}</p>
                    <p><b>Company Phone:</b> {{$vendor->company_phone}}</p>
                    <p><b>Province:</b> {{$vendor->province->eng_name}}</p>
                    <p><b>District:</b> {{$vendor->district->dist_name}}</p>
                    <p><b>Local Address:</b> {{$vendor->company_address}}</p>
                    <p><b>Company PAN/VAT:</b> @if ($vendor->pan_vat == null)
                                              Not Provided
                                          @else
                                              {{$vendor->pan_vat}}
                                      @endif</p>
                  </div>
                  <div class="col-md-6">
                    <p><b>Concerned Person Name:</b> {{$vendor->concerned_name}}</p>
                    <p><b>Concerned Person Phone:</b> {{$vendor->concerned_phone}}</p>
                    <p><b>Concerned Person Email:</b> {{$vendor->concerned_email}}</p>
                    <p><b>Concerned Person Designation:</b> {{$vendor->designation}}</p>
                  </div>
                </div>

                <a href="{{ route('vendors.edit', $vendor->id) }}" class="btn btn-primary">Edit Supplier</a>
              </div>
        </div>
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @endsection
  @push('scripts')

 {{-- Yajra Datatables --}}
 <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
 <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
    $(function () {

      var table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('vendors.index') }}",
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
              {data: 'company_name', name: 'company_name'},
              {data: 'concerned_name', name: 'concerned_name'},
              {data: 'company_email', name: 'company_email'},
              {data: 'company_phone', name: 'company_phone'},
              {data: 'company_address', name: 'company_address'},
              {data: 'pan_vat', name: 'pan_vat'},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });

    });
  </script>
  @endpush
