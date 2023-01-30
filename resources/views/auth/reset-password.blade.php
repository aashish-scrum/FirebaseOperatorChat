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

                        <h4>Password Reset</h4>
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
                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <div class="input-group bg-soft-light rounded-3  mb-3">
                                        <span class="input-group-text text-muted" id="basic-addon5">
                                            <i class="ri-mail-line"></i>
                                        </span>
                                        <input type="email" name="email" :value="old('email', $request->email)" required autofocus class="form-control form-control-lg bg-soft-light border-light" placeholder="Enter Email" aria-label="Enter Email" aria-describedby="basic-addon5">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Password</label>
                                    <div class="input-group bg-soft-light mb-3 rounded-3">
                                        <span class="input-group-text border-light text-muted" id="basic-addon7">
                                            <i class="ri-lock-2-line"></i>
                                        </span>
                                        <input type="password" name="password" required autocomplete="new-password" class="form-control form-control-lg bg-soft-light border-light" placeholder="Enter Password" aria-label="Enter Password" aria-describedby="basic-addon7">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Confirm Password</label>
                                    <div class="input-group bg-soft-light mb-3 rounded-3">
                                        <span class="input-group-text border-light text-muted" id="basic-addon8">
                                            <i class="ri-lock-2-line"></i>
                                        </span>
                                        <input type="password" name="password_confirmation" required class="form-control form-control-lg bg-soft-light border-light" placeholder="Enter Confirm Password" aria-label="Enter Confirm Password" aria-describedby="basic-addon8">
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit">Reset Password</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
