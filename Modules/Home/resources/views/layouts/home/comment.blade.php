<!-- Contact Start -->
<div id="comment" class="container-fluid py-5">
    <div class="container">
        <div class="text-center mx-auto pb-5 wow fadeIn" data-wow-delay=".3s" style="max-width: 600px;">
            <h5 class="text-primary">Kritik & Saran</h5>
        </div>

        <style>
            @media (max-width: 380px) {
                .icon {
                    font-size: 24px;
                }
            }

            @media (max-width: 350px) {
                .icon {
                    font-size: 20px;
                }
            }

            @media (max-width: 330px) {
                .icon {
                    font-size: 18px;
                }
            }
        </style>

        <div class="contact-detail position-relative p-5">
            <div class="row g-5 d-flex justify-content-center">
                <div class="wow fadeIn" data-wow-delay=".5s">
                    <div class="p-5 rounded contact-form">
                        <form action="/create-comment" method="POST">
                            @csrf

                            <div class="rating mb-1">
                                <label>
                                    <input type="radio" name="star" value=3 />
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                </label>

                                <label>
                                    <input type="radio" name="star" value=4 />
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                </label>

                                <label>
                                    <input type="radio" name="star" value=5 />
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                </label>
                            </div>

                            @error('star')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="mb-4">
                                <input type="text" class="w-100 form-control border-0 py-3" placeholder="Your Name" name="name" id="name-commentator" required>

                                @error('name')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <textarea class="w-100 form-control border-0 py-3" rows="6" cols="10" placeholder="Message" name="comment" required></textarea>

                                @error('comment')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="text-start">
                                <button type="submit" class="btn bg-primary text-white py-3 px-5">Send Message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact End -->
