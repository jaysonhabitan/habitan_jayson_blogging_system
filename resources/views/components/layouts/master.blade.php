<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- <link
    rel="apple-touch-icon"
    sizes="180x180"
    href="{{ request()->merchant->favicon_path ?? '/apple-touch-icon.png?v=4' }}"
  />
  <link
    rel="icon"
    type="image/png"
    sizes="32x32"
    href="{{ request()->merchant->favicon_path ?? '/favicon-32x32.png?v=4' }}"
  />
  <link
    rel="icon"
    type="image/png"
    sizes="16x16"
    href="{{ request()->merchant->favicon_path ?? '/favicon-16x16.png?v=4' }}"
  />
  <link rel="manifest" href="/site.webmanifest" />
  <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#f05f8f">
  <meta name="msapplication-TileColor" content="#f05f8f">
  <meta name="theme-color" content="#0c545b">

  <!-- Primary Meta Tags -->
  <title>{{ !empty($title) ? $title . " | " . request()->merchant->name : request()->merchant->name }}</title>
  <meta name="title" content="{{ !empty($title) ? $title . " | " . request()->merchant->name : request()->merchant->name }}">
  <meta name="description" content="{{ request()->merchant->tagline }}">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ url()->full() }}">
  <meta property="og:title" content="{{ !empty($title) ? $title . " | " . request()->merchant->name : request()->merchant->name }}">
  <meta property="og:description" content="{{ request()->merchant->tagline }}">
  <meta property="og:image" content="{{ request()->merchant->logo_image_path }}">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:url" content="{{ url()->full() }}">
  <meta property="twitter:title" content="{{ !empty($title) ? $title . " | " . request()->merchant->name : request()->merchant->name }}">
  <meta property="twitter:description" content="{{ request()->merchant->tagline }}">
  <meta property="twitter:image" content="{{ request()->merchant->logo_image_path }}"> --}}

  <link rel="preconnect" href="https://fonts.gstatic.com" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet" />

  <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
</head>

<body class="bg-white">
    <x-header-banner />

        <div id="app">
            {{ $slot }}
        </div>

    <x-footer-banner />

  {{ $pageScript }}
  <script src="{{ mix('/js/app.js') }}"></script>
</body>

</html>
