@extends('backend.layouts.app')
@push('styles')

    <style>
        option.title {
            font: 30px bold;
            background-color: #e9ecef;
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
                        <h1 class="m-0">Entry Journal Voucher <a href="{{ route('journals.index') }}"
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
                                    <form action="{{ route('journals.store') }}" id="validate"
                                        enctype="multipart/form-data" method="POST">
                                        @csrf
                                        @method("POST")
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="journal_voucher_no">Voucher No<i
                                                            class="text-danger">*</i></label>
                                                    <input type="text" name="journal_voucher_no" id="voucher_no"
                                                        value="JV-{{ $jvnumber }}" class="form-control" readonly
                                                        required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="entry_date">Entry Date<i class="text-danger">*</i></label>
                                                    <input type="text" name="entry_date" id="entry_date"
                                                        class="form-control datepicker" value="" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="file">Attach file (Bills, etc.)</label>
                                                    <input type="file" name="file[]" id="file" class="form-control"
                                                        onchange="loadFile(event)" multiple>
                                                        <p class="text-danger">
                                                            {{ $errors->first('file') }}
                                                        </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <img id="output" style="height: 100px;">
                                            </div>
                                            {{-- <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="status">Status: </label>
                                                    <input type="radio" name="status" value="1"> Approved
                                                    <input type="radio" name="status" value="0" checked> To be Approved
                                                </div>
                                            </div> --}}
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="status">Suppliers: </label>
                                                    <select name="vendor_id" id="vendor" class="form-control">
                                                        <option value="">--Select Option--</option>
                                                        @foreach ($vendors as $vendor)
                                                        <option value="{{$vendor->id}}">{{$vendor->company_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover" id="debtAccVoucher">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-center"> Account Name<i class="text-danger">*</i>
                                                        </th>
                                                        {{-- <th class="text-center"> Payable To</th> --}}
                                                        <th class="text-center"> Code</th>
                                                        <th class="text-center"> Remarks</th>
                                                        <th class="text-center"> Debit</th>
                                                        <th class="text-center"> Credit</th>
                                                        <th class="text-center"> Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="debitvoucher">
                                                    <tr class="test">
                                                        <td class="" width="300px">
                                                            <select name="child_account_id[]" id="accountName_1" class="form-control select" required>
                                                                <option value="">--Select One--</option>
                                                                @foreach ($accounts as $account)
                                                                    <option value="" class="title" disabled>
                                                                        {{ $account->title }}</option>
                                                                    @php
                                                                        $sub_accounts = DB::table('sub_accounts')
                                                                            ->where('account_id', $account->id)
                                                                            ->get();
                                                                    @endphp

                                                                    @foreach ($sub_accounts as $sub_account)
                                                                        @php
                                                                            $child_accounts = DB::table('child_accounts')
                                                                                ->where('sub_account_id', $sub_account->id)
                                                                                ->get();
                                                                        @endphp
                                                                        @foreach ($child_accounts as $child_account)
                                                                            <option value="{{ $child_account->id }}">
                                                                                {{ $child_account->title }} -
                                                                                {{ $sub_account->title }}</option>
                                                                        @endforeach
                                                                    @endforeach
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        {{-- <td>
                                                    <input type="text" class="form-control" disabled>
                                                </td> --}}
                                                        <td>
                                                            <input type="text" name="code[]" class="form-control "
                                                                id="code_1">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="remarks[]" class="form-control all_remarks"
                                                                id="remarks_1" value="">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="debitAmount[]" value="0"
                                                                class="form-control debitPrice text-right"
                                                                id="debitAmount_1" onkeyup="calculateTotal(1)">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="creditAmount[]" value="0"
                                                                class="form-control creditPrice text-right"
                                                                id="creditAmount_1" onkeyup="calculateTotal(1)">
                                                        </td>

                                                        <td>
                                                            <button class="btn btn-danger red" type="button" value="Delete"
                                                                onclick="deleteRowContravoucher(this)"><i
                                                                    class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        {{-- <td></td> --}}
                                                        <td></td>
                                                        <td colspan="2" class="text-right">
                                                            <label for="reason" class="  col-form-label">Total</label>
                                                        </td>
                                                        <td class="text-right">
                                                            <input type="text" id="debitTotal"
                                                                class="form-control text-right " name="debitTotal" value=""
                                                                readonly="readonly" value="0" />
                                                        </td>
                                                        <td class="text-right">
                                                            <input type="text" id="creditTotal"
                                                                class="form-control text-right " name="creditTotal" value=""
                                                                readonly="readonly" value="0" />
                                                        </td>
                                                        {{-- <td></td> --}}
                                                        <td>
                                                            <a id="add_more" class="btn btn-info" name="add_more"
                                                                onClick="addaccountContravoucher('debitvoucher')"><i
                                                                    class="fa fa-plus"></i></a>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <hr>

                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <label for="narration">Narration:</label>
                                            </div>
                                            <div class="col-md-10">
                                                <textarea name="narration" id="narration" class="form-control"
                                                cols="30" rows="5" placeholder="Being something..."></textarea>
                                                <p class="text-danger">
                                                    {{ $errors->first('narration') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12 text-center">
                                                <input type="submit" id="add_receive" class="btn btn-success btn-large"
                                                    name="save" value="Save" tabindex="9" />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <input type="hidden" id="headoption" value="<option value=''>--Select One--</option>
                                    @foreach ($accounts as $account)
                                    <option value='' class='title' disabled>{{ $account->title }}</option>
                                    @php
                                        $sub_accounts = DB::table('sub_accounts')
                                            ->where('account_id', $account->id)
                                            ->get();
                                    @endphp
                                    @foreach ($sub_accounts as $sub_account)
                                        @php
                                            $child_accounts = DB::table('child_accounts')
                                                ->where('sub_account_id', $sub_account->id)
                                                ->get();
                                        @endphp
                                        @foreach ($child_accounts as $child_account)
                                            <option value='{{ $child_account->id }}'>{{ $child_account->title }} -
                                                {{ $sub_account->title }}</option>
                                        @endforeach
                                    @endforeach
                            @endforeach"
                            name="">
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
        function addaccountContravoucher(divName) {
            var optionval = $("#headoption").val();
            var row = $("#debtAccVoucher tbody tr").length;
            var count = row + 1;
            var limits = 500;
            var tabin = 0;
            if (count == limits) alert("You have reached the limit of adding " + count + " inputs");
            else {
                var newdiv = document.createElement('tr');
                var tabin = "accountName_" + count;
                var tabindex = count * 2;
                newdiv = document.createElement("tr");
                newdiv.innerHTML = "<td> <select name='child_account_id[]' id='accountName_" + count +
                    "' class='form-control select' required></select></td><td><input type='text' name='code[]' class='form-control'  id='code_" +
                    count +
                    "' ></td><td><input type='text' name='remarks[]' class='form-control all_remarks' value='' id='remarks_" +
                    count +
                    "' ></td><td><input type='number' name='debitAmount[]' class='form-control debitPrice text-right' value='0' id='debitAmount_" +
                    count + "' onkeyup='calculateTotal(" + count +
                    ")' ></td><td><input type='number' name='creditAmount[]' class='form-control creditPrice text-right' id='debitAmount1_" +
                    count + "' value='0' onkeyup='calculateTotal(" + count +
                    ")'></td><td><button  class='btn btn-danger red' type='button'  onclick='deleteRowContravoucher(this)'><i class='fa fa-trash'></i></button></td>";
                document.getElementById(divName).appendChild(newdiv);
                document.getElementById(tabin).focus();
                $("#accountName_" + count).html(optionval);
                count++;
                $("select.form-control:not(.dont-select-me)").select2({
                    // placeholder: "--Select One--",
                    // allowClear: true
                });
            }
        }

        function dbtvouchercalculation(sl) {
            var gr_tot = 0;
            $(".debitPrice").each(function() {
                isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
            });
            $("#debitTotal").val(gr_tot.toFixed(2, 2));
        }

        function calculateTotal(sl) {
            var gr_tot1 = 0;
            var gr_tot = 0;
            $(".debitPrice").each(function() {
                isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
            });
            $(".creditPrice").each(function() {
                isNaN(this.value) || 0 == this.value.length || (gr_tot1 += parseFloat(this.value))
            });
            $("#debitTotal").val(gr_tot.toFixed(2, 2));
            $("#creditTotal").val(gr_tot1.toFixed(2, 2));

            if($(".debitPrice").value != 0) {
                $(".creditPrice").attr('disabled');
            }
        }

        function deleteRowContravoucher(e) {
            var t = $("#debtAccVoucher > tbody > tr").length;
            if (1 == t) alert("There only one row you can't delete.");
            else {
                var a = e.parentNode.parentNode;
                a.parentNode.removeChild(a)
            }
            calculateTotal()
        }

    </script>


    <script type="text/javascript">
        window.onload = function() {
            var mainInput = document.getElementById("entry_date");
            var date = new Date;
            var year = date.getFullYear();
            var month = date.getMonth() + 1;
            var day = date.getDate();
            let Addate = {
                year: year,
                month: month,
                day: day
            };
            var nepali_date = NepaliFunctions.AD2BS(Addate);
            var nepali_date_string = nepali_date['year'] + '-' + nepali_date['month'] + '-' + nepali_date['day'];
            mainInput.value = nepali_date_string;
            mainInput.nepaliDatePicker();
        };

    </script>

    <script>
        var loadFile = function(event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };
    </script>

    <script>
        $(document).on('input', 'input.debitPrice', function() {
            $(this).parent().siblings().find('input.creditPrice').attr('readonly',
                'readonly');
        });
        $(document).on('input', 'input.creditPrice', function() {
            $(this).parent().siblings().find('input.debitPrice').attr('readonly',
                'readonly');
        });
    </script>

    
    <script>
        $(document).ready(function() {
            $(".select").select2();
        });
    </script>
@endpush
