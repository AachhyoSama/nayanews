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
          <div class="col-sm-9">
            <h1 class="m-0">Add Account Types <a href="{{ route('account.index') }}" class="btn btn-success btn-sm">View Main Account Types</a> <a href="{{ route('sub_account.index') }}" class="btn btn-success btn-sm">View Sub Account Types</a> <a href="{{ route('child_account.index') }}" class="btn btn-success btn-sm">View Child Account Types</a></h1>
          </div><!-- /.col -->
          <div class="col-sm-3">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
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
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header text-center">
                            <h3>Main Account</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('account.store') }}" method="POST" id="account_form">
                                @csrf
                                @method("POST")
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" name="account_title" class="form-control" placeholder="Enter Account Title">
                                    <p class="text-danger">
                                        {{$errors->first('account_title')}}
                                    </p>
                                </div>

                                <button type="submit" class="btn btn-success">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header text-center">
                            <h3>Sub Account</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('sub_account.store') }}" method="POST">
                                @csrf
                                @method("POST")
                                <div class="form-group">
                                    <label for="title">Title:</label>
                                    <input type="text" name="subaccount_title" class="form-control" placeholder="Enter Sub-Account Title">
                                    <p class="text-danger">
                                        {{ $errors->first('subaccount_title') }}
                                    </p>
                                </div>

                                <div class="form-group">
                                    <label for="account_id">Embedded Account Head:</label>
                                    <select name="account_id" class="form-control">
                                        <option value="">--Select an Account Type--</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->title }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">
                                        {{ $errors->first('account_id') }}
                                    </p>
                                </div>

                                <button type="submit" class="btn btn-success">Save</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header text-center">
                            <h3>Child Account</h3>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('child_account.store') }}" method="POST">
                                @csrf
                                @method("POST")
                                <div class="form-group">
                                    <label for="title">Title:</label>
                                    <input type="text" name="childaccount_title" class="form-control" placeholder="Enter Child-Account Title">
                                    <p class="text-danger">
                                        {{ $errors->first('childaccount_title') }}
                                    </p>
                                </div>

                                <div class="form-group">
                                    <label for="sub_account_id">Embedded Sub-Account Head:</label>
                                    <select name="sub_account_id" class="form-control">
                                        <option value="">--Select an Account Type--</option>
                                        @foreach ($sub_accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->title }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">
                                        {{ $errors->first('sub_account_id') }}
                                    </p>
                                </div>

                                <button type="submit" class="btn btn-success">Save</button>
                            </form>
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
    $("#account_form").on("submit", function(e) {
        // e.preventDefault();

        $.ajax({
            url:$(this).attr('action'),
            method:$(this).attr('method'),
            data:new FormData(this),
            dataType:'json',
            contentType:false,
            beforeSend:function(){
                $(document).find('span.error-text').text('');
            },
            success:function(data){
                if(data.status == 0) {
                    console.log('What');
                    $.each(data.error, function(prefix, val){
                        $('span.'+prefix+'_error').text(val[0]);
                    });
                }else {
                    $("account_form").reset();
                    alert('Done');
                }
            }
        });
    });

</script>
  @endpush
