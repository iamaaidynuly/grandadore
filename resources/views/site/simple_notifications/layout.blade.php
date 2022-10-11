<!doctype html><html lang="{!! app()->getLocale() !!}"><head>
    <meta charset="utf-8">
    <title></title>
    <style>
        body {
            margin:0;
            padding:15px;
            font-family: "Segoe UI", Arial, sans-serif;
            font-size:16px;
            color:#333333;
        }
        .logo>img {
            width:200px;
            height:auto;
        }
        p {
            margin-top: 3px;
            margin-bottom: 8px;
        }
        th,td{
            padding:2px 4px;
        }
    </style>
</head><body>
    <div class="logo"><img src="{{ asset('images/logo.svg') }}" alt=""></div>
    <div style="margin-top:20px;">
        @yield('content')

    </div>
</body></html>
