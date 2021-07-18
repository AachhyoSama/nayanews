@extends('backend.layouts.app')
@push('styles')
@endpush
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-sm-6">
              <h1 class="m-0">Journal Vouchers <a href="{{ route('journals.create') }}" class="btn btn-success">Entry New Journal</a> <a href="{{ route('journals.index') }}" class="btn btn-success">View Journals</a></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Journal Vouchers</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        <div class="card">
            <div class="card-header text-center">
                <h2>Generate Report</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('extra') }}" method="GET">
                    @csrf
                    @method("GET")
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Fiscal Year</label>
                                <select name="fiscal_year" class="form-control fiscal">
                                    @foreach ($fiscal_years as $fiscal_year)
                                        <option value="{{ $fiscal_year->fiscal_year }}" {{ $fiscal_year->id == $current_fiscal_year->id ? 'selected' : '' }}>{{ $fiscal_year->fiscal_year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Starting date</label>
                                <input type="text" name="starting_date" class="form-control startdate" id="starting_date" value="{{ $actual_year[0] . '-04-01' }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Ending date</label>
                                <input type="text" name="ending_date" class="form-control enddate" id="ending_date" value="">
                            </div>
                        </div>

                        <div class="col-md-3 mt-4">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Generate Report</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
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
        <div class="card">
            <div class="card-header text-center">
                <h1>Journal Vouchers</h1>
                <h4>For the fiscal year {{ $current_fiscal_year->fiscal_year }} ({{ $starting_date }} to {{ $ending_date }})</h4>
            </div>
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
          ajax: "{{ route('generatereport', ['id' => $id, 'starting_date' => $starting_date, 'ending_date' => $ending_date]) }}",
          columns: [
              {data: 'journal_voucher_no', name: 'journal_voucher_no'},
              {data: 'entry_date', name: 'entry_date'},
              {data: 'particulars', name: 'particulars'},
              {data: 'debit_amount', name: 'debit_amount'},
              {data: 'credit_amount', name: 'credit_amount'},
              {data: 'narration', name: 'narration'},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });

    });
  </script>

  <script>
      $(function() {
        $('.fiscal').change(function() {
            var fiscal_year = $(this).children("option:selected").val();
            var array_date = fiscal_year.split("/");

            var starting_date = document.getElementById("starting_date");
            var starting_full_date = array_date[0] + '-04-01';
            starting_date.value = starting_full_date;
            starting_date.nepaliDatePicker();

            var ending_date = document.getElementById("ending_date");
            var ending_year = array_date[1];
            var days_count = NepaliFunctions.GetDaysInBsMonth(ending_year, 3);
            var ending_full_date = ending_year + '-03-' + days_count;
            ending_date.value = ending_full_date;

            ending_date.nepaliDatePicker();
        })
      })
  </script>


  <script type="text/javascript">
    window.onload = function() {
        var starting_date = document.getElementById("starting_date");
        var ending_date = document.getElementById("ending_date");
        var ending_year = {{ $actual_year[1] }};

        var days_count = NepaliFunctions.GetDaysInBsMonth(ending_year, 3);
        starting_date.nepaliDatePicker();

        var ending_full_date = ending_year + '-03-' + days_count;
        ending_date.value = ending_full_date;

        ending_date.nepaliDatePicker();
    };
</script>
  @endpush
