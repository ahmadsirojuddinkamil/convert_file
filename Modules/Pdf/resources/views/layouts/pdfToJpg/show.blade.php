@include('components.convert.header')
@include('home::layouts.home.header')

<div style="background-color: #F3F0EC">
    <div class="text-center container py-5 pl-5">

        @include('components.convert.alertSuccess')
        @include('components.convert.title')
        @include('components.convert.progressBar')

        <div>
            <form action="" method="POST" enctype="multipart/form-data" id="reply-pdf-to-jpg">
                @csrf

                <label id="select-primary" for="pdf-to-jpg" style="display: inline-block" class="btn btn-secondary text-white px-5 py-3 rounded-pill">Pilih Pdf File</label>
                <label id="select-dumy" style="display: none" class="btn btn-secondary text-white px-5 py-3 rounded-pill">Pilih Pdf File</label>

                <input id="pdf-to-jpg" type="file" name="file_pdf" style="display: none" accept="application/pdf" onchange="validateFilePdf(event, 'pdf')">
            
                <input type="hidden" name="link_pdf" id="previewImagePdf">
                <input type="hidden" name="name" id="previewNamePdf">
            
                <button id="button-primary" type="submit" class="btn text-white bg-primary" onclick="animasiSubmitPdf(event)">Convert Now!</button>
                <button id="button-dumy" class="btn text-white bg-primary" style="display: none">Convert Now!</button>
            </form>
        </div>

        <br>
        @include('components.convert.previewPdf')

        <div class="border border-dark mt-4 p-5 rounded">
            <ul class="horizontal-list">

                @foreach ($jpgFiles as $jpg)
                    <li>
                        <ul>
                            <li class="mb-2 text-dark" style="list-style-type: none;">
                                @php
                                    $fileName = pathinfo($jpg->name, PATHINFO_FILENAME);
                                    $fileExtension = pathinfo($jpg->name, PATHINFO_EXTENSION);
                                    $limitedFileName = mb_substr($fileName, 0, 15);
                                    $displayFileName = $limitedFileName . '.' . $fileExtension;
                                @endphp
                                {{ $displayFileName }}
                            </li>

                            <li style="list-style-type: none;">
                                <img src="{{ asset('storage/' . $jpg->file) }}" alt="" height="150px"
                                    width="150px">
                            </li>

                            <a href="/pdf-to-jpg/{{ $jpg->uuid }}/download"
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