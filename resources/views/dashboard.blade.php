@extends('layouts.app')

@section('content')
    
@endsection
@push('js')
    {{-- <script>
        (function() {
            var js = document.createElement("script");
            js.async = true;
            // Path to the script that loads the widget
            js.src = "http://192.168.2.116:8000/widget/dist/widget-loader.js"; // This is where the app is currently hosted
            document.getElementsByTagName("head")[0].appendChild(js);
        })();
    </script> --}}
    {{-- <script type="text/javascript">
        var Tawk_API = Tawk_API || {},
            Tawk_LoadStart = new Date();
        (function() {
            var s1 = document.createElement("script"),
                s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'http://192.168.2.116:8000/widget-load';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script> --}}
@endpush