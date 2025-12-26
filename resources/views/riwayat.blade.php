@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-success">Riwayat Laporan Ditangani</h4>
                <p class="card-description">
                    Daftar laporan sekolah Anda yang statusnya sudah <b class="text-success">Selesai</b> atau <b class="text-danger">Ditolak</b>.
                </p>
                
                <div id="loading-riwayat" class="text-center py-5">
                    <div class="spinner-border text-success" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Memuat data...</p>
                </div>

                <div class="table-responsive" id="table-container" style="display: none;">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Ticket ID</th>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Pelapor</th>
                                <th>Status Akhir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tabel-riwayat-body">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="modal-title text-success">Detail Riwayat</h5>
        <button type="button" class="close" data-dismiss="modal" onclick="$('#modalDetail').modal('hide');">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-7 border-right">
                <h4 id="d-ticket-header" class="mb-3 text-dark">-</h4>
                <div class="form-group">
                    <label class="text-muted mb-0">Judul / Kronologi:</label>
                    <p id="d-kronologi" class="text-dark font-weight-bold">-</p>
                </div>
                <div class="row">
                    <div class="col-6">
                        <small class="text-muted">Pelapor:</small>
                        <p id="d-pelapor" class="font-weight-medium">-</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Kelas:</small>
                        <p id="d-kelas" class="font-weight-medium">-</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Lokasi:</small>
                        <p id="d-lokasi">-</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Tanggal:</small>
                        <p id="d-tanggal">-</p>
                    </div>
                </div>
                <hr>
                <div class="alert alert-secondary mt-3">
                    <strong class="d-block mb-1">Pesan Balasan / Tindak Lanjut:</strong>
                    <span id="d-pesan-balasan" class="text-dark">-</span>
                </div>
            </div>

            <div class="col-md-5 text-center">
                <h6 class="text-muted mb-3">Bukti Foto</h6>
                <div class="border rounded p-2 bg-light">
                    <img id="d-foto" src="" class="img-fluid rounded" style="display: none; max-height: 300px;">
                    <div id="d-no-foto" class="py-5 text-muted">
                        <i class="mdi mdi-image-off" style="font-size: 3rem;"></i><br>Tidak ada foto
                    </div>
                </div>
                <div class="mt-3">
                    <span id="d-kategori-badge" class="badge badge-outline-secondary mb-2">Kategori</span><br>
                    <span id="d-status-badge-modal" class="badge badge-success">Status</span>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="$('#modalDetail').modal('hide');">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        const token = localStorage.getItem('user_token');
        const userSekolahId = localStorage.getItem('sekolah_id'); 

        if (!token) {
            Swal.fire('Akses Ditolak', 'Silakan login terlebih dahulu', 'warning')
                .then(() => window.location.href = '/login');
            return;
        }

        loadRiwayatData(token, userSekolahId);
    });

    function loadRiwayatData(token, sekolahId) {
        const apiUrl = 'https://api-hacktown.rusnandapurnama.com/laporans?items_per_page=100';

        $.ajax({
            url: apiUrl,
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + token },
            success: function(result) {
                $('#loading-riwayat').hide();
                $('#table-container').fadeIn();

                let listLaporan = [];
                if (result.status === 200) {
                    if (result.data && Array.isArray(result.data.data)) {
                        listLaporan = result.data.data;
                    } else if (result.data && Array.isArray(result.data)) {
                        listLaporan = result.data;
                    }
                }

                const tbody = $('#tabel-riwayat-body');
                tbody.empty();

                // --- FILTERING ---
                const filteredData = listLaporan.filter(item => {
                    // 1. Filter Sekolah ID
                    if (sekolahId && item.sekolah_id !== sekolahId) {
                        return false;
                    }

                    // 2. Filter Status (Hanya Selesai / Ditolak / Ditutup)
                    let statusName = "";
                    if (item.status && item.status.name) {
                        statusName = item.status.name;
                    } else if (item.status) {
                        statusName = item.status; 
                    }

                    return statusName.toLowerCase().includes('selesai') || 
                           statusName.toLowerCase().includes('tutup') || 
                           statusName.toLowerCase().includes('tolak');
                });

                if (filteredData.length === 0) {
                    tbody.html('<tr><td colspan="7" class="text-center text-muted">Tidak ada riwayat laporan (Selesai/Ditolak) untuk sekolah ini.</td></tr>');
                    return;
                }

                // Render Tabel
                filteredData.forEach((item, index) => {
                    const statusName = item.status?.name || item.status || 'Status Info -';
                    const kategoriName = item.kategori_bullying?.name || item.kategori?.name || 'Umum';

                    const dateObj = new Date(item.create_at);
                    const dateStr = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });

                    let badgeClass = 'badge-secondary';
                    if (statusName.toLowerCase().includes('selesai')) badgeClass = 'badge-success';
                    else if (statusName.toLowerCase().includes('tolak')) badgeClass = 'badge-danger';

                    // Encode data item untuk dikirim ke modal
                    const dataJson = encodeURIComponent(JSON.stringify(item));

                    const row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td><span class="text-primary font-weight-bold">${item.ticket_id || '-'}</span></td>
                            <td>${dateStr}</td>
                            <td>${kategoriName}</td>
                            <td>${item.nama_lengkap || 'Anonim'}</td>
                            <td><label class="badge ${badgeClass}">${statusName}</label></td>
                            <td>
                                <button class="btn btn-info btn-sm btn-icon-text" onclick="openDetail('${dataJson}')">
                                    <i class="mdi mdi-eye btn-icon-prepend"></i> Detail
                                </button>
                            </td>
                        </tr>
                    `;
                    tbody.append(row);
                });
            },
            error: function(err) {
                console.error(err);
                $('#loading-riwayat').html('<p class="text-danger">Gagal memuat data. Cek koneksi.</p>');
            }
        });
    }

    // --- FUNGSI BUKA MODAL (UPDATE LOGIKA FOTO DI SINI) ---
    window.openDetail = function(encodedItem) {
        const item = JSON.parse(decodeURIComponent(encodedItem));

        // Mapping Data Teks
        const statusName = item.status?.name || item.status || '-';
        const kategoriName = item.kategori_bullying?.name || item.kategori?.name || '-';

        $('#d-ticket-header').text(item.ticket_id || 'Tanpa Ticket ID');
        $('#d-kronologi').text(item.deskripsi_kejadian || '-');
        $('#d-pelapor').text(item.nama_lengkap || 'Anonim');
        $('#d-kelas').text(item.kelas || '-');
        $('#d-lokasi').text(item.lokasi || '-');
        $('#d-pesan-balasan').text(item.pesan_balasan || 'Tidak ada catatan tindak lanjut.');
        
        const d = new Date(item.create_at);
        $('#d-tanggal').text(d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute:'2-digit' }));

        $('#d-kategori-badge').text(kategoriName);
        $('#d-status-badge-modal').text(statusName);
        
        if(statusName.toLowerCase().includes('tolak')) {
            $('#d-status-badge-modal').removeClass('badge-success').addClass('badge-danger');
        } else {
            $('#d-status-badge-modal').removeClass('badge-danger').addClass('badge-success');
        }

        // === UPDATE LOGIKA FOTO (Attachments Array) ===
        const apiBaseUrl = 'https://api-hacktown.rusnandapurnama.com';
        let fotoUrl = "";

        // Cek 1: Apakah ada di array attachments? (Prioritas Utama)
        if (item.attachments && Array.isArray(item.attachments) && item.attachments.length > 0) {
            const path = item.attachments[0].path; 
            fotoUrl = apiBaseUrl + path;
        } 
        // Cek 2: Fallback ke bukti_foto (Legacy)
        else if (item.bukti_foto && item.bukti_foto !== 'null') {
            fotoUrl = item.bukti_foto.startsWith('http') ? 
                      item.bukti_foto : 
                      apiBaseUrl + '/storage/' + item.bukti_foto;
        }

        // Tampilkan atau Sembunyikan Elemen Gambar
        if (fotoUrl) {
            $('#d-foto').attr('src', fotoUrl).show();
            $('#d-no-foto').hide();
        } else {
            $('#d-foto').hide();
            $('#d-no-foto').show();
        }
        // ===============================================

        $('#modalDetail').modal('show');
    }
</script>
@endpush