<div class="side-menu flex-lg-column me-lg-1 ms-lg-0 position-fixed left-0">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ route('dashboard', $companyUuid) }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="" height="30">
            </span>
        </a>

        <a href="index.html" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="" height="30">
            </span>
        </a>
    </div>
    <!-- end navbar-brand-box -->

    <!-- Start side-menu nav -->
    <div class="flex-lg-column my-auto">
        <ul class="nav nav-pills side-menu-nav justify-content-center" role="tablist">
            <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Dasboard">
                <a href="{{ route('dashboard', $companyUuid) }}" class="nav-link">
                    <i class="ri-dashboard-line"></i>
                </a>
            </li>
            <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Chats">
                <a href="{{ route('chat', $companyUuid) }}" class="nav-link">
                    <i class="ri-message-3-line"></i>
                </a>
            </li>
            <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Settings">
                <a href="{{ route('settings', $companyUuid) }}" class="nav-link">
                    <i class="ri-settings-2-line"></i>
                </a>
            </li>
            <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Change Company">
                <a data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample"
                    class="nav-link">
                    <i class="ri-building-4-line"></i>
                </a>
            </li>
            <li class="nav-item dropdown profile-user-dropdown d-inline-block d-lg-none">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt=""
                        class="profile-user rounded-circle">
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Profile <i
                            class="ri-profile-line float-end text-muted"></i></a>
                    <a class="dropdown-item" href="#">Setting <i
                            class="ri-settings-3-line float-end text-muted"></i></a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); this.closest('form').submit();">Log out <i
                                class="ri-logout-circle-r-line float-end text-muted"></i></a>
                    </form>
                </div>
            </li>
        </ul>
    </div>
    <!-- end side-menu nav -->

    <div class="flex-lg-column d-none d-lg-block">
        <ul class="nav side-menu-nav justify-content-center">
            <li class="nav-item">
                <a class="nav-link light-dark-mode" href="#" data-bs-toggle="tooltip" data-bs-trigger="hover"
                    data-bs-placement="right" title="Dark / Light Mode">
                    <i class='ri-sun-line theme-mode-icon'></i>
                </a>
            </li>

            <li class="nav-item btn-group dropup profile-user-dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt=""
                        class="profile-user rounded-circle">
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Profile <i
                            class="ri-profile-line float-end text-muted"></i></a>
                    <a class="dropdown-item" href="#">Setting <i
                            class="ri-settings-3-line float-end text-muted"></i></a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); this.closest('form').submit();">Log out <i
                                class="ri-logout-circle-r-line float-end text-muted"></i></a>
                    </form>
                </div>
            </li>
        </ul>
    </div>
    <!-- Side menu user -->
    <div class="offcanvas offcanvas-start" style="left: 5%;width:300px" tabindex="-1" id="offcanvasExample"
        data-bs-backdrop="false" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Companies</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="list-group">
                @foreach ($companiesNav as $nav)
                    <a href="{{url('/'.request()->segment(1).'/'.$nav->uuid)}}"
                        class="list-group-item list-group-item-action py-3 rounded-0 {{ $nav->uuid == request()->segment(2) ? 'active' : '' }}">{{ $nav->company_name }}</a>
                @endforeach
            </div>
        </div>
        <div class="offcanvas-footer p-0">
            <a href="{{route('company.create')}}" class="btn btn-secondary w-100 rounded-0 d-flex justify-content-center align-items-center"><i class="ri-add-line fs-4 me-2"></i> <span>Add Company</span></a>
        </div>
    </div>
</div>
