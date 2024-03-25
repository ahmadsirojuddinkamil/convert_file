<!-- Testimonial Start -->
<div id="testimonial"  class="container-fluid testimonial py-5 mb-5">
    <div class="container">
        <div class="text-center mx-auto pb-5 wow fadeIn" data-wow-delay=".3s" style="max-width: 600px;">
            <h5 class="text-primary">Testimonial</h5>
            <h1>Kata Para Pengguna!</h1>
        </div>

        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach ($comments as $index => $comment)
                    <button type="button" data-bs-target="#carouselExampleCaptions"
                        data-bs-slide-to="{{ $index }}" @if ($loop->first) class="active" @endif
                        aria-current="true" aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
            </div>

            <div class="carousel-inner">
                @foreach ($comments as $comment)
                    <div class="carousel-item @if ($loop->first) active @endif"">
                        <img src="{{ asset('assets/home/img/background-white.jpg') }}" class="d-block  w-75"
                            alt="...">

                        <div class="carousel-caption d-md-block">
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-11 col-lg-9 col-xl-7">
                                    <div class="d-flex flex-start mb-5 mt-5">
                                        <div class="card w-100">
                                            <div class="card-body p-4">
                                                <h5>{{ $comment->name }}</h5>
                                                <p class="small text-dark">
                                                    {{ $comment->created_at->diffForHumans() }}</p>

                                                @for ($i = 1; $i <= $comment->star; $i++)
                                                    <span class="text-dark"><i class="bi bi-star-fill"></i></span>
                                                @endfor

                                                <p class="text-dark">
                                                    {{ $comment->comment }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<!-- Testimonial End -->
