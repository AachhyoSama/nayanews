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
          <div class="col-sm-8">
            <h1 class="m-0">Unapproved Journal Vouchers <a href="{{ route('journals.create') }}" class="btn btn-success">Entry New Journal</a> <a href="{{ route('journals.index') }}" class="btn btn-success">View Journals</a></h1>
          </div><!-- /.col -->
          <div class="col-sm-4">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item active">Unapproved Journal Vouchers</li>
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
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-bordered data-table text-center">
                            <thead>
                                <tr>
                                    <th>JV no.</th>
                                    <th>Entry Date</th>
                                    <th>Particulars</th>
                                    <th>Debit Amount</th>
                                    <th>Credit Amount</th>
                                    <th>Narration</th>
                                    <th>Status</th>
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
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @endsection
  @push('scripts')



    <script type="text/javascript">
        $(function () {

        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('journals.unapproved') }}",
            columns: [
                {data: 'journal_voucher_no', name: 'journal_voucher_no'},
                {data: 'entry_date_nepali', name: 'entry_date_nepali'},
                {data: 'particulars', name: 'particulars'},
                {data: 'debit_amount', name: 'debit_amount'},
                {data: 'credit_amount', name: 'credit_amount'},
                {data: 'narration', name: 'narration'},
                {data: 'status', name:'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        });
    </script>
  @endpush
