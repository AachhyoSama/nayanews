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
                        <h1 class="m-0">Journal Voucher no: {{ $journalVoucher->journal_voucher_no }} <a href="{{ route('journals.index') }}"
                                class="btn btn-primary">Exhibit Vouchers</a></h1>
                    </div>
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
                                    <div class="row">
                                        <div class="col-md-6">
                                            <b>Journal Voucher no.:</b> {{ $journalVoucher->journal_voucher_no }}<br><br>
                                            <b>Entried by:</b> {{ $journalVoucher->user_entry->name }}  <i>at {{ $created_nepali_date }}(in B.S)</i><br><br>
                                            <b>Edited by:</b> {{ $journalVoucher->edited_by == null ? '-' : $journalVoucher->user_edit->name }} <br><br>
                                            <b>Cancelled by:</b> {{ $journalVoucher->cancelled_by == null ? '-' : $journalVoucher->user_cancel->name }} <br><br>
                                            <b>Approved by:</b> {{ $journalVoucher->approved_by == null ? '-' : $journalVoucher->user_approved->name }} <br><br>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <b>Date:</b> {{ $journalVoucher->entry_date_nepali }} / {{ $journalVoucher->entry_date_english }}
                                        </div>

                                        <div class="col-md-12 table-responsive mt-4">
                                            <table class="table table-bordered text-center">
                                                <thead>
                                                    <tr>
                                                        <th>Particulars</th>
                                                        <th>Remarks</th>
                                                        <th style="width: 100px;">LF no.</th>
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

                                        <div class="col-md-2 mt-3">
                                            <b style="font-size: 20px;">Narration:</b>
                                        </div>
                                        <div class="col-md-10 mt-3" style="font-size: 20px;">
                                            ( {{ $journalVoucher->narration }} )
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <label for="" style="font-size: 20px;">Attached documents:</label>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            @php
                                                $journal_images = DB::table('journal_images')->where('journalvoucher_id', $journalVoucher->id)->get();
                                            @endphp

                                            @if (count($journal_images) == 0)
                                                <p>No attached documents.</p>
                                            @else
                                                @foreach ($journal_images as $images)
                                                    <a href="{{ Storage::disk('uploads')->url($images->location) }}" target="_blank">
                                                        <img src="{{ Storage::disk('uploads')->url($images->location) }}" alt="{{$journalVoucher->id}}" style="height: 250px;">
                                                    </a>
                                                @endforeach
                                            @endif
                                        </div>

                                        <div class="col-md-12 mt-4">
                                            <a href="{{ route('journals.edit', $journalVoucher->id) }}" class="btn btn-primary">Edit</a>
                                            <a href="{{ route('pdf.generateJournal', $journalVoucher->id) }}" class="btn btn-primary">Export(as PDF)</a>
                                            <a href="{{ route('journals.print', $journalVoucher->id) }}" class="btn btn-primary btnprn">Print</a>
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
    <script type="text/javascript">
        $(document).ready(function(){
        $('.btnprn').printPage();
        });
    </script>
@endpush
