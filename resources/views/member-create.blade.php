@extends('layouts.app')

@section('content')
    <div class="col-md-8 col-12 mx-auto">
        <div class="px-4 pt-5">
            <h4 class="mb-5">
                Invite Members
            </h4>
            <form action="{{ route('invite.members', $companyUuid) }}" method="post">
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
                <div class="mt-5">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('settings', $companyUuid) }}" class="btn btn-secondary ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
