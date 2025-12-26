@extends('layouts.master')

@section('content')
<div class="row">
    
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card text-center">
            <div class="card-body">
                <div class="mb-4">
                    <img src="{{ asset('kapella/images/faces/face28.png') }}" alt="profile" class="img-lg rounded-circle" style="width: 120px; height: 120px; border: 3px solid #e3e3e3;" />
                </div>
                <h4 class="font-weight-bold mb-1" id="text-name-card">Memuat...</h4>
                <p class="text-muted mb-4">Akun Sekolah (Admin)</p>
                
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary btn-sm mb-2"><i class="mdi mdi-camera me-1"></i> Ubah Foto</button>
                    <button class="btn btn-danger btn-sm" onclick="logoutSystem()"><i class="mdi mdi-logout me-1"></i> Logout</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Edit Profil Sekolah</h4>
                    <i class="mdi mdi-settings text-muted"></i>
                </div>
                
                <form class="forms-sample" id="formProfile">
                    
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama Sekolah</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" placeholder="Memuat data...">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">NPSN</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="npsn" readonly style="background-color: #f3f3f3;">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="email">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">No. Telepon</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="phone_number">
                        </div>
                    </div>

                    <hr class="my-4">
                    <p class="card-description text-primary font-weight-bold">Pengaturan Login</p>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Username</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="username" readonly style="background-color: #f3f3f3;">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Password Baru</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="password" placeholder="Isi hanya jika ingin mengubah password">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const apiEndpoint = 'https://api-hacktown.rusnandapurnama.com/sekolah';

    document.addEventListener("DOMContentLoaded", function() {
        // 1. Ambil data saat halaman dimuat
        getProfileData();

        // 2. Event Listener untuk Tombol Simpan
        const form = document.getElementById('formProfile');
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah reload halaman
            updateProfile();
        });
    });

    // --- FUNGSI AMBIL DATA (GET) ---
    function getProfileData() {
        const token = localStorage.getItem('user_token');

        if (!token) {
            window.location.href = "{{ url('/login') }}";
            return;
        }

        fetch(apiEndpoint, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.status === 401) {
                handleUnauthorized();
                return;
            }
            return response.json();
        })
        .then(result => {
            if (result && result.data) {
                const d = result.data;

                // Isi Form
                document.getElementById('name').value = d.name || '';
                document.getElementById('npsn').value = d.npsn || ''; // Readonly
                document.getElementById('email').value = d.email || '';
                document.getElementById('phone_number').value = d.phone_number || '';
                document.getElementById('username').value = d.username || ''; // Readonly
                
                // Update UI Kartu
                document.getElementById('text-name-card').innerText = d.name || 'Admin Sekolah';
            }
        })
        .catch(error => console.error('Error fetching profile:', error));
    }

    // --- FUNGSI UPDATE DATA (PUT) ---
    function updateProfile() {
        const token = localStorage.getItem('user_token');
        
        // 1. Ambil Value dari Input
        const nameVal = document.getElementById('name').value;
        const emailVal = document.getElementById('email').value;
        const phoneVal = document.getElementById('phone_number').value;
        const passwordVal = document.getElementById('password').value;

        // 2. Validasi Sederhana
        if (!nameVal || !emailVal || !phoneVal) {
            Swal.fire('Peringatan', 'Nama, Email, dan No. Telepon wajib diisi.', 'warning');
            return;
        }

        // 3. Susun Payload Sesuai Permintaan Struktur JSON
        let payload = {
            name: nameVal,
            email: emailVal,
            phone_number: phoneVal
        };

        // Jika user mengisi password, masukkan ke payload (Opsional tergantung API)
        if (passwordVal && passwordVal.trim() !== "") {
            payload.password = passwordVal;
        }

        // 4. Tampilkan Loading
        Swal.fire({
            title: 'Menyimpan...',
            text: 'Sedang memperbarui profil sekolah.',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        // 5. Kirim ke API
        fetch(apiEndpoint, {
            method: 'PUT', // Method Update biasanya PUT
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(response => {
            if (response.status === 401) {
                handleUnauthorized();
                throw new Error("Unauthorized");
            }
            return response.json();
        })
        .then(result => {
            // Cek indikator sukses dari API (sesuaikan dengan respon API kamu, misal status: 200 atau error: false)
            if (result.status === 200 || result.error === false || (result.message && result.message.toLowerCase().includes('berhasil'))) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Profil sekolah berhasil diperbarui.',
                    confirmButtonColor: '#3085d6'
                }).then(() => {
                    // Refresh data tampilan dan kosongkan field password
                    getProfileData();
                    document.getElementById('password').value = '';
                });
            } else {
                throw new Error(result.message || 'Gagal memperbarui data.');
            }
        })
        .catch(error => {
            if (error.message !== "Unauthorized") {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: error.message || 'Terjadi kesalahan sistem.'
                });
            }
        });
    }

    // Fungsi Helper Logout jika Token Expired
    function handleUnauthorized() {
        alert("Sesi Anda telah berakhir. Silakan login kembali.");
        localStorage.removeItem('user_token');
        window.location.href = "{{ url('/login') }}";
    }

    // Fungsi Logout Manual (Tombol Merah)
    function logoutSystem() {
        Swal.fire({
            title: 'Konfirmasi Logout',
            text: "Apakah Anda yakin ingin keluar?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Keluar'
        }).then((result) => {
            if (result.isConfirmed) {
                localStorage.removeItem('user_token');
                window.location.href = "{{ url('/login') }}";
            }
        })
    }
</script>
@endpush