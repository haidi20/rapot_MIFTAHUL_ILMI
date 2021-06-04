<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-base-url" content="{{ url('/') }}">

    <title>MIFTAHUL ILMI SAMARINDA</title>
    {{-- <link rel="shortcut icon" href="{{asset('icons/favicon.ico')}}"> --}}
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{asset('icons/favicon.ico')}}"/>
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"/> --}}
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    @include('layouts.source-css')
    @yield('style')
  </head>
  <body>
    @yield('body')

    @include('layouts.source-js')
    @yield('script')
  </body>
</html>
