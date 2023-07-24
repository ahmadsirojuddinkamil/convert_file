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
            <h1>PNG to JPG Converter</h1>
            <p>Convert your PNG to JPG documents with incredible accuracy.</p>
            <p class=" text-danger">Files will be deleted after 10 minutes!</p>
        </div>

        <div>
            <form action="/png_to_jpg" method="POST" enctype="multipart/form-data">
                @csrf

                <label for="jpg" class="btn btn-secondary text-white px-5 py-3 rounded-pill">Select Png File</label>
                <input id="jpg" type="file" name="file" style="display: none" accept="image/png"
                    onchange="previewImage(event)" onclick="resetInputValue()" required>

                <button type="submit" class="btn text-white bg-primary">Convert Now!</button>
            </form>
        </div>

        <div class=" d-flex justify-content-center">
            <img id="preview" alt="Preview Image" style="display:none; max-width: 150px;" class=" mt-3">
        </div>

        <div class="border border-dark mt-4 p-5 rounded">
            <ul class="horizontal-list">

            </ul>
        </div>

    </div>
</div>
