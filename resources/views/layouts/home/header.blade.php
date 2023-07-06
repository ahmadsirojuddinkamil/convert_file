<!-- Navbar Start -->
<div class="container-fluid bg-primary">
    <div class="container">
        <nav class="navbar navbar-dark navbar-expand-lg py-0">

            <a href="/" class="navbar-brand">
                <h1 class="text-white fw-bold d-block">High<span class="text-secondary">Convert</span> </h1>
            </a>

            <button type="button" class="navbar-toggler me-0" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse bg-transparent" id="navbarCollapse">
                <div class="navbar-nav ms-auto mx-xl-auto p-0">
                    <a href="/" class="nav-item nav-link {{ Request::is('/') ? 'active' : '' }}">Home</a>
                    <a href="/about" class="nav-item nav-link {{ Request::is('/about') ? 'active' : '' }}">About</a>
                </div>
            </div>

            <div class="d-none d-xl-flex flex-shirink-0">
                <div class="d-flex flex-column pe-4 border-end">
                    <span class="text-white-50">Ingin data tidak hilang ketika refresh?</span>
                    <span class="text-secondary">Login Sekarang -> </span>
                </div>

                <div class="d-flex align-items-center justify-content-center ms-4 ">
                    <a href="#"><i class="bi bi-person-circle text-white fa-2x"></i> </a>
                </div>
            </div>

        </nav>
    </div>
</div>
<!-- Navbar End -->
