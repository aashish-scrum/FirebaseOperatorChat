@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }} <a href="{{ route('chat') }}">Chat</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!--Start of Tawk.to Script-->
    {{-- <script type="text/javascript">
        var Tawk_API = Tawk_API || {},
            Tawk_LoadStart = new Date();
        (function() {
            var s1 = document.createElement("script"),
                s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/63b7f7c3c2f1ac1e202c06a4/1gm39vli1';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script> --}}
    <!--End of Tawk.to Script-->
    <script>
        var js = document.createElement("script");
        js.async = true;
        // Path to the script that loads the widget
        js.src = "http://192.168.2.116:8000/widget/dist/widget-loader.js"; // This is where the app is currently hosted
        document.getElementsByTagName("head")[0].appendChild(js);
      </script>
@endsection
