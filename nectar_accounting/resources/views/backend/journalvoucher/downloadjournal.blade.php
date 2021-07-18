<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $setting->company_name }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{asset('backend/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">

    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
                <div class="row">
                    <div class="col-md-2" style="float: left;">
                        <img src="{{ $path_img }}" alt="" style="height: 150px;">
                    </div>
                    <div class="col-md-10" style="text-align: center;">
                        <h1>{{ $setting->company_name }}</h1>
                        <h4>{{ $setting->address }}</h4>
                    </div>
                </div>
                <br><br><br><br>

                <div class="row">
                    <div class="col-md-12">
                        <div style="float: left;">
                            <b>Journal Voucher no.:</b> {{ $journalVoucher->journal_voucher_no }}
                        </div>
                        <div style="float: right;">
                            <b>Date:</b> {{ $journalVoucher->entry_date_nepali }} / {{ $journalVoucher->entry_date_english }}
                        </div>
                    </div>
                </div> <br><br><br>

                <div class="row" style="width: 100%">
                    <div class="col-md-12">
                        <table class="table mt-5" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th style="width: 100px;">Date</th>
                                    <th>Particulars</th>
                                    <th style="width: 20px;">LF no.</th>
                                    <th>Debit Amount</th>
                                    <th>Credit Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        {{ $journalVoucher->entry_date_nepali }}

                                        <br><br><br><br><br><br><br><br>
                                    </td>
                                    <td>
                                        @php
                                            $particulars = '';
                                            foreach ($journal_extras as $extra) {
                                                $child_account = DB::table('child_accounts')->where('id', $extra->child_account_id)->first();
                                                $particulars = $particulars . $child_account->title. '<br><br>' ;
                                            }
                                            echo $particulars;
                                        @endphp
                                        <br><br><br><br><br><br><br>
                                    </td>

                                    <td></td>
                                    <td>
                                        @php
                                            $debit_amounts = '';
                                            foreach ($journal_extras as $extra) {
                                                if ($extra->debitAmount == 0) {
                                                    $debit_amounts = $debit_amounts . '-'.'<br><br>' ;
                                                } else {
                                                    $debit_amounts = $debit_amounts .  'Rs. ' . $extra->debitAmount.'<br><br>' ;
                                                }
                                            }
                                            echo $debit_amounts;
                                        @endphp
                                        <br><br><br><br><br><br><br>
                                    </td>

                                    <td>
                                        @php
                                            $credit_amounts = '';
                                            foreach ($journal_extras as $extra) {
                                                if ($extra->creditAmount == 0) {
                                                    $credit_amounts = $credit_amounts . '-'.'<br><br>' ;
                                                } else {
                                                    $credit_amounts = $credit_amounts .  'Rs. ' . $extra->creditAmount.'<br><br>' ;
                                                }
                                            }
                                            echo $credit_amounts;
                                        @endphp
                                        <br><br><br><br><br><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"><b>Total</b></td>
                                    <td>Rs. {{ $journalVoucher->debitTotal }}</td>
                                    <td>Rs. {{ $journalVoucher->creditTotal }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <br><br><br>

                <b style="font-size: 20px;">Narration:</b> ( {{ $journalVoucher->narration }} )
                <br>
                <hr>

                <br><br><br>

                <div class="row">
                    <div class="col-md-12">
                        <div style="float: left; width:35%">
                            <b>Prepared By</b><br><br>
                            Name:<br><br>
                            Date:<br><br>
                            Authorized Signature:<br><br>
                        </div>
                        <div style="float: left; width:35%">
                            <b>Received By</b><br><br>
                            Name:<br><br>
                            Date:<br><br>
                            Authorized Signature:<br><br>

                        </div>
                        <div style="float: left; width:30%">
                            <b>Approved By</b><br><br>
                            Name:<br><br>
                            Date:<br><br>
                            Authorized Signature:<br><br>
                        </div>
                    </div>
                </div>
        </div>
    </section>
    <!-- /.content -->
</body>
</html>
