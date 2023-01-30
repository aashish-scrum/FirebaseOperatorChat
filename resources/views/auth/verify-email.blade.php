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
                            <div class="mb-4 text-muted">
                                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                            </div>
                            @if (session('status') == 'verification-link-sent')
                                <div class="mb-4 text-success">
                                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                                </div>
                            @endif
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <div class="d-grid">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit">Resend Verification Email</button>
                                </div>
                            </form>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                
                                <button type="submit" class="btn btn-link">
                                    {{ __('Log out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

