<!-- JavaScript Libraries -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/home/lib/wow/wow.min.js') }}"></script>
<script src="{{ asset('assets/home/lib/easing/easing.min.js') }}"></script>
<script src="{{ asset('assets/home/lib/waypoints/waypoints.min.js') }}"></script>
<script src="{{ asset('assets/home/lib/owlcarousel/owl.carousel.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<!-- Template Javascript -->
<script src="{{ asset('assets/home/js/main.js') }}"></script>

{{-- js for input star comment --}}
<script>
    $(':radio').change(function() {
        console.log('New star rating: ' + this.value);
    });
</script>

{{-- to preview the input image --}}
<script>
    var currentImage = null;

    function previewImage(event) {
        var input = event.target;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var previewImage = document.getElementById('preview');
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
                currentImage = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function resetInputValue() {
        var input = document.getElementById('jpg');
        if (input.value && currentImage) {
            input.value = null;
            currentImage = null;
            var previewImage = document.getElementById('preview');
            previewImage.src = '#';
            previewImage.style.display = 'none';
        }
    }
</script>

{{-- refresh every 15 minutes and run the function --}}
<script>
    const checkOwnershipAndGetValueAll = localStorage.getItem('ownership');

    if (checkOwnershipAndGetValueAll) {
        setTimeout(function() {
            localStorage.removeItem('ownership');

            axios.delete(`/jpg_to_png/${checkOwnershipAndGetValue}`)
                .then(response => {
                    console.log(response.data);
                })
                .catch(error => {
                    console.error(error);
                });

            window.location.href = '/';
        }, 15 * 60 * 1000);
    }
</script>
