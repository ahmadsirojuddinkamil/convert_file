<div style="background-color: #F3F0EC">
    <div class="text-center container py-5 pl-5">
        <div>
            <h1>PDF to WORD Converter</h1>
            <p>Convert your PDF to WORD documents with incredible accuracy.</p>
        </div>

        <div>
            <form action="/pdf_to_word" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="pdf" class="btn btn-secondary text-white px-5 py-3 rounded-pill">Select Pdf File</label>
                <input id="pdf" type="file" name="file" style="display:none" accept="application/pdf">

                <button type="submit" class="btn text-white bg-primary">Convert Now!</button>
            </form>
        </div>

        <div class="border border-dark mt-4 p-5 rounded">
            ok
        </div>
    </div>
</div>
