@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-sm-12 mb-4 mb-xl-0">
        <h3 class="text-dark font-weight-bold mb-2">Halo, Selamat Datang!</h3>
        <h6 class="font-weight-normal mb-2 text-muted">Dashboard Monitoring BK</h6>
    </div>
</div>

<div class="row mt-3">
    <div class="col-sm-6 col-lg-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-1">Total Laporan</h4>
                        <h2 class="text-success font-weight-bold mb-0" id="val-siswa-melapor">...</h2>
                    </div>
                    <i class="mdi mdi-account-multiple-outline mdi-36px text-success"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-1">Sedang Diproses</h4>
                        <h2 class="text-warning font-weight-bold mb-0" id="val-diproses">...</h2>
                    </div>
                    <i class="mdi mdi-clock-outline mdi-36px text-warning"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-1">Kasus Selesai</h4>
                        <h2 class="text-info font-weight-bold mb-0" id="val-selesai">...</h2>
                    </div>
                    <i class="mdi mdi-check-decagram mdi-36px text-info"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-1">Laporan Ditolak</h4>
                        <h2 class="text-danger font-weight-bold mb-0" id="val-ditolak">...</h2>
                    </div>
                    <i class="mdi mdi-alert-circle-outline mdi-36px text-danger"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Statistik Laporan per Kelas</h4>
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="chartKelas"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Kategori Laporan</h4>
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="chartKategori"></canvas>
                </div>
                <div class="mt-4">
                    <p class="text-muted text-small text-center">
                        *Visualisasi distribusi kategori laporan berdasarkan data aktual.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card border-danger">
            <div class="card-body">
                <h4 class="card-title text-danger"><i class="mdi mdi-alert-octagram mr-2"></i>Sinyal Darurat (Panic Button)</h4>
                <p class="card-description">Daftar lokasi siswa yang menekan tombol darurat.</p>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Akurasi (Meter)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tabel-alert-body">
                            <tr>
                                <td colspan="5" class="text-center py-4">Memuat data alert...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalMap" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lokasi Darurat Siswa</h5>
                <button type="button" class="close" data-dismiss="modal" onclick="$('#modalMap').modal('hide');">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div id="map" style="height: 400px; width: 100%;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#modalMap').modal('hide');">Tutup</button>
                <a href="#" id="btn-gmaps" target="_blank" class="btn btn-primary">Buka di Google Maps</a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

