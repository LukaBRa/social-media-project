<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title inertia>{{ config('app.name', 'Laravel') }}</title>
    </head>
    <body >
        <h2>Posetite link kako biste resetovali lozinku</h2>
        <p>{{ $link }}</p>
    </body>
</html>
