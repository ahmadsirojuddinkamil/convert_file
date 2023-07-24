<!-- Services Start -->
<div class="container-fluid services py-5">
    <div class="container">

        <div class="text-center mx-auto pb-5 wow fadeIn" data-wow-delay=".3s" style="max-width: 600px;">
            <h5 class="text-primary">Layanan Convert</h5>
            <h1>Layanan Yang Dibangun Khusus Untuk Anda!</h1>
        </div>

        <div class="row g-5 services-inner">

            {{-- Jpg To Png --}}
            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay=".5s">
                <div class="services-item bg-light">
                    <div class="p-4 text-center services-content">
                        <div class="services-content-icon">
                            <i class="bi bi-card-image fa-7x mb-4 text-primary"></i>
                            <h4 class="mb-4">Jpg To Png</h4>
                            <a href="/jpg_to_png"
                                class="btn btn-secondary text-white px-5 py-3 rounded-pill">Convert</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Png To Jpg --}}
            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay=".5s">
                <div class="services-item bg-light">
                    <div class="p-4 text-center services-content">
                        <div class="services-content-icon">
                            <i class="bi bi-file-earmark-image fa-7x mb-4 text-primary"></i>
                            <h4 class="mb-4">Png To Jpg</h4>
                            <a href="/png_to_jpg"
                                class="btn btn-secondary text-white px-5 py-3 rounded-pill">Convert</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Jpg To Pdf --}}
            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay=".5s">
                <div class="services-item bg-light">
                    <div class="p-4 text-center services-content">
                        <div class="services-content-icon">
                            <i class="bi bi-file-earmark-image-fill fa-7x mb-4 text-primary"></i>
                            <h4 class="mb-4">Jpg To Pdf</h4>
                            <a href="/jpg_to_pdf"
                                class="btn btn-secondary text-white px-5 py-3 rounded-pill">Convert</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pdf To Jpg --}}
            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay=".5s">
                <div class="services-item bg-light">
                    <div class="p-4 text-center services-content">
                        <div class="services-content-icon">
                            <i class="bi bi-file-image fa-7x mb-4 text-primary"></i>
                            <h4 class="mb-4">Pdf To Jpg</h4>
                            <a href="" class="btn btn-secondary text-white px-5 py-3 rounded-pill">Convert</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Png To Pdf --}}
            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay=".5s">
                <div class="services-item bg-light">
                    <div class="p-4 text-center services-content">
                        <div class="services-content-icon">
                            <i class="bi bi-file-image-fill fa-7x mb-4 text-primary"></i>
                            <h4 class="mb-4">Png To Pdf</h4>
                            <a href="" class="btn btn-secondary text-white px-5 py-3 rounded-pill">Convert</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pdf To Png --}}
            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay=".5s">
                <div class="services-item bg-light">
                    <div class="p-4 text-center services-content">
                        <div class="services-content-icon">
                            <i class="bi bi-image-fill fa-7x mb-4 text-primary"></i>
                            <h4 class="mb-4">Pdf To Png</h4>
                            <a href="" class="btn btn-secondary text-white px-5 py-3 rounded-pill">Convert</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Services End -->

{{-- <script>
    const checkOwnershipAndGetValue = localStorage.getItem('ownership');
    const getIdLinkShowPng = document.getElementById('showConvertPng');
    const getIdLinkShowJpg = document.getElementById('showConvertJpg');

    getIdLinkShowPng.href = checkOwnershipAndGetValue ?
        `/jpg_to_png/${checkOwnershipAndGetValue}/file` :
        `/jpg_to_png`;

    getIdLinkShowJpg.href = checkOwnershipAndGetValue ?
        `/png_to_jpg/${checkOwnershipAndGetValue}/file` :
        `/png_to_jpg`;
</script> --}}
