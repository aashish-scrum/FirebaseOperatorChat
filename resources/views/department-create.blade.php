@extends('layouts.app')

@section('content')
    <div class="col-md-8 col-12 mx-auto">
        <div class="px-4 pt-4">
            <h4 class="mb-5">@isset($edit)Edit @else Add @endisset Department</h4>
            <form action="{{route('department.create',$companyUuid)}}" method="post">
                @csrf
                @isset($edit)
                    <input type="hidden" name="id" value="{{$edit->id}}">
                @endisset
                <div class="mb-3">
                    <label for="formGroupExampleInput" class="form-label">Name</label>
                    <input type="text" class="form-control" name="department_name" value="@if(old('department_name')){{old('department_name')}}@elseif(isset($edit)){{$edit->department_name}}@endif" id="formGroupExampleInput" placeholder="Enter Department Name">
                </div>
                <div class="mb-3">
                    <label for="formGroupExampleInput2" class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="5" placeholder="Enter Department Description">@if(old('description')){{old('description')}}@elseif(isset($edit)){{$edit->description}}@endif</textarea>
                </div>
                <div class="mb-5">
                    <label for="formGroupExampleInput3" class="form-label w-100">Members</label>
                    @foreach ($members as $key=>$member)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" @if(isset($edit) && in_array($member->id,$edit->users->pluck('id')->toArray())) checked @endif name="members[]" type="checkbox" id="inlineCheckbox{{$key}}" value="{{$member->id}}">
                        <label class="form-check-label" for="inlineCheckbox{{$key}}">{{$member->name}}</label>
                    </div>
                    @endforeach
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('settings',$companyUuid) }}" class="btn btn-secondary ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
