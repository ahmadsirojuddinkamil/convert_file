@include('components.convert.header')
@include('components.convert.checkOwnership')
@include('home::layouts.home.header')

<div style="background-color: #F3F0EC">
    <div class="text-center container py-5 pl-5">

        @include('components.convert.alertSuccess')
        @include('components.convert.title')
        @include('components.convert.progressBar')

        <div>
            <form action="/png-to-pdf" method="POST" enctype="multipart/form-data">
                @csrf

                <label id="select-primary" for="png-to-pdf" style="display: inline-block" class="btn btn-secondary text-white px-5 py-3 rounded-pill">Pilih Png File</label>
                <label id="select-dumy" style="display: none" class="btn btn-secondary text-white px-5 py-3 rounded-pill">Pilih Png File</label>

                <input id="png-to-pdf" type="file" name="file" style="display: none" accept="image/png" onchange="validateFileImage(event, 'png') && previewImage(event);">
        
                <button id="button-primary" type="submit" class="btn text-white bg-primary" onclick="startProcess(event)">Convert Now!</button>
                <button id="button-dumy" class="btn text-white bg-primary" style="display: none">Convert Now!</button>
            </form>
        </div>

        @include('components.convert.previewImage')

        <div class="border border-dark mt-4 p-5 rounded">
            <ul class="horizontal-list">

            </ul>
        </div>

    </div>
</div>

@include('home::layouts.home.footer')
@include('components.convert.footer')
