<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
        <img src="{{ Storage::disk('uploads')->url($setting->logo) }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ $setting->company_name }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('backend/dist/img/AdminLTELogo.png') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('user.edit', Auth::user()->id) }}" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        {{-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> --}}

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                {{-- <li class="nav-item">
            <a href="pages/widgets.html" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Widgets
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li> --}}


                @if (Auth::user()->can('create-accounts') || Auth::user()->can('view-accounts') || Auth::user()->can('edit-accounts') || Auth::user()->can('remove-accounts'))
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>
                            Account Types
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (Auth::user()->can('view-accounts'))
                        <li class="nav-item">
                            <a href="{{ route('account.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>View Accounts Types</p>
                            </a>
                        </li>
                        @endif
                        @if (Auth::user()->can('create-accounts'))
                        <li class="nav-item">
                            <a href="{{ route('account.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Account Types</p>
                            </a>
                        </li>
                        @endif

                    </ul>
                </li>
                @endif


                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-coins"></i>
                        <p>
                            Financial Accounts
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            @if (Auth::user()->can('view-journals') || Auth::user()->can('create-journals') || Auth::user()->can('edit-journals') || Auth::user()->can('cancel-journals') || Auth::user()->can('approve-journals'))
                                <a href="{{ route('journals.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Journal Vouchers</p>
                                </a>
                            @endif

                        </li>
                        <li class="nav-item">
                            <a href="{{route('journals.trialbalance')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Trial Balance</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Profit and Loss</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cash-register"></i>
                        <p>
                            Items
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('product.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>View Our Items</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('product.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Create New Item</p>
                            </a>
                        </li>
                    </ul>
                </li>

                @if (Auth::user()->can('view-vendor') || Auth::user()->can('create-vendor') || Auth::user()->can('edit-vendor') || Auth::user()->can('cancel-vendor') || Auth::user()->can('approve-vendor'))
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>
                            Suppliers
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (Auth::user()->can('view-vendor'))
                        <li class="nav-item">
                            <a href="{{ route('vendors.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>See All Suppliers</p>
                            </a>
                        </li>
                        @endif
                        @if (Auth::user()->can('create-vendor'))
                        <li class="nav-item">
                            <a href="{{ route('vendors.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add New Supplier</p>
                            </a>
                        </li>
                        @endif

                    </ul>
                </li>
                @endif

                @if (Auth::user()->can('view-daily-expenses') || Auth::user()->can('create-daily-expenses') || Auth::user()->can('edit-daily-expenses') || Auth::user()->can('cancel-daily-expenses') || Auth::user()->can('approve-vendor'))
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-money-check-alt"></i>
                        <p>
                            Daily Expenses
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (Auth::user()->can('view-daily-expenses'))
                        <li class="nav-item">
                            <a href="{{ route('dailyexpenses.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>See All Expenses</p>
                            </a>
                        </li>
                        @endif
                        @if (Auth::user()->can('create-daily-expenses'))
                        <li class="nav-item">
                            <a href="{{ route('dailyexpenses.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Record Expense</p>
                            </a>
                        </li>
                        @endif

                    </ul>
                </li>
                @endif

                <li class="nav-item">
                    <a href="" class="nav-link">
                        <p>
                           <i class="nav-icon fas fa-users"></i> User Management
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                    @if (Auth::user()->can('view-user') || Auth::user()->can('create-user') || Auth::user()->can('edit-user') || Auth::user()->can('remove-user'))
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-user"></i>
                                <p>
                                    Users
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @if (Auth::user()->can('view-user'))
                                    <li class="nav-item">
                                        <a href="{{ route('user.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>View All Users</p>
                                        </a>
                                    </li>
                                @endif
                                @if (Auth::user()->can('create-user'))
                                    <li class="nav-item">
                                        <a href="{{ route('user.create') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Create New User</p>
                                        </a>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif
                    {{-- @if (Auth::user()->can('view-permission') || Auth::user()->can('create-permission') || Auth::user()->can('edit-permission') || Auth::user()->can('remove-permission'))
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-shield-alt"></i>
                                <p>
                                    Permissions
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @if (Auth::user()->can('view-permission'))
                                    <li class="nav-item">
                                        <a href="{{ route('permission.index') }}" class="nav-link">
                                            <i class="nav-icon fas fa-shield-alt"></i>
                                            <p>
                                                Permissions
                                                <i class="right fas fa-angle-left"></i>
                                            </p>
                                        </a>
                                    </li>
                                @endif
                                @if (Auth::user()->can('create-permission'))
                                    <li class="nav-item">
                                        <a href="{{ route('permission.create') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Create New Permission</p>
                                        </a>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif --}}
                    @if (Auth::user()->can('view-role') || Auth::user()->can('create-role') || Auth::user()->can('edit-role') || Auth::user()->can('remove-role'))
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-user-tag"></i>
                                <p>
                                    Roles
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @if (Auth::user()->can('view-role'))
                                    <li class="nav-item">
                                        <a href="{{ route('roles.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>View All Roles</p>
                                        </a>
                                    </li>
                                @endif
                                @if (Auth::user()->can('create-role'))
                                    <li class="nav-item">
                                        <a href="{{ route('roles.create') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Create New Role</p>
                                        </a>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('setting.index') }}" class="nav-link">
                        <p>
                           <i class="nav-icon fas fa-cog"></i> Setting
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-trash"></i>
                        <p>
                            Trash
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('deletedusers') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>User and Role Trash</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('deletedindex') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Account Head Trash</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('deletedproduct') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Items Trash</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('deletedvendor')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Suppliers Trash</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('deletedexpenses') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daily Expenses Trash</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
