@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Laporan Masuk</h4>
                <p class="card-description">
                    Daftar laporan yang masih <b class="text-warning">Aktif</b> (Menunggu, Diproses, atau Tindak Lanjut).
                </p>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Lokasi</th>
                                <th>Pelapor</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tabel-body">
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <p class="mt-3 text-muted font-weight-medium mb-0">Sedang memuat data...</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Detail Laporan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="$('#modalDetail').modal('hide');">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-7">
                <h3 id="d-judul" class="text-primary mb-3">Laporan Siswa</h3>
                <ul class="list-unstyled">
                    <li><strong>Tanggal:</strong> <span id="d-tanggal">-</span></li>
                    <li><strong>Pelapor:</strong> <span id="d-pelapor">-</span></li>
                    <li><strong>Kategori:</strong> <span id="d-kategori" class="badge badge-info">-</span></li>
                    <li><strong>Lokasi:</strong> <span id="d-lokasi">-</span></li>
                    <li><strong>Ticket ID:</strong> <span id="d-ticket" class="text-muted font-weight-bold">-</span></li>
                </ul>
                <hr>
                <h5 class="mt-4">Detail / Kronologi:</h5>
                <p id="d-kronologi" class="text-muted" style="line-height: 1.8;">...</p>
            </div>
            <div class="col-md-5">
                <div class="text-center mb-3">
                    <img id="d-foto" src="" class="img-fluid rounded shadow-sm" style="display: none; max-height: 250px;">
                    <div id="d-no-foto" class="alert alert-secondary">
                        <i class="mdi mdi-image-off"></i> Tidak ada bukti foto
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="$('#modalDetail').modal('hide');">Tutup</button>
        <a href="#" id="btn-tindak-lanjut" class="btn btn-success">
            <i class="mdi mdi-pencil-box-outline btn-icon-prepend"></i> Tangani / Respon
        </a>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        loadLaporan();
    });

    function loadLaporan() {
        const apiUrl = 'https://api-hacktown.rusnandapurnama.com/laporans?desc=true&items_per_page=100';
        const tbody = document.getElementById('tabel-body');
        const token = localStorage.getItem('user_token');
        const sekolahId = localStorage.getItem('sekolah_id'); 

        // Base URL API untuk gambar
        const apiBaseUrl = 'https://api-hacktown.rusnandapurnama.com'; 

        if (!token) {
            window.location.href = '/login';
            return;
        }

        fetch(apiUrl, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.status === 401) {
                alert("Sesi Anda telah berakhir.");
                localStorage.removeItem('user_token');
                window.location.href = '/login';
                return;
            }
            return response.json();
        })
        .then(result => {
            if (!result) return;
            if (result.status !== 200) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Gagal memuat: ' + (result.message || 'Server Error') + '</td></tr>';
                return;
            }

            let rawData = result.data.data || [];

            // --- FILTERING LOGIC ---
            const filteredList = rawData.filter(item => {
                // 1. Filter Sekolah
                if (sekolahId && item.sekolah_id && item.sekolah_id !== sekolahId) {
                    return false;
                }
                // 2. Filter Status (HANYA YANG AKTIF)
                const statusName = item.status?.name?.toLowerCase() || 'menunggu';
                const isFinished = statusName.includes('selesai') || 
                                   statusName.includes('ditolak') || 
                                   statusName.includes('tutup');
                return !isFinished; 
            });

            tbody.innerHTML = '';

            if (filteredList.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Tidak ada laporan masuk yang perlu ditangani.</td></tr>';
                return;
            }

            // LOOP DATA
            filteredList.forEach((item, index) => {
                const dateObj = new Date(item.create_at);
                const dateStr = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric'});
                
                const statusName = item.status?.name || 'Menunggu';
                
                let badgeClass = 'badge-warning';
                if (statusName.toLowerCase().includes('proses')) badgeClass = 'badge-info';
                else if (statusName.toLowerCase().includes('orang tua')) badgeClass = 'badge-primary';
                
                const detailSafe = item.deskripsi_kejadian ? item.deskripsi_kejadian.replace(/'/g, "\\'") : "-";
                const kategoriSafe = item.kategori_bullying?.name || "Umum";
                const lokasiSafe = item.lokasi || "-";
                const pelaporSafe = item.nama_lengkap || "Anonim";
                const ticketId = item.ticket_id || "-";
                
                // --- LOGIKA PENGAMBILAN FOTO (UPDATED) ---
                let fotoUrl = "";

                // Cek 1: Apakah ada di array attachments? (Prioritas Utama sesuai JSON baru)
                if (item.attachments && item.attachments.length > 0) {
                    // Ambil path dari item pertama di array attachments
                    const path = item.attachments[0].path; 
                    fotoUrl = apiBaseUrl + path;
                } 
                // Cek 2: Fallback ke field bukti_foto (jika JSON lama/format lain)
                else if (item.bukti_foto && item.bukti_foto !== 'null') {
                    fotoUrl = item.bukti_foto.startsWith('http') ? item.bukti_foto : apiBaseUrl + '/storage/' + item.bukti_foto;
                }

                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${dateStr}</td>
                        <td>${kategoriSafe}</td>
                        <td>${lokasiSafe}</td>
                        <td>${pelaporSafe}</td>
                        <td><label class="badge ${badgeClass}">${statusName}</label></td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm btn-icon-text"
                                onclick="bukaModal('${item.id}', '${dateStr}', '${kategoriSafe}', '${lokasiSafe}', '${detailSafe}', '${pelaporSafe}', '${fotoUrl}', '${ticketId}')">
                                <i class="mdi mdi-eye btn-icon-prepend"></i> Detail
                            </button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        })
        .catch(error => {
            console.error('Error:', error);
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Terjadi kesalahan koneksi.</td></tr>';
        });
    }

    function bukaModal(id, tanggal, kategori, lokasi, detail, pelapor, fotoUrl, ticketId) {
        document.getElementById('d-tanggal').innerText = tanggal;
        document.getElementById('d-kategori').innerText = kategori;
        document.getElementById('d-lokasi').innerText = lokasi;
        document.getElementById('d-kronologi').innerText = detail;
        document.getElementById('d-pelapor').innerText = pelapor;
        document.getElementById('d-ticket').innerText = ticketId;
        
        const fotoEl = document.getElementById('d-foto');
        const noFotoEl = document.getElementById('d-no-foto');
        
        // Reset dulu display
        fotoEl.style.display = 'none';
        noFotoEl.style.display = 'block';

        // Validasi URL Foto
        if (fotoUrl && fotoUrl.length > 15) { 
            fotoEl.src = fotoUrl;
            fotoEl.style.display = 'block';
            noFotoEl.style.display = 'none';
        }
        
        const btnTindakLanjut = document.getElementById('btn-tindak-lanjut');
        btnTindakLanjut.href = "{{ url('/tindak-lanjut') }}/" + id;

        $('#modalDetail').modal('show');
    }
</script>
@endpush