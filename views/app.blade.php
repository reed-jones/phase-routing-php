<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @if (view()->exists('parts.head'))
        @include('parts.head')
    @else
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endif
    @foreach (config('phase.assets.sass') as $styles)
        <link rel="stylesheet" type="text/css" href="{{ mix(preg_replace('/s(a|c)ss/', 'css', $styles)) }}">
    @endforeach
</head>
 <body>
     @if(config('phase.ssr')) {{-- SSR --}}
        {!! ssr(config('phase.assets.ssr.server')) // App
            ->context(config('phase.initial_state_key'), Vuex::toArray()) // Phased State
            // If ssr fails, we need a container to render the app client-side
            ->fallback('<div id="app" vue-ssr-failed></div>')
            ->render(); !!}
        @if(config('phase.hydrate'))
            @if(config('phase.state')) @vuex @endif
            <script defer src="{{ mix(config('phase.assets.ssr.client')) }}"></script>
        @endif
    @else {{-- Non-SSR --}}
        <div id="app"></div>{{-- App --}}
        @if(config('phase.state')) @vuex @endif {{-- Phased State --}}
        <script src="{{ mix(config('phase.assets.ssr.client')) }}"></script>{{-- Javascript --}}
    @endif
</body>
</html>
