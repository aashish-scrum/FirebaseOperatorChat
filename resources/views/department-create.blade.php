@extends('layouts.app')

@section('content')
    <div class="col-md-8 col-12 mx-auto">
        <div class="px-4 pt-4">
            <h4 class="mb-5">Add Department</h4>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="formGroupExampleInput" class="form-label">Name</label>
                    <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Enter Department Name">
                </div>
                <div class="mb-3">
                    <label for="formGroupExampleInput2" class="form-label">Description</label>
                    <textarea name="description" id="" class="form-control" rows="5"
                        placeholder="Enter Department Description"></textarea>
                </div>
                <div class="mb-5">
                    <label for="formGroupExampleInput3" class="form-label w-100">Members</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                        <label class="form-check-label" for="inlineCheckbox1">Aashish Nayak</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
                        <label class="form-check-label" for="inlineCheckbox2">Aashish Nayak</label>
                    </div>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('settings',$companyUuid) }}" class="btn btn-secondary ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
