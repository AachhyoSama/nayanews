@extends('backend.layouts.app')
@push('styles')
    <style>
        .this-one {
            color: #555;
            font-size: 14px;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 10px 0;
        }

        .this-one hr {
            margin: 50px 0;
        }

        .this-one ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        ul.padding {
            padding-left: 25px;
        }

        .this-one .container {
            margin: 40px auto;
            max-width: 700px;
        }

        .this-one li {
            margin-top: 1em;
        }

        .this-one label {
            font-weight: bold;
        }

    </style>
@endpush
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create New Role <a href="{{ route('roles.index') }}" class="btn btn-sm btn-primary">View Roles</a>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('roles.store') }}" method="POST" class="bg-light p-3">
                            @csrf
                            @method('POST')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Role Name: </label>
                                        <input type="text" name="name" class="form-control" value="{{ @old('name') }}"
                                            placeholder="Enter Permission Name">
                                        @error('name')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="this-one col-md-4">
                                    <ul>
                                        <li>
                                            <input type="checkbox" /> Manage User
                                            <ul class="padding">
                                                @php
                                                    $userperms = DB::table('permissions')
                                                        ->where('column_name', 'user')
                                                        ->get();
                                                @endphp
                                                @foreach ($userperms as $userperm)
                                                    <li>
                                                        <input type="checkbox" name="permissions[]" value="{{ $userperm->id }}">
                                                        {{ $userperm->name }}
                                                    </li>
                                                @endforeach

                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <div class="this-one col-md-4">
                                    <ul>
                                        <li>
                                            <input type="checkbox" /> Manage Role
                                            <ul class="padding">
                                                @php
                                                    $userperms = DB::table('permissions')
                                                        ->where('column_name', 'role')
                                                        ->get();
                                                @endphp
                                                @foreach ($userperms as $userperm)
                                                    <li>
                                                        <input type="checkbox" name="permissions[]" value="{{ $userperm->id }}">
                                                        {{ $userperm->name }}
                                                    </li>
                                                @endforeach

                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <div class="this-one col-md-4">
                                    <ul>
                                        <li>
                                            <input type="checkbox" /> Manage Permission
                                            <ul class="padding">
                                                @php
                                                    $userperms = DB::table('permissions')
                                                        ->where('column_name', 'permission')
                                                        ->get();
                                                @endphp
                                                @foreach ($userperms as $userperm)
                                                    <li>
                                                        <input type="checkbox" name="permissions[]" value="{{ $userperm->id }}">
                                                        {{ $userperm->name }}
                                                    </li>
                                                @endforeach

                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <div class="this-one col-md-4">
                                    <ul>
                                        <li>
                                            <input type="checkbox" /> Manage Journals
                                            <ul class="padding">
                                                @php
                                                    $userperms = DB::table('permissions')
                                                        ->where('column_name', 'journals')
                                                        ->get();
                                                @endphp
                                                @foreach ($userperms as $userperm)
                                                    <li>
                                                        <input type="checkbox" name="permissions[]" value="{{ $userperm->id }}">
                                                        {{ $userperm->name }}
                                                    </li>
                                                @endforeach

                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <div class="this-one col-md-4">
                                    <ul>
                                        <li>
                                            <input type="checkbox" /> Manage  Vendors
                                            <ul class="padding">
                                                @php
                                                    $userperms = DB::table('permissions')
                                                        ->where('column_name', 'vendor')
                                                        ->get();
                                                @endphp
                                                @foreach ($userperms as $userperm)
                                                    <li>
                                                        <input type="checkbox" name="permissions[]" value="{{ $userperm->id }}">
                                                        {{ $userperm->name }}
                                                    </li>
                                                @endforeach

                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <div class="this-one col-md-4">
                                    <ul>
                                        <li>
                                            <input type="checkbox" /> Manage Daily Expenses
                                            <ul class="padding">
                                                @php
                                                    $userperms = DB::table('permissions')
                                                        ->where('column_name', 'daily_expenses')
                                                        ->get();
                                                @endphp
                                                @foreach ($userperms as $userperm)
                                                    <li>
                                                        <input type="checkbox" name="permissions[]" value="{{ $userperm->id }}">
                                                        {{ $userperm->name }}
                                                    </li>
                                                @endforeach

                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <div class="this-one col-md-4">
                                    <ul>
                                        <li>
                                            <input type="checkbox" /> Manage Accounts
                                            <ul class="padding">
                                                @php
                                                    $userperms = DB::table('permissions')
                                                        ->where('column_name', 'accounts')
                                                        ->get();
                                                @endphp
                                                @foreach ($userperms as $userperm)
                                                    <li>
                                                        <input type="checkbox" name="permissions[]" value="{{ $userperm->id }}">
                                                        {{ $userperm->name }}
                                                    </li>
                                                @endforeach

                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success mt-3">Submit</button>
                        </form>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
    <script>
        $('li :checkbox').on('click', function() {
            var $chk = $(this),
                $li = $chk.closest('li'),
                $ul, $parent;
            if ($li.has('ul')) {
                $li.find(':checkbox').not(this).prop('checked', this.checked)
            }
            do {
                $ul = $li.parent();
                $parent = $ul.siblings(':checkbox');
                if ($chk.is(':checked')) {
                    $parent.prop('checked', $ul.has(':checkbox:not(:checked)').length == 0)
                } else {
                    $parent.prop('checked', false)
                }
                $chk = $parent;
                $li = $chk.closest('li');
            } while ($ul.is(':not(.someclass)'));
        });
    </script>
@endpush
