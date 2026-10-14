<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="color-scheme" content="light dark" />

{{-- La marca se escribe literal: APP_NAME sigue siendo «Laravel» en .env. --}}
<title>
    {{ filled($title ?? null) ? $title.' - Garagic' : 'Garagic' }}
</title>

<link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
<link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml" sizes="any">
<link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
