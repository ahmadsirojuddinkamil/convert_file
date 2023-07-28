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
            <h1>PDF to PNG Converter</h1>
            <p>Convert your PDF to PNG documents with incredible accuracy.</p>
            <p class=" text-danger">Files will be deleted after 10 minutes!</p>
        </div>

        <div>
            <form action="" method="POST" enctype="multipart/form-data" id="reply-jpg-to-png">
                @csrf

                <label for="pdf-to-png" class="btn btn-secondary text-white px-5 py-3 rounded-pill">Select Pdf
                    File</label>
                <input id="pdf-to-png" type="file" name="file" style="display: none" accept="application/pdf"
                    onchange="previewImagePdf(event)" onclick="resetInputValuePdf()" required>

                <button type="submit" class="btn text-white bg-primary">Convert Now!</button>
            </form>
        </div>

        <div class=" d-flex justify-content-center">
            <img id="previewPdf" alt="Preview Image" style="display:none; max-width: 150px;" class=" mt-3">
        </div>

        <div class="border border-dark mt-4 p-5 rounded">
            <ul class="horizontal-list">

                @foreach ($findAndGetDataFile as $resultJpg)
                    <li>
                        <ul>
                            <li class="mb-2 text-dark" style="list-style-type: none;">
                                @php
                                    $fileName = pathinfo($resultJpg->name, PATHINFO_FILENAME);
                                    $fileExtension = pathinfo($resultJpg->name, PATHINFO_EXTENSION);
                                    $limitedFileName = Str::limit($fileName, 15);
                                    $displayFileName = $limitedFileName . '.' . $fileExtension;
                                @endphp
                                {{ $displayFileName }}
                            </li>

                            <li style="list-style-type: none;">
                                <img src="{{ asset('storage/' . $resultJpg->file) }}" alt="" height="150px"
                                    width="150px">
                            </li>

                            <a href="/pdf_to_png/{{ $resultJpg->unique_id }}/download"
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
    const checkOwnershipAndGetValue = localStorage.getItem('ownership');

    if (checkOwnershipAndGetValue) {
        document.getElementById('reply-jpg-to-png').action = `/pdf_to_png/${checkOwnershipAndGetValue}/reply`;
    }
</script>
