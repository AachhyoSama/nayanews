@extends('backend.layouts.app')

@section('content')

    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content p-5">
            <div class="container-fluid">
                <div class="ibox">

                    <div style="float: left;">
                        <img src="{{ Storage::disk('uploads')->url($setting->logo) }}" alt="{{ $setting->company_name }}" style="height: 120px;">
                    </div>

                    <h1 class="m-0 text-center">{{ $setting->company_name }} </h1><br>
                    <p class="text-center">{{ $setting->address }}, {{ $setting->district->dist_name }}</p>


                    <br><br><br><br>
                    <div class="card">
                        <div class="card-body">
                            <div style="width: 100%;">
                                <div style="float: right;">
                                    <b>Date:</b> {{ $journalVoucher->entry_date_nepali }} / {{ $journalVoucher->entry_date_english }}
                                </div>
                                <div style="float: left;">
                                    <b>Journal Voucher no.:</b> {{ $journalVoucher->journal_voucher_no }}<br><br>
                                </div>
                                <br><br>

                                <table class="table table-bordered text-center mt-5">
                                    <thead>
                                        <tr>
                                            <th>Particulars</th>
                                            <th>Remarks</th>
                                            <th style="width: 80px;">LF no.</th>
                                            <th>Debit Amount</th>
                                            <th>Credit Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                @php
                                                    $particulars = '';
                                                    foreach ($journal_extras as $extra) {
                                                        $child_account = DB::table('child_accounts')->where('id', $extra->child_account_id)->first();
                                                        $particulars = $particulars . $child_account->title. '<br><br>' ;
                                                    }
                                                    echo $particulars;
                                                @endphp
                                                <br><br>
                                            </td>

                                            <td>
                                                @php
                                                    $remarks = '';
                                                    foreach ($journal_extras as $extra) {
                                                        if ($extra->remarks == null) {
                                                            $remarks = $remarks . '-'.'<br><br>' ;
                                                        } else {
                                                            $remarks = $remarks . $extra->remarks.'<br><br>' ;
                                                        }
                                                    }
                                                    echo $remarks;
                                                @endphp
                                                <br><br>
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
                                                <br><br>
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
                                                <br><br>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><b>Total</b></td>
                                            <td>Rs. {{ $journalVoucher->debitTotal }}</td>
                                            <td>Rs. {{ $journalVoucher->creditTotal }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br>
                                <b style="font-size: 20px;">Narration:</b> ( {{ $journalVoucher->narration }} )
                                <hr>
                                <br><br>

                                <div>
                                    <div style="float: left; width:35%">
                                        <b>Prepared By</b><br>
                                        Name:<br>
                                        Date:<br>
                                        Authorized Signature:<br>
                                    </div>
                                    <div style="float: left; width:35%">
                                        <b>Received By</b><br>
                                        Name:<br>
                                        Date:<br>
                                        Authorized Signature:<br>

                                    </div>
                                    <div style="float: left; width:30%">
                                        <b>Approved By</b><br>
                                        Name:<br>
                                        Date:<br>
                                        Authorized Signature:<br>
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

@endsection
