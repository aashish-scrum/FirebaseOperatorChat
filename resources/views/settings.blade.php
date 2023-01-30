@extends('layouts.app')

@section('content')
    <div class="col-12 mx-auto">
        <div class="px-4 pt-4">
            <h4 class="mb-0">Settings</h4>
            <div class="row mt-5 justify-content-lg-start">
                @if (Session::exists('success'))
                    <div class="col-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> {{ Session::get('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                aria-orientation="vertical">
                                <button class="nav-link text-start active" id="v-pills-home-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-home" type="button" role="tab"
                                    aria-controls="v-pills-home" aria-selected="true">Property Members</button>
                                <button class="nav-link text-start" id="v-pills-department-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-department" type="button" role="tab"
                                    aria-controls="v-pills-department" aria-selected="false">Departments</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                            aria-labelledby="v-pills-home-tab" tabindex="0">
                            <div class="d-flex justify-content-between">
                                <h5>Property Members</h5>
                                <div>
                                    <button class="btn btn-success btn-sm ms-auto" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop">Invite Member</button>
                                </div>
                            </div>
                            <div class="container">
                                <div class="row py-4">
                                    <div class="col-12">
                                        <table id="" class="example table table-hover responsive nowrap"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Avatar</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Role</th>
                                                    <th>App Access</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($members as $member)
                                                    <tr>
                                                        <td>
                                                            <a href="#">
                                                                <div class="avatar-xs">
                                                                    <span
                                                                        class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                                        {{ substr($member->name,0, 1) }}
                                                                    </span>
                                                                </div>
                                                            </a>
                                                        </td>
                                                        <td>{{ $member->name }}</td>
                                                        <td>{{ $member->email }}</td>
                                                        <td>{{ $member->role }}</td>
                                                        <td>
                                                            <div class="badge {{($member->status == 1) ? 'badge-soft-success' : 'badge-soft-danger'}} rounded-pill">{{($member->status == 1) ? 'Enabled' : 'Disabled'}}</div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="v-pills-department" role="tabpanel"
                            aria-labelledby="v-pills-department-tab" tabindex="0">
                            <div class="d-flex justify-content-between">
                                <h5>Departments</h5>
                                <div>
                                    <a href="{{ route('department.add', $companyUuid) }}"
                                        class="btn btn-primary btn-sm ms-auto">Add Department</a>
                                </div>
                            </div>
                            <div class="container">
                                <div class="row py-4">
                                    <div class="col-12">
                                        <table id="" class="example table table-hover responsive nowrap"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Avatar</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Role</th>
                                                    <th>App Access</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            <div class="avatar-xs">
                                                                <span
                                                                    class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                                    V
                                                                </span>
                                                            </div>
                                                        </a>
                                                    </td>
                                                    <td>Aashish</td>
                                                    <td>Designer@gmail.com</td>
                                                    <td>Designer</td>
                                                    <td>
                                                        <div class="badge badge-soft-success rounded-pill">Enabled</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('invite.members', $companyUuid) }}" method="post">
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Invite Member</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                            </div>
                        </div>
                        <div>
                            <div class="row align-items-center mb-2">
                                <div class="col">
                                    <input type="email" name="email[]" required class="form-control" placeholder="Enter Email"
                                        aria-label="Enter Email">
                                </div>
                                <div class="col">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="role[]" value="admin"
                                            id="flexRadioDefault1">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Admin
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="role[]" value="agent"
                                            id="flexRadioDefault2" checked>
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            Agent
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Send Invitation</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/datatable-table.css') }}">
@endpush
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".example").DataTable({
                aaSorting: [],
                responsive: true,

                columnDefs: [{
                        responsivePriority: 1,
                        targets: 0
                    },
                    {
                        responsivePriority: 2,
                        targets: -1
                    }
                ]
            });

            $(".dataTables_filter input").attr("placeholder", "Search here...").css({
                width: "300px",
                display: "inline-block"
            });

            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
