<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link
    rel="icon"
    type="image/png"
    sizes="32x32"
    href="{{ asset('/favicon.png') }}"
  />
  
  <title>Authors Domain</title>
  
  <link rel="preconnect" href="https://fonts.gstatic.com" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet" />

  <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
</head>

<body class="bg-gray-100">
  {{-- TODO: Disable fields/buttons when submitting --}}
  {{-- TODO: Update table to break words --}}
  {{-- TODO: Add rich text editor --}}
  {{-- TODO: Add image upload to posts  --}}
  <div id="app">
      {{ $slot }}

    <snackbar-component />
  </div>

  <script src="{{ mix('/js/app.js') }}"></script>
</body>

</html>
