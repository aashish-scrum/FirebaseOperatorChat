<x-guest-layout>
    <div class="account-pages my-4 pt-sm-4">
        <div class="container position-relative">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="text-center mb-4">
                        <a href="{{ url('/') }}" class="auth-logo mb-3 d-block">
                            <img src="{{asset('assets/images/logo-dark.png')}}" alt="" height="30" class="logo logo-dark">
                            <img src="{{asset('assets/images/logo-light.png')}}" alt="" height="30" class="logo logo-light">
                        </a>
                        <h4>Company Details</h4>
                    </div>

                    <div class="card">
                        <div class="card-body p-4">
                            @if ($errors->any())
                                <div class="">
                                    <div class="fs-5 text-danger">
                                        {{ __('Whoops! Something went wrong.') }}
                                    </div>

                                    <ul class="mt-3 text-danger">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{route('company.save')}}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label for="formGroupExampleInput" class="form-label">Company Name</label>
                                    <input type="text" name="company" class="form-control" id="formGroupExampleInput"
                                        placeholder="Enter Company Name">
                                </div>
                                <div class="mb-3">
                                    <label for="formGroupExampleInput2" class="form-label">Site Url</label>
                                    <input type="url" name="site_url" class="form-control" id="formGroupExampleInput2"
                                        placeholder="Enter Company Site Url">
                                </div>
                                <div class="mb-3">
                                    <label for="formGroupExampleInput3" class="form-label w-100">Widget Name</label>
                                    <input type="text" name="widget_name" class="form-control" id="formGroupExampleInput3"
                                        placeholder="Enter Widget Name">
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="position-absolute top-0 left-3">
                <a href="{{$back}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</x-guest-layout>
