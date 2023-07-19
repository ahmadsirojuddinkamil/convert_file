<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HighConvert - File</title>
    @include('base.home.css')
    @include('base.home.js')
</head>

<script>
    const checkOwnershipAndDeleteAll = localStorage.getItem('ownership');

    if (checkOwnershipAndDeleteAll) {
        axios.delete(`/delete_all_file/${checkOwnershipAndDeleteAll}`)
            .then(response => {
                console.log(response.data);
            })
            .catch(error => {
                console.error(error);
            });

        localStorage.removeItem('ownership');
    }
</script>

<body>
    @include('template.home.index')
</body>

</html>
