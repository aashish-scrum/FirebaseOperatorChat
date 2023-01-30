<x-guest-layout>
    <div class="account-pages my-4 pt-sm-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="text-center mb-4">
                        <a href="{{ url('/') }}" class="auth-logo mb-3 d-block">
                            <img src="{{asset('assets/images/logo-dark.png')}}" alt="" height="30" class="logo logo-dark">
                            <img src="{{asset('assets/images/logo-light.png')}}" alt="" height="30" class="logo logo-light">
                        </a>

                        <h4>Password Confirm</h4>
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
                            <div class="alert alert-success text-center mb-4" role="alert">
                                This is a secure area of the application. Please confirm your password before continuing.
                            </div>
                            <form method="POST" action="{{ route('password.confirm') }}">
                                @csrf

                                <div class="mb-4">
                                    <label class="form-label">Password</label>
                                    <div class="input-group mb-3 bg-soft-light rounded-3">
                                        <span class="input-group-text text-muted" id="basic-addon5">
                                            <i class="ri-lock-2-line"></i>
                                        </span>
                                        <input type="password" name="password" required autocomplete="current-password" autofocus class="form-control form-control-lg border-light bg-soft-light" placeholder="Enter Email" aria-label="Enter Email" aria-describedby="basic-addon5">                                                
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit">Confirm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

