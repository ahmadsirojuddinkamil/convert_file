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
