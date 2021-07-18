@extends('backend.layouts.app')
@push('styles')
@endpush
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-9">
                    <h1 class="m-0">Account Head (Trashed) <a href="{{ route('account.index') }}" class="btn btn-sm btn-success">View Main Account Types</a> <a href="{{ route('sub_account.index') }}" class="btn btn-sm btn-success">View Sub-Account Types</a> <a href="{{ route('child_account.index') }}" class="btn btn-sm btn-success">View Child Account Types</a></h1>
                </div><!-- /.col -->
                <div class="col-sm-3">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Account Types</li>
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

            <div class="card mt-3">
                <div class="card-header text-center">
                    <h2>Main Account Types</h2>
                </div>
                <div class="card-body">
                    <table class="table table-bordered data-table-1 text-center">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Title</th>
                                <th>Slug</th>
                                <th width="100px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header text-center">
                    <h2>Sub Account Types</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-bordered data-table-2 text-center">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Title</th>
                                        <th>Embedded Account</th>
                                        <th>Slug</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header text-center">
                    <h2>Child Account Types</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-bordered data-table-3 text-center">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Title</th>
                                        <th>Embedded Sub Account</th>
                                        <th>Slug</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
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
          ajax: "{{ route('deletedindex') }}",
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
              {data: 'title', name: 'title'},
              {data: 'slug', name: 'slug'},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });

    });

    $(function () {
      var table = $('.data-table-2').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('deletedsubindex') }}",
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
              {data: 'title', name: 'title'},
              {data: 'account', name: 'account'},
              {data: 'slug', name: 'slug'},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });
    });

    $(function () {

      var table = $('.data-table-3').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('deletedchildindex') }}",
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
              {data: 'title', name: 'title'},
              {data: 'sub_account', name: 'sub_account'},
              {data: 'slug', name: 'slug'},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });

    });

  </script>
@endpush
