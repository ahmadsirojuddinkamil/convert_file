<div style="background-color: #F3F0EC">
    <div class="text-center container py-5 pl-5">

        @if (session()->has('success'))
            <script>
                if (!localStorage.getItem("ownership")) {
                    localStorage.setItem("ownership", "{{ session('uuid') }}");
                }
            </script>

            <span class="alert-text d-flex justify-content-center p-2 rounded mb-4 text-white"
                style="background-color: #26D48C">
                {{ session('success') }}
            </span>
        @endif

        <div>
            <h1>JPG to PNG Converter show</h1>
            <p>Convert your JPG to PNG documents with incredible accuracy.</p>
        </div>

        <div>
            <form action="/jpg_to_png" method="POST" enctype="multipart/form-data">
                @csrf

                <label for="jpg" class="btn btn-secondary text-white px-5 py-3 rounded-pill">Select Pdf File</label>
                <input id="jpg" type="file" name="file" style="display:none" accept="image/jpeg">

                <input type="text" id="uuid" name="uuid" style="display:none" hidden>

                <button type="submit" class="btn text-white bg-primary">Convert Now!</button>
            </form>
        </div>

        <div class="border border-dark mt-4 p-5 rounded">
            <ul class="horizontal-list">

                @foreach ($find_and_get_data_file as $result_png)
                    <li>
                        <ul>
                            <li class="mb-2 text-dark" style="list-style-type: none;">
                                {{ Str::limit($result_png->name, 15) }}
                            </li>

                            <li style="list-style-type: none;">
                                <img src="{{ asset('storage/' . $result_png->file) }}" alt="" height="150px"
                                    width="150px">
                            </li>

                            <a href="/jpg_to_png/{{ $result_png->uuid }}/download"
                                class="btn text-white bg-primary mt-2">Download</a>
                        </ul>

                        <br>
                    </li>
                @endforeach
            </ul>
        </div>

    </div>
</div>

<script>
    const check_ownership_and_get_value = localStorage.getItem('ownership');

    if (check_ownership_and_get_value) {
        document.getElementById('uuid').value = localStorage.getItem('ownership');
    }

    setTimeout(function() {
        localStorage.removeItem('ownership');

        axios.delete(`/jpg_to_png/${check_ownership_and_get_value}`)
            .then(response => {
                console.log(response.data);
            })
            .catch(error => {
                console.error(error);
            });

        window.location.href = '/';
    }, 30 * 60 * 1000);
</script>
