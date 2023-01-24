<x-guest-layout>
    <div class="account-pages my-4 pt-sm-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="text-center mb-4">
                        <a href="{{url('/')}}" class="auth-logo mb-3 d-block">
                            <img src="assets/images/logo-dark.png" alt="" height="30" class="logo logo-dark">
                            <img src="assets/images/logo-light.png" alt="" height="30" class="logo logo-light">
                        </a>

                        <h4>Sign in</h4>
                        <p class="text-muted mb-4">Sign in to continue to Chatvia.</p>
                        
                    </div>

                    <div class="card">
                        <div class="card-body p-4">
                            @if ($errors->any())
                                <div {{ $attributes }}>
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
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <div class="input-group mb-3 bg-soft-light rounded-3">
                                        <span class="input-group-text text-muted" id="basic-addon3">
                                            <i class="ri-user-2-line"></i>
                                        </span>
                                        <input type="email" name="email" value="{{old('email')}}" required autofocus class="form-control form-control-lg border-light bg-soft-light" placeholder="Enter Email" aria-label="Enter Email" aria-describedby="basic-addon3">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    @if (Route::has('password.request'))
                                    <div class="float-end">
                                        <a href="{{ route('password.request') }}" class="text-muted font-size-13">Forgot password?</a>
                                    </div>
                                    @endif
                                    <label class="form-label">Password</label>
                                    <div class="input-group mb-3 bg-soft-light rounded-3">
                                        <span class="input-group-text text-muted" id="basic-addon4">
                                            <i class="ri-lock-2-line"></i>
                                        </span>
                                        <input type="password" name="password" required autocomplete="current-password" class="form-control form-control-lg border-light bg-soft-light" placeholder="Enter Password" aria-label="Enter Password" aria-describedby="basic-addon4">
                                    </div>
                                </div>

                                <div class="form-check mb-4">
                                    <input id="remember_me" type="checkbox" name="remember" class="form-check-input">
                                    <label class="form-check-label" for="remember_me">Remember me</label>
                                </div>
                                <div class="d-grid">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit">Sign in</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @if (Route::has('register'))
                    <div class="mt-4 text-center">
                        <p>Don't have an account ? <a href="{{route('register')}}" class="fw-medium text-primary"> Signup now </a> </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