@push('scripts')
    <script src="{{ asset('kapella/vendors/chart.js/Chart.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const token = localStorage.getItem('user_token');
            
            if (!token) {
                Swal.fire({
                    title: 'Akses Ditolak',
                    text: 'Silakan login terlebih dahulu',
                    icon: 'warning'
                }).then(() => window.location.href = '/login');
                return;
            }

            // Load Data
            loadStatistik(token);
            loadAlertData(token);
        });

        // ==========================================
        // FUNGSI LOAD ALERT
        // ==========================================
        function loadAlertData(token) {
            const alertUrl = 'https://api-hacktown.rusnandapurnama.com/alert';

            fetch(alertUrl, {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(result => {
                const tbody = document.getElementById('tabel-alert-body');
                tbody.innerHTML = '';

                let dataAlert = [];

                // Cek apakah result.data ada dan berupa Array
                if (result.data && Array.isArray(result.data)) {
                    dataAlert = result.data;
                } 
                // Fallback: Jika ternyata cuma 1 objek
                else if (result.data && typeof result.data === 'object') {
                    dataAlert = [result.data];
                }

                if (dataAlert.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Belum ada sinyal darurat aktif.</td></tr>';
                    return;
                }

                dataAlert.forEach((item, index) => {
                    const rawLat = item.lat;
                    const rawLong = item.long;
                    const acc = item.accuracy || '-';

                    // Validasi Angka Koordinat
                    const latVal = parseFloat(rawLat);
                    const longVal = parseFloat(rawLong);
                    const isValid = !isNaN(latVal) && !isNaN(longVal);

                    let btnAction = '';
                    let btnClass = 'btn-danger';
                    let btnText = 'Lihat Lokasi';

                    if (isValid) {
                        btnAction = `onclick="showMap(${latVal}, ${longVal})"`;
                    } else {
                        btnClass = 'btn-secondary';
                        btnAction = 'disabled';
                        btnText = 'Lokasi Invalid';
                    }

                    const row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${rawLat}</td>
                            <td>${rawLong}</td>
                            <td>${acc} Meter</td>
                            <td>
                                <button class="btn ${btnClass} btn-sm" ${btnAction}>
                                    <i class="mdi mdi-map-marker-radius mr-1"></i> ${btnText}
                                </button>
                            </td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });
            })
            .catch(err => {
                console.error("Gagal load alert:", err);
                document.getElementById('tabel-alert-body').innerHTML = 
                    '<tr><td colspan="5" class="text-center text-danger">Gagal memuat data alert.</td></tr>';
            });
        }

        // Variabel global map
        let map = null;
        let marker = null;

        // FUNGSI TAMPILKAN MAP (DIPERBAIKI)
        window.showMap = function(lat, long) {
            $('#modalMap').modal('show');

            const gmapsLink = `http:maps.google.com/?q=${lat},${long}`;
            document.getElementById('btn-gmaps').href = gmapsLink;

            // Tunggu modal muncul baru render peta
            setTimeout(() => {
                // Hapus map lama agar tidak error
                if (map) {
                    map.off();
                    map.remove();
                }

                // Inisialisasi Map
                map = L.map('map').setView([lat, long], 16);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                marker = L.marker([lat, long]).addTo(map)
                    .bindPopup(`<div style="text-align:center;"><b>LOKASI SISWA</b><br>Lat: ${lat}<br>Long: ${long}</div>`)
                    .openPopup();
                
                // === FIX UTAMA: PAKSA RENDER ULANG ===
                // Ini memperbaiki map yang abu-abu atau berantakan
                map.invalidateSize(); 

            }, 500); 
        };

        // ==========================================
        // FUNGSI LOAD STATISTIK (TIDAK BERUBAH)
        // ==========================================
        function loadStatistik(token) {
            const apiUrl = 'https://api-hacktown.rusnandapurnama.com/laporans?items_per_page=100';

            fetch(apiUrl, {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (response.status === 401) return;
                return response.json();
            })
            .then(result => {
                if (!result) return;

                if (result.status === 200 && result.data && result.data.data) {
                    const listLaporan = result.data.data;

                    const stats = calculateStats(listLaporan);
                    const kelasStats = calculateKelasStats(listLaporan);
                    const kategoriStats = calculateKategoriStats(listLaporan);

                    document.getElementById('val-siswa-melapor').innerText = stats.total;
                    document.getElementById('val-diproses').innerText = stats.diproses;
                    document.getElementById('val-selesai').innerText = stats.selesai;
                    document.getElementById('val-ditolak').innerText = stats.ditolak;

                    renderChartKelas(kelasStats);
                    renderChartKategori(kategoriStats);
                } else {
                    setDefaultStats();
                }
            })
            .catch(error => {
                console.error('API Error:', error);
                setDefaultStats();
            });
        }

        function calculateStats(laporan) {
            let total = laporan.length;
            let diproses = 0, selesai = 0, ditolak = 0;

            laporan.forEach(item => {
                const statusName = (item.status?.name || '').toLowerCase();
                if (statusName.includes('proses') || statusName.includes('investigasi')) diproses++;
                else if (statusName.includes('selesai') || statusName.includes('ditutup')) selesai++;
                else if (statusName.includes('ditolak')) ditolak++;
            });
            return { total, diproses, selesai, ditolak };
        }

        function calculateKelasStats(laporan) {
            const kelasCount = {};
            laporan.forEach(item => {
                const kelas = item.kelas || 'Tidak Diketahui';
                kelasCount[kelas] = (kelasCount[kelas] || 0) + 1;
            });
            return kelasCount;
        }

        function calculateKategoriStats(laporan) {
            const kategoriCount = {};
            laporan.forEach(item => {
                let kategoriName = item.kategori_bullying?.name || item.kategori?.name || 'Umum';
                kategoriCount[kategoriName] = (kategoriCount[kategoriName] || 0) + 1;
            });
            return kategoriCount;
        }

        function setDefaultStats() {
            document.getElementById('val-siswa-melapor').innerText = '0';
            document.getElementById('val-diproses').innerText = '0';
            document.getElementById('val-selesai').innerText = '0';
            document.getElementById('val-ditolak').innerText = '0';
        }

        function renderChartKelas(kelasStats) {
            const kelasLabels = Object.keys(kelasStats).map(k => 'Kelas ' + k).sort();
            const kelasValues = kelasLabels.map(label => kelasStats[label.replace('Kelas ', '')] || 0);

            if (kelasLabels.length > 0) {
                new Chart(document.getElementById('chartKelas'), {
                    type: 'bar',
                    data: {
                        labels: kelasLabels,
                        datasets: [{
                            label: 'Jumlah Laporan',
                            data: kelasValues,
                            backgroundColor: ['#4B49AC', '#98BDFF', '#7DA0FA', '#FFC100', '#57B657', '#FF4747'],
                            borderColor: ['#4B49AC', '#98BDFF', '#7DA0FA', '#FFC100', '#57B657', '#FF4747'],
                            borderWidth: 1,
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: { yAxes: [{ ticks: { beginAtZero: true, stepSize: 1 } }] },
                        legend: { display: false }
                    }
                });
            }
        }

        function renderChartKategori(kategoriStats) {
            const katLabels = Object.keys(kategoriStats);
            const katValues = Object.values(kategoriStats);

            if (katLabels.length > 0) {
                new Chart(document.getElementById('chartKategori'), {
                    type: 'doughnut',
                    data: {
                        labels: katLabels,
                        datasets: [{
                            data: katValues,
                            backgroundColor: ['#FF4747', '#FFC100', '#248AFD', '#57B657', '#9C27B0', '#FF9800', '#00BCD4'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: { position: 'bottom' }
                    }
                });
            } else {
                document.getElementById('chartKategori').parentElement.innerHTML = '<p class="text-center text-muted py-5">Belum ada data kategori</p>';
            }
        }
    </script>
@endpush