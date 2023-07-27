<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HighConvert - Pdf To Jpg</title>
    @include('base.home.css')
</head>

<script>
    const checkOwnership = localStorage.getItem('ownership');

    if (checkOwnership) {
        window.location.href = '/'
    }
</script>

<body>
    @include('template.convert.pdfToPng.index')
    @include('base.home.js')
</body>

</html>
