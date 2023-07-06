<div style="background-color: #F3F0EC">
    <div class="text-center container py-5 pl-5">
        <div>
            <h1>WORD to PDF Converter</h1>
            <p>Convert your WORD to PDF documents with incredible accuracy.</p>
        </div>

        <div>
            <form action="/jpg_to_png" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="pdf" class="btn btn-secondary text-white px-5 py-3 rounded-pill">Select Pdf File</label>
                <input id="pdf" type="file" name="file" style="display:none"
                    accept="application/vnd.openxmlformats-officedocument.wordprocessingml.document">

                <button type="submit" class="btn text-white bg-primary">Convert Now!</button>
            </form>
        </div>

        <div class="border border-dark mt-4 p-5 rounded">
            file anda!
        </div>
    </div>
</div>
