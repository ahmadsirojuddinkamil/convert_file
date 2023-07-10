<!-- Services Start -->
<div class="container-fluid services py-5">
    <div class="container">

        <div class="text-center mx-auto pb-5 wow fadeIn" data-wow-delay=".3s" style="max-width: 600px;">
            <h5 class="text-primary">Layanan Convert</h5>
            <h1>Layanan Yang Dibangun Khusus Untuk Anda!</h1>
        </div>

        <div class="row g-5 services-inner">

            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay=".5s">
                <div class="services-item bg-light">
                    <div class="p-4 text-center services-content">
                        <div class="services-content-icon">
                            <i class="fa fa-file-code fa-7x mb-4 text-primary"></i>

                            <h4 class="mb-4">Jpg To Png</h4>

                            <a href="" id="show_convert_png"
                                class="btn btn-secondary text-white px-5 py-3 rounded-pill">Convert now</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay=".5s">
                <div class="services-item bg-light">
                    <div class="p-4 text-center services-content">
                        <div class="services-content-icon">
                            <i class="fa fa-file-code fa-7x mb-4 text-primary"></i>
                            <h4 class="mb-4">Png To Jpg</h4>
                            <a href="" class="btn btn-secondary text-white px-5 py-3 rounded-pill">Convert</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Services End -->

<script>
    const check_ownership_and_get_value = localStorage.getItem('ownership');
    const get_id_link_show = document.getElementById('show_convert_png');

    get_id_link_show.href = check_ownership_and_get_value ?
        `/jpg_to_png/${check_ownership_and_get_value}/file` :
        `/jpg_to_png`;
</script>
