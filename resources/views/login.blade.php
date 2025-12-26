<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  
  <title>Login SiLapor - Admin Sekolah</title>
  
  <link rel="stylesheet" href="{{ asset('kapella/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('kapella/vendors/base/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('kapella/css/style.css') }}">
  <link rel="shortcut icon" href="{{ asset('kapella/images/favicon.png') }}" />

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="main-panel">
        <div class="content-wrapper d-flex align-items-center auth px-0">
          <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                
                <div class="brand-logo text-center">
                  <img src="{{ asset('kapella/images/logo.png') }}" alt="logo">
                </div>
                
                <h4>Selamat Datang!</h4>
                <h6 class="font-weight-light">Silakan login menggunakan akun sekolah.</h6>
                
                <form class="pt-3" id="formLogin">
                  
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" id="username" placeholder="Username" required>
                  </div>
                  
                  <div class="form-group">
                    <input type="password" class="form-control form-control-lg" id="password" placeholder="Password" required>
                  </div>
                  
                  <div class="mt-3">
                    <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" id="btnLogin">
                        MASUK
                    </button>
                  </div>
                  
                  <div class="my-2 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input">
                        Ingat Saya
                      </label>
                    </div>
                  </div>
                  
                  <div class="text-center mt-4 font-weight-light">
                    Belum punya akun? <a href="{{ url('/register') }}" class="text-primary">Daftar Sekolah</a>
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
      document.getElementById('formLogin').addEventListener('submit', function(e) {
          e.preventDefault();

          const btnLogin = document.getElementById('btnLogin');
          const usernameVal = document.getElementById('username').value;
          const passwordVal = document.getElementById('password').value;

          // 1. Validasi Sederhana
          if(!usernameVal || !passwordVal) {
              Swal.fire({
                  icon: 'warning',
                  title: 'Perhatian',
                  text: 'Username dan Password wajib diisi!',
              });
              return;
          }

          // Ubah tombol loading
          btnLogin.innerText = "MEMERIKSA...";
          btnLogin.disabled = true;

          // 2. Hit API Login
          fetch('https://api-hacktown.rusnandapurnama.com/sekolah/login', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                  username: usernameVal,
                  password: passwordVal
              })
          })
          .then(response => response.json())
          .then(result => {
              console.log(result); 

              // 3. Cek Status Login
              if (result.status === 200) {
                  // LOGIN BERHASIL
                  
                  // A. Simpan Token JWT
                  const token = result.data.access_token;
                  localStorage.setItem('user_token', token);
                  localStorage.setItem('user_name', usernameVal);

                  // B. Tampilkan SweetAlert Sukses
                  Swal.fire({
                      icon: 'success',
                      title: 'Login Berhasil!',
                      text: 'Mengalihkan ke Dashboard...',
                      timer: 1500, // Otomatis tutup dalam 1.5 detik
                      showConfirmButton: false
                  }).then(() => {
                      // Redirect setelah timer selesai
                      window.location.href = "{{ url('/') }}";
                  });

              } else {
                  // LOGIN GAGAL
                  Swal.fire({
                      icon: 'error',
                      title: 'Login Gagal',
                      text: result.message || 'Username atau Password salah.',
                  });
                  
                  btnLogin.innerText = "MASUK";
                  btnLogin.disabled = false;
              }
          })
          .catch(error => {
              console.error('Error:', error);
              Swal.fire({
                  icon: 'error',
                  title: 'Kesalahan Sistem',
                  text: 'Tidak dapat terhubung ke server.',
              });
              
              btnLogin.innerText = "MASUK";
              btnLogin.disabled = false;
          });
      });
  </script>
</body>
</html>