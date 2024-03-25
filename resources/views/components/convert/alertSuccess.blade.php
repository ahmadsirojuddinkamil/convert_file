@if (session()->has('success'))
    <script>
        if (!localStorage.getItem("ownership")) {
            localStorage.setItem("ownership", "{{ session('uuid') }}");
        }
    </script>

    <span class="alert-text d-flex justify-content-center p-2 rounded mb-4 text-white"
        style="background-color: #26D48C">
        {{ session('success') }}
    </span>
@endif