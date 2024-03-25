<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>File Convert</title>
    @include('home::bases.home.css')
    @include('home::bases.home.js')
</head>

<body>
    <script>
        const ownershipConvert = localStorage.getItem('ownership');
        if (ownershipConvert) {
            localStorage.removeItem('ownership');
        }
    </script>

    @include('home::layouts.home.header')
    @include('home::layouts.home.service')
    @include('home::layouts.home.testimoni')
    @include('home::layouts.home.about')
    @include('home::layouts.home.comment')
    @include('home::layouts.home.footer')
</body>

</html>
