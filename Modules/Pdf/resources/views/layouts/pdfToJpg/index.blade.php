@include('components.convert.header')
@include('components.convert.checkOwnership')
@include('home::layouts.home.header')

<div style="background-color: #F3F0EC">
    <div class="text-center container py-5 pl-5">
        
        @include('components.convert.alertSuccess')
        @include('components.convert.title')
        @include('components.convert.progressBar')
        
        <div>
            <form action="/pdf-to-jpg" method="POST" enctype="multipart/form-data" id="reply-pdf-to-jpg">
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

            </ul>
        </div>

    </div>
</div>

@include('home::layouts.home.footer')
@include('components.convert.footer')
