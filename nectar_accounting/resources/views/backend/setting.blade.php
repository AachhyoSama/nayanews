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
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Settings</li>
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
        <div class="ibox">
            <div class="row ibox-body">
                <div class="col-sm-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h2>{{ $setting->company_name }}</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{route('setting.update', $setting->id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method("PUT")
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_name">Company Name:</label>
                                            <input type="text" id="company_name" name="company_name" class="form-control" value="{{ $setting->company_name }}" />
                                            <p class="text-danger">
                                                {{ $errors->first('company_name') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_email">Email:</label>
                                            <input type="text" name="company_email" class="form-control" value="{{ $setting->company_email }}">
                                            <p class="text-danger">
                                                {{ $errors->first('company_email') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_phone">Contact no.:</label>
                                            <input type="text" name="company_phone" class="form-control" value="{{ $setting->company_phone }}">
                                            <p class="text-danger">
                                                {{ $errors->first('company_phone') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="province">Province no.</label>
                                            <select name="province" class="form-control province">
                                                <option value="">--Select a province--</option>
                                                @foreach ($provinces as $province)
                                                    <option value="{{ $province->id }}"{{ $province->id == $setting->province_id ? 'selected' : '' }}>{{ $province->eng_name }}</option>
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
                                                @foreach ($district_group as $district)
                                                    <option value="{{ $district->id }}"{{ $district->id == $setting->district_id ? 'selected' : '' }}>{{ $district->dist_name }}</option>
                                                @endforeach
                                            </select>
                                            <p class="text-danger">
                                                {{ $errors->first('district') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address">Local Address</label>
                                            <input type="text" name="address" class="form-control" value="{{ $setting->address }}">
                                            <p class="text-danger">
                                                {{ $errors->first('address') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_logo">Company Logo:</label>
                                            <input type="file" name="company_logo" class="form-control" onchange="loadFile(event)">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="">Current Logo:</label><br>
                                        <img id="output" style="height: 100px;" src="{{ Storage::disk('uploads')->url($setting->logo) }}">
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
                        url: 'http://127.0.0.1:8000/vendors/getdistricts/' + province_no,
                        type: 'get',
                        dataType: 'json',
                        success: function(response) {
                            var districts = response;
                            console.log(districts);
                            fillSelect(districts);
                        }
                    });
                }
                fetchRecords(province_no);
            })
        });
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
  @endpush
