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

                        <h4>Reset Password</h4>
                        <p class="text-muted mb-4">Reset Password With Chatvia.</p>
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
                                Enter your Email and instructions will be sent to you!
                            </div>
                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf

                                <div class="mb-4">
                                    <label class="form-label">Email</label>
                                    <div class="input-group mb-3 bg-soft-light rounded-3">
                                        <span class="input-group-text text-muted" id="basic-addon5">
                                            <i class="ri-mail-line"></i>
                                        </span>
                                        <input type="email" name="email" :value="old('email')" required autofocus class="form-control form-control-lg border-light bg-soft-light" placeholder="Enter Email" aria-label="Enter Email" aria-describedby="basic-addon5">                                                
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit">Reset</button>
                                </div>

                            </form>
                        </div>
                    </div>
                    <div class="mt-4 text-center">
                        <p>Remember It ? <a href="{{route('login')}}" class="fw-medium text-primary"> Signin </a> </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
