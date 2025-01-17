<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="minimum-scale=1.0, width=device-width, maximum-scale=1.0, user-scalable=no">

    <title>@yield('title') &ndash; SYNC</title>
    {{ HTML::style("css/bootstrap.min.css") }}
    {{ HTML::style("css/front-end.css") }}
    <link rel="shortcut icon" type="image/x-icon" href="/refresh.ico"/>
    
    <!--[if lt IE 9]>
        <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6/html5shiv.min.js"></script>
        <script src="//oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div class="fader"></div>

    @include('partials.front.topnav')

    <div class="container">
        @yield('content')
    </div>

    {{ HTML::script("https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js") }}
    {{ HTML::script("js/bootstrap.min.js") }}
    {{ HTML::script("js/front-end.js") }}
    {{ HTML::script("js/moment/moment.min.js") }}
    {{ HTML::script("js/moment/nl.js") }}
    @yield('script')
</body>
</html>