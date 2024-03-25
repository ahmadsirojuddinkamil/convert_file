@include('components.convert.header')
@include('home::layouts.home.header')

<div style="background-color: #F3F0EC">
    <div class="text-center container py-5 pl-5">
        @include('components.convert.alertSuccess')
        @include('components.convert.title')
        @include('components.convert.progressBar')

        <div>
            <form action="" method="POST" enctype="multipart/form-data" id="reply-jpg-to-png">
                @csrf

                <label id="select-primary" for="jpg-to-png" style="display: inline-block" class="btn btn-secondary text-white px-5 py-3 rounded-pill">Pilih Jpg File</label>
                <label id="select-dumy" style="display: none" class="btn btn-secondary text-white px-5 py-3 rounded-pill">Pilih Jpg File</label>

                <input id="jpg-to-png" name="file-jpg" class="file-input" type="file" style="display: none" accept="image/jpeg" onchange="validateFileImage(event, 'jpg') && previewImage(event);">
                <input type="hidden" id="preview-image" name="file" required>
                <input type="hidden" id="preview-name" name="name" required>
                
                <button id="button-primary" type="submit" class="btn text-white bg-primary" onclick="startProcess(event)">Convert Now!</button>
                <button id="button-dumy" class="btn text-white bg-primary" style="display: none">Convert Now!</button>
            </form>
        </div>
        
        @include('components.convert.previewImage')

        <div class="border border-dark mt-4 p-5 rounded">
            <ul class="horizontal-list">

                @foreach ($pngFiles as $png)
                    <li>
                        <ul>
                            <li class="mb-2 text-dark" style="list-style-type: none;">
                                @php
                                    $fileName = pathinfo($png->name, PATHINFO_FILENAME);
                                    $fileExtension = pathinfo($png->name, PATHINFO_EXTENSION);
                                    $limitedFileName = mb_substr($fileName, 0, 15);
                                    $displayFileName = $limitedFileName . '.' . $fileExtension;
                                @endphp
                                {{ $displayFileName }}
                            </li>

                            <li style="list-style-type: none;">
                                <img src="{{ asset('storage/' . $png->file) }}" alt="" height="150px"
                                    width="150px">
                            </li>

                            <a href="/jpg-to-png/{{ $png->uuid }}/download"
                                class="btn text-white bg-primary mt-2">Download</a>
                        </ul>

                        <br>
                    </li>
                @endforeach
            </ul>
        </div>

    </div>
</div>

@include('home::layouts.home.footer')
@include('components.convert.footer')
