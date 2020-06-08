<!DOCTYPE html>
<html>
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title')</title>
</head>
<body>
  <div>
    @yield('content')
  </div>
</body>
</html>
