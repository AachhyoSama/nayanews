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
            <h1 class="m-0">Update Account Information <a href="{{ route('account.index') }}" class="btn btn-primary">See Sub Account Types</a></h1>
          </div><!-- /.col -->
          {{-- <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li>
            </ol>
          </div><!-- /.col --> --}}
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('sub_account.update', $subAccount->id) }}" method="POST">
                            @csrf
                            @method("PUT")
                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input type="text" name="title" class="form-control" placeholder="Enter Account Title" value="{{ $subAccount->title }}">
                                <p class="text-danger">
                                    {{ $errors->first('title') }}
                                </p>
                            </div>

                            <div class="form-group">
                                <label for="account_id">Embedded Account Head:</label>
                                <select name="account_id" class="form-control">
                                    <option value="">--Select an Account Type--</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}"{{ $account->id == $subAccount->account_id ? 'selected' : '' }}> {{ $account->title }} </option>
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
        </div>
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @endsection
  @push('scripts')

  @endpush
