<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    
    <title>SiLapor - Dashboard</title>

    <link rel="stylesheet" href="{{ asset('kapella/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('kapella/vendors/base/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('kapella/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('kapella/images/favicon.png') }}" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const token = localStorage.getItem('user_token');
        if (!token) {
            window.location.href = '/login';
        }
    </script>

    <script>
        const originalFetch = window.fetch;
        window.fetch = async function(...args) {
            try {
                const response = await originalFetch(...args);

                // Jika API membalas 401 (Unauthorized)
                if (response.status === 401) {
                    if (!window.location.pathname.includes('/login')) {
                        // Gunakan Alert biasa dulu disini supaya tidak konflik render
                        alert("Sesi login telah berakhir. Silakan login kembali.");
                        localStorage.removeItem('user_token');
                        localStorage.removeItem('user_name');
                        localStorage.removeItem('school_name');
                        window.location.href = '/login';
                    }
                }
                return response;
            } catch (error) {
                console.error("Network Error:", error);
                throw error;
            }
        };
    </script>
</head>

<body>
    <div class="container-scroller">
        <div class="horizontal-menu">
            <nav class="navbar top-navbar col-lg-12 col-12 p-0">
                <div class="container-fluid">
                    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-between">
                        
                        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                            <a class="navbar-brand brand-logo" href="{{ url('/') }}">
                                <img src="{{ asset('kapella/images/logo.png') }}" alt="logo"/>
                            </a>
                            <a class="navbar-brand brand-logo-mini" href="{{ url('/') }}">
                                <img src="{{ asset('kapella/images/logo-mini.svg') }}" alt="logo" />
                            </a>
                        </div>

                        <ul class="navbar-nav navbar-nav-right">
                            <li class="nav-item nav-profile dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                                    <span class="nav-profile-name" id="nav-school-name">Halo, Memuat...</span>
                                    <span class="online-status"></span>
                                    <img src="{{ asset('kapella/images/faces/face28.png') }}" alt="profile" />
                                </a>
                                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                                    <a class="dropdown-item" href="#" onclick="logoutSystem()">
                                        <i class="mdi mdi-logout text-primary"></i>
                                        Logout
                                    </a>
                                </div>
                            </li>
                        </ul>

                        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="horizontal-menu-toggle">
                            <span class="mdi mdi-menu"></span>
                        </button>
                    </div>
                </div>
            </nav>

          <nav class="bottom-navbar">
                <div class="container">
                    <ul class="nav page-navigation">

                        <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ url('/') }}">
                                <i class="mdi mdi-file-document-box menu-icon"></i>
                                <span class="menu-title">Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-item {{ Request::is('lapor*') || Request::is('tindak-lanjut*') || Request::is('penanganan*') ? 'active' : '' }}">
                            <a href="{{ url('/lapor') }}" class="nav-link">
                                <i class="mdi mdi-email-alert menu-icon"></i>
                                <span class="menu-title">Laporan</span>
                            </a>
                        </li>

                        <li class="nav-item {{ Request::is('riwayat*') ? 'active' : '' }}">
                            <a href="{{ url('/riwayat') }}" class="nav-link">
                                <i class="mdi mdi-ticket-account menu-icon"></i>
                                <span class="menu-title">Riwayat Penanganan</span>
                            </a>
                        </li>

                        <li class="nav-item {{ Request::is('profile*') ? 'active' : '' }}">
                            <a href="{{ url('/profile') }}" class="nav-link">
                                <i class="mdi mdi-account-card-details menu-icon"></i>
                                <span class="menu-title">Profil Sekolah</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </nav>
        </div>

        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>

                <footer class="footer">
                    <div class="footer-wrap">
                        <div class="d-sm-flex justify-content-center justify-content-sm-between">
                            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2025 SiLapor.</span>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <script src="{{ asset('kapella/vendors/base/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('kapella/js/template.js') }}"></script>

    <script>
        function logoutSystem() {
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: "Apakah Anda yakin ingin keluar dari aplikasi?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Keluar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // 1. Hapus Token dari LocalStorage
                    localStorage.removeItem('user_token');
                    localStorage.removeItem('user_name');
                    localStorage.removeItem('school_name');

                    // 2. Tampilkan pesan singkat (opsional) atau langsung redirect
                    window.location.href = '/login';
                }
            })
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            updateNavName();
        });

        function updateNavName() {
            const navNameElement = document.getElementById('nav-school-name');
            const token = localStorage.getItem('user_token');

            const savedName = localStorage.getItem('school_name');
            if (savedName) {
                navNameElement.innerText = "Halo, " + savedName;
            }

            if (token) {
                fetch('https://api-hacktown.rusnandapurnama.com/sekolah', {
                        method: 'GET',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => {
                        if (response.status === 200) return response.json();
                    })
                    .then(result => {
                        if (result && result.data) {
                            const realName = result.data.name;
                            navNameElement.innerText = "Halo, " + realName;
                            localStorage.setItem('school_name', realName);
                        }
                    })
                    .catch(err => console.log('Gagal memuat nama navbar', err));
            }
        }
    </script>

    @stack('scripts')
</body>

</html>