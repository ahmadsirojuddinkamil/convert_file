<!-- Services Start -->
<div id="services" class="container-fluid services py-5">
    <div class="container">

        <div class="text-center mx-auto  wow fadeIn" data-wow-delay=".3s" style="max-width: 600px;">
            <h5 class="text-primary">Layanan Convert</h5>
            <h1>Layanan Yang Dibangun Khusus Untuk Anda!</h1>
        </div>

        @if (session()->has('error'))
            <span class="alert-text d-flex justify-content-center p-2 rounded mb-4 text-white"
                style="background-color: #da4545">
                {{ session('error') }}
            </span>
        @endif

        @if (session()->has('success'))
            <span class="alert-text d-flex justify-content-center p-2 rounded mb-4 text-white"
                style="background-color: #26D48C">
                {{ session('success') }}
            </span>
        @endif

        <div class="row g-5 services-inner">

            {{-- Jpg To Png --}}
            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay=".5s">
                <div class="services-item bg-light">
                    <div class="p-4 text-center services-content">
                        <div class="services-content-icon">
                            <i class="bi bi-card-image fa-7x mb-4 text-primary"></i>
                            <h4 class="mb-4">Jpg To Png</h4>
                            <a href="/jpg-to-png"
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
                            <a href="/png-to-jpg"
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
                            <a href="/jpg-to-pdf"
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
                            <a href="/pdf-to-jpg"
                                class="btn btn-secondary text-white px-5 py-3 rounded-pill">Convert</a>
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
                            <a href="/png-to-pdf"
                                class="btn btn-secondary text-white px-5 py-3 rounded-pill">Convert</a>
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
                            <a href="/pdf-to-png"
                                class="btn btn-secondary text-white px-5 py-3 rounded-pill">Convert</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Services End -->
