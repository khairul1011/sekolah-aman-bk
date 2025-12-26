<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

  <title>Register SiLapor - Sekolah</title>
  
  <link rel="stylesheet" href="{{ asset('kapella/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('kapella/vendors/base/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('kapella/css/style.css') }}">
  <link rel="shortcut icon" href="{{ asset('kapella/images/favicon.png') }}" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="main-panel">
        <div class="content-wrapper d-flex align-items-center auth px-0">
          <div class="row w-100 mx-0">
            <div class="col-lg-6 mx-auto">
              <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                
                <div class="brand-logo text-center">
                  <img src="{{ asset('kapella/images/logo.png') }}" alt="logo">
                </div>
                
                <h4>Daftarkan Sekolah Baru</h4>
                <h6 class="font-weight-light">Lengkapi data sekolah untuk mendapatkan akses Admin BK.</h6>
                
                <form class="pt-3" id="formRegister">
                  
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" id="name" placeholder="Nama Sekolah (Contoh: SMAN 1 Pekanbaru)" required>
                  </div>

                  <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                            <input type="number" class="form-control form-control-lg" id="npsn" placeholder="NPSN (Cth: 12345678)" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-lg" id="phone_number" placeholder="No. HP (+628...)" required>
                        </div>
                      </div>
                  </div>

                  <div class="form-group">
                    <input type="email" class="form-control form-control-lg" id="email" placeholder="Email Sekolah" required>
                  </div>

                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" id="username" placeholder="Username Login" required>
                  </div>
                  
                  <div class="form-group">
                    <input type="password" class="form-control form-control-lg" id="password" placeholder="Password" required>
                  </div>

                  <div class="mb-4">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input" required>
                        Saya menyatakan data sekolah ini benar.
                      </label>
                    </div>
                  </div>
                  
                  <div class="mt-3">
                    <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" id="btnSubmit">
                        DAFTAR SEKARANG
                    </button>
                  </div>
                  
                  <div class="text-center mt-4 font-weight-light">
                    Sudah punya akun? <a href="{{ url('/login') }}" class="text-primary">Login di sini</a>
                  </div>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <script src="{{ asset('kapella/vendors/base/vendor.bundle.base.js') }}"></script>
  <script src="{{ asset('kapella/js/template.js') }}"></script>
  
  <script>
      document.getElementById('formRegister').addEventListener('submit', function(e) {
          e.preventDefault(); // Mencegah reload halaman

          // 1. Ambil Data dari Input
          const btnSubmit = document.getElementById('btnSubmit');
          const data = {
              username: document.getElementById('username').value,
              password: document.getElementById('password').value,
              name: document.getElementById('name').value,
              npsn: document.getElementById('npsn').value,
              email: document.getElementById('email').value,
              phone_number: document.getElementById('phone_number').value
          };

          // Ubah tombol jadi loading
          btnSubmit.innerText = "MEMPROSES...";
          btnSubmit.disabled = true;

          // 2. Kirim ke API
          fetch('https://api-hacktown.rusnandapurnama.com/sekolah', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json'
              },
              body: JSON.stringify(data)
          })
          .then(response => response.json())
          .then(result => {
              console.log(result); // Cek response di console

              // 3. Cek Status Response
              // Sesuaikan kondisi ini dengan balasan API jika sukses
              // Biasanya API mengembalikan status 200 atau 201
              if (result.status === 200 || result.message === "success" || result.id) { 
                  alert("Registrasi Berhasil! Silakan Login.");
                  window.location.href = "{{ url('/login') }}";
              } else {
                  // Jika gagal (misal username sudah ada)
                  alert("Registrasi Gagal: " + (result.message || "Periksa kembali data Anda."));
                  btnSubmit.innerText = "DAFTAR SEKARANG";
                  btnSubmit.disabled = false;
              }
          })
          .catch(error => {
              console.error('Error:', error);
              alert("Terjadi kesalahan koneksi ke server.");
              btnSubmit.innerText = "DAFTAR SEKARANG";
              btnSubmit.disabled = false;
          });
      });
  </script>

</body>
</html>