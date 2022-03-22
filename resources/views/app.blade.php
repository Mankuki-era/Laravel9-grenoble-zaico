<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>{{ config('app.name') }}</title>

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>

  <!-- Favicon -->
  <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">

  <!-- Styles -->
  <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
  <link href="{{ asset('css/share.css') }}" rel="stylesheet">
  <link href="{{ asset('css/auth/login.css') }}" rel="stylesheet">
  <link href="{{ asset('css/item/index.css') }}" rel="stylesheet">
  <link href="{{ asset('css/log/index.css') }}" rel="stylesheet">
  <link href="{{ asset('css/stock/index.css') }}" rel="stylesheet">
  <link href="{{ asset('css/stock/step/input.css') }}" rel="stylesheet">
  <link href="{{ asset('css/stock/step/confirm.css') }}" rel="stylesheet">
  <link href="{{ asset('css/stock/step/result.css') }}" rel="stylesheet">
  <link href="{{ asset('css/modal/item.css') }}" rel="stylesheet">
  <link href="{{ asset('css/modal/import.css') }}" rel="stylesheet">
  <link href="{{ asset('css/modal/alert.css') }}" rel="stylesheet">
  <link href="{{ asset('css/modal/log.css') }}" rel="stylesheet">
  <link href="{{ asset('css/modal/login.css') }}" rel="stylesheet">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.14.1/xlsx.full.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <!-- JQuery -->
  <script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>

</head>
<body>
  <div id="app">    
    <root-component></root-component>
  </div>
</body>
</html>