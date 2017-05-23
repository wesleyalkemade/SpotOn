<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="minimum-scale=1.0, width=device-width, maximum-scale=1.0, user-scalable=no">

    <title>@yield('title') &ndash; Admin / SYNC</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/css/datetimepicker.min.css" rel="stylesheet" />
    <link href="/css/backend.css" rel="stylesheet" />
    <link rel="shortcut icon" type="image/x-icon" href="/refresh.ico"/>

    <!--[if lt IE 9]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    @include('partials.back.topnav')

    <aside class="sidebar hidden-xs">
        <div class="user">
            <p>{{{ $user->firstname }}} {{{ $user->lastname }}}</p>
            <p>{{{ $user->email }}}</p>
        </div>

        @include('partials.back.sidebar')
    </aside>

    <div class="content">
        <div class="container-fluid">
            @yield('content')
        </div>
    </div>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="/js/jquery.js"><\/script>')</script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/backend.js" ></script>
    @yield('script')
</body>
</html>