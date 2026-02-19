<!DOCTYPE html>
<html>
<head>
    {!! $head !!}
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { width: {{ $width }}px; height: {{ $height }}px; overflow: hidden; }
    </style>
</head>
<body>{!! $templateContent !!}</body>
</html>
