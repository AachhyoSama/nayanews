@extends('backend.layouts.app')
@push('styles')
    <style>
        .display {
            display: none;
        }

        .sub-drop {
            padding-left: 25px;
            display: inline-block;
        }

        .single-drop {
            padding-left: 50px;
            display: inline-block;
        }

    </style>
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-4">
                    <div class="col-sm-8">
                        <h1 class="m-0">Trial Balance</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-4">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Trial Balance</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->

                <div class="card">
                    <div class="card-header text-center">
                        <h2>Generate Report</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('trialextra') }}" method="GET">
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
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session()->get('error') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header text-center">
                                <h1>Trial Balance</h1>
                                <h4>For the fiscal year {{ $current_fiscal_year->fiscal_year }} ({{ $starting_date }} to {{ $ending_date }})</h4>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" style="width: 300px;">Accounts</th>
                                                    <th colspan="2">Current</th>
                                                </tr>
                                                <tr>
                                                    <th>Debit Amount</th>
                                                    <th>Credit Amount</th>
                                                </tr>
                                            </thead>


                                            @foreach ($mainaccounts as $account)
                                                @php
                                                    $subaccounts = $account->sub_accounts;
                                                    $mainchildaccounts = [];
                                                    foreach ($subaccounts as $subaccount) {
                                                        $everymainchildaccounts = $subaccount->child_accounts;
                                                        array_push($mainchildaccounts, $everymainchildaccounts);
                                                    }
                                                    $collapsedmainsubaccounts = Arr::collapse($mainchildaccounts);
                                                    $journalextras = [];
                                                    foreach ($collapsedmainsubaccounts as $collapsedmain) {
                                                        $everyjournalextras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year, $start_date, $end_date){
                                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
                                                                    $q->where('entry_date_english', '>=', $start_date);
                                                                    $q->where('entry_date_english', '<=', $end_date);
                                                                })
                                                            ->where('child_account_id', $collapsedmain->id)
                                                            ->get();
                                                        array_push($journalextras, $everyjournalextras);
                                                    }
                                                    $collapsedjextras = Arr::collapse($journalextras);
                                                    $mainDebitAmounts = [];
                                                    $mainCreditAmounts = [];
                                                    foreach ($collapsedjextras as $collapsedjextras) {
                                                        $mainDebit = $collapsedjextras->debitAmount;
                                                        $mainCredit = $collapsedjextras->creditAmount;
                                                        array_push($mainDebitAmounts, $mainDebit);
                                                        array_push($mainCreditAmounts, $mainCredit);
                                                    }
                                                    // dd($mainDebitAmounts);
                                                    $mainDebitsum = array_sum($mainDebitAmounts);
                                                    $mainCreditsum = array_sum($mainCreditAmounts);
                                                    $mainDebitsum = array_sum($mainDebitAmounts);
                                                    $mainCreditsum = array_sum($mainCreditAmounts);
                                                    $maindifference = $mainDebitsum-$mainCreditsum;
                                                    if($maindifference==0){
                                                        $maindebitamount = '-';
                                                        $maincreditamount = '-';
                                                    }elseif ($maindifference < 0) {
                                                        $maindebitamount = '-';
                                                        $maincreditamount = '('.abs($maindifference).')';
                                                    }elseif($maindifference > 0){
                                                        $maindebitamount = $maindifference;
                                                        $maincreditamount = '-';
                                                    }
                                                @endphp
                                                <tbody>
                                                    <tr class="main">
                                                        <td><a href="#" class="drop"><i class="main far fa-folder"></i></a>
                                                            {{ $account->title }}</td>
                                                        <td>{{ $maindebitamount }}</td>
                                                        <td>{{ $maincreditamount }}</td>
                                                    </tr>
                                                    @foreach ($account->sub_accounts as $subAccount)
                                                        @php
                                                            $journalextras = [];
                                                            $subChild = $subAccount->child_accounts;
                                                            foreach ($subChild as $subchildaccount) {
                                                                $everyjournalextras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year){
                                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
                                                                })
                                                                    ->where('child_account_id', $subchildaccount->id)
                                                                    ->get();
                                                                array_push($journalextras, $everyjournalextras);
                                                            }

                                                            $collapsedjextras = Arr::collapse($journalextras);
                                                            $subDebitAmounts = [];
                                                            $subCreditAmounts = [];
                                                            foreach ($collapsedjextras as $collapsedjextras) {
                                                                $subDebit = $collapsedjextras->debitAmount;
                                                                $subCredit = $collapsedjextras->creditAmount;
                                                                array_push($subDebitAmounts, $subDebit);
                                                                array_push($subCreditAmounts, $subCredit);
                                                            }
                                                            $subDebitsum = array_sum($subDebitAmounts);
                                                            $subCreditsum = array_sum($subCreditAmounts);
                                                            $difference = $subDebitsum-$subCreditsum;
                                                            if($difference==0){
                                                                $debitamount = '-';
                                                                $creditamount = '-';
                                                            }elseif ($difference < 0) {
                                                                $debitamount = '-';
                                                                $creditamount = '('.abs($difference).')';
                                                            }elseif($difference > 0){
                                                                $debitamount = $difference;
                                                                $creditamount = '-';
                                                            }
                                                        @endphp
                                                        <tr class="sub display">
                                                            <td><a href="#" class="sub-drop"><i
                                                                        class="subicon far fa-folder"></i></a>{{ $subAccount->title }}
                                                            </td>
                                                            <td>{{ $debitamount }}</td>
                                                            <td>{{ $creditamount }}</td>

                                                        </tr>
                                                        @foreach ($subAccount->child_accounts as $childAccount)
                                                            @php
                                                                $debitAmount = [];
                                                                $creditAmount = [];
                                                                $journal_extras = \App\Models\JournalExtra::whereHas('journal_voucher', function ($q) use($current_fiscal_year){
                                                                    $q->where('fiscal_year_id', '=', $current_fiscal_year->id)->where('is_cancelled', 0)->where('status', 1);
                                                                })
                                                                    ->where('child_account_id', $childAccount->id)
                                                                    ->get();
                                                                    // dd($journal_extras);
                                                                foreach ($journal_extras as $journalExtra) {
                                                                    array_push($debitAmount, $journalExtra->debitAmount);
                                                                    array_push($creditAmount, $journalExtra->creditAmount);
                                                                }
                                                                // dd($debitAmount);
                                                                $debitSum = array_sum($debitAmount);
                                                                $creditSum = array_sum($creditAmount);
                                                            @endphp
                                                            <tr class="single display">
                                                                <td><a href="#" class="single-drop"><i
                                                                            class="far fa-file"></i></a>
                                                                    {{ $childAccount->title }}</td>
                                                                <td>{{ $debitSum == 0 ? '-' : $debitSum }}</td>
                                                                <td>{{ $creditSum == 0 ? '-' : '('.$creditSum.')' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                </tbody>

                                            @endforeach
                                        </table>

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
    <script>
        $(function() {
            var main = $('tr.main');
            var sub = $("tr.sub");
            var drop = $("tr.main a.drop");
            var single = $('tr.single');
            $('td a').click(function(e) {
                e.preventDefault();
            })

            drop.click(function() {
                $(this).parent().parent().parent().find('.sub').toggleClass('display');
                $(this).parent().parent().parent().find('.single').addClass('display');
                $('i.main').toggleClass('fa-folder');
                $('i.main').toggleClass('fa-folder-open');
            })
            $('.sub-drop').click(function() {
                $(this).parent().parent().parent().find('.single').toggleClass('display');
                $('i.subicon').toggleClass('fa-folder');
                $('i.subicon').toggleClass('fa-folder-open');
            })
        })
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
