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
                        <h1 class="m-0">Supplier Information <a href="{{ route('vendors.index') }}"
                                class="btn btn-primary">Our Suppliers</a></h1>
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
                <div class="ibox">
                    <div class="row ibox-body">
                        <div class="col-sm-12 col-md-12">
                            <form action="{{ route('vendors.store') }}" method="POST">
                                @csrf
                                @method("POST")
                                <div class="card">
                                    <div class="card-header">
                                        <p class="card-title">Company Details</p>
                                    </div>
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Company Name</label>
                                                    <input type="text" name="company_name" class="form-control"
                                                        value="{{ old('company_name') }}"
                                                        placeholder="Enter Company Name">
                                                    <p class="text-danger">
                                                        {{ $errors->first('company_name') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="company_email">Company Email</label>
                                                    <input type="email" name="company_email" class="form-control"
                                                        value="{{ old('company_email') }}"
                                                        placeholder="Enter Company Email">
                                                    <p class="text-danger">
                                                        {{ $errors->first('company_email') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="company_phone">Company Phone</label>
                                                    <input type="text" name="company_phone" class="form-control"
                                                        value="{{ old('company_phone') }}"
                                                        placeholder="Enter Company Contact no.">
                                                    <p class="text-danger">
                                                        {{ $errors->first('company_phone') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="pan_vat">PAN No./VAT No. (Optional)</label>
                                                    <input type="text" name="pan_vat" class="form-control"
                                                        value="{{ old('pan_vat') }}"
                                                        placeholder="Enter Company PAN or VAT No.">
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="province">Province no.</label>
                                                    <select name="province" class="form-control province">
                                                        <option value="">--Select a province--</option>
                                                        @foreach ($provinces as $province)
                                                            <option value="{{ $province->id }}">
                                                                {{ $province->eng_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-danger">
                                                        {{ $errors->first('province') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="district">Districts</label>
                                                    <select name="district" class="form-control" id="district">
                                                        <option value="">--Select a province first--</option>
                                                    </select>
                                                    <p class="text-danger">
                                                        {{ $errors->first('district') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="company_address">Company Local Address</label>
                                                    <input type="text" name="company_address" class="form-control"
                                                        value="{{ old('company_address') }}"
                                                        placeholder="Company Address">
                                                    <p class="text-danger">
                                                        {{ $errors->first('company_address') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <p class="card-title">Concerned Person Details</p>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="concerned_name">Name</label>
                                                    <input type="text" name="concerned_name" class="form-control"
                                                        value="{{ old('concerned_name') }}"
                                                        placeholder="Enter Concerned Person Name">
                                                    <p class="text-danger">
                                                        {{ $errors->first('concerned_name') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="concerned_phone">Phone</label>
                                                    <input type="text" name="concerned_phone" class="form-control"
                                                        value="{{ old('concerned_phone') }}"
                                                        placeholder="Enter Concerned Person Phone">
                                                    <p class="text-danger">
                                                        {{ $errors->first('concerned_phone') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="concerned_email">Email</label>
                                                    <input type="email" name="concerned_email" class="form-control"
                                                        value="{{ old('concerned_email') }}"
                                                        placeholder="Enter Concerned Person Email">
                                                    <p class="text-danger">
                                                        {{ $errors->first('concerned_email') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="designation">Designation</label>
                                                    <input type="text" name="designation" class="form-control"
                                                        value="{{ old('designation') }}"
                                                        placeholder="Enter Concerned Person Designation">
                                                    <p class="text-danger">
                                                        {{ $errors->first('designation') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </form>
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
            $('.province').change(function() {
                var province_no = $(this).children("option:selected").val();
                function fillSelect(districts) {
                    document.getElementById("district").innerHTML =
                    districts.reduce((tmp, x) => `${tmp}<option value='${x.id}'>${x.dist_name}</option>`, '');
                }
                function fetchRecords(province_no) {
                    $.ajax({
                        url: 'getdistricts/' + province_no,
                        type: 'get',
                        dataType: 'json',
                        success: function(response) {
                            var districts = response;
                            fillSelect(districts);
                        }
                    });
                }
                fetchRecords(province_no);
            })
        });

    </script>

@endpush
