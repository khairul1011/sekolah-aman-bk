@extends('layouts.master')

@section('content')
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            {{-- SPAN UNTUK TICKET ID --}}
            <h3 class="text-dark font-weight-bold">Penanganan Laporan <span id="ticket_id"></span></h3>
            <a href="{{ url('/lapor') }}" class="btn btn-light border"><i class="mdi mdi-arrow-left"></i> Kembali</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-primary">Data Laporan Siswa </h4>

                    <div id="loading-indicator" class="text-center py-5">
                        <svg width="100" height="100" fill="hsl(228, 97%, 42%)" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <circle cx="12" cy="3" r="1">
                                    <animate id="spinner_7Z73" begin="0;spinner_tKsu.end-0.5s" attributeName="r"
                                        calcMode="spline" dur="0.6s" values="1;2;1"
                                        keySplines=".27,.42,.37,.99;.53,0,.61,.73" />
                                </circle>
                                <circle cx="16.50" cy="4.21" r="1">
                                    <animate id="spinner_Wd87" begin="spinner_7Z73.begin+0.1s" attributeName="r"
                                        calcMode="spline" dur="0.6s" values="1;2;1"
                                        keySplines=".27,.42,.37,.99;.53,0,.61,.73" />
                                </circle>
                                <circle cx="7.50" cy="4.21" r="1">
                                    <animate id="spinner_tKsu" begin="spinner_9Qlc.begin+0.1s" attributeName="r"
                                        calcMode="spline" dur="0.6s" values="1;2;1"
                                        keySplines=".27,.42,.37,.99;.53,0,.61,.73" />
                                </circle>
                                <circle cx="19.79" cy="7.50" r="1">
                                    <animate id="spinner_lMMO" begin="spinner_Wd87.begin+0.1s" attributeName="r"
                                        calcMode="spline" dur="0.6s" values="1;2;1"
                                        keySplines=".27,.42,.37,.99;.53,0,.61,.73" />
                                </circle>
                                <circle cx="4.21" cy="7.50" r="1">
                                    <animate id="spinner_9Qlc" begin="spinner_Khxv.begin+0.1s" attributeName="r"
                                        calcMode="spline" dur="0.6s" values="1;2;1"
                                        keySplines=".27,.42,.37,.99;.53,0,.61,.73" />
                                </circle>
                                <circle cx="21.00" cy="12.00" r="1">
                                    <animate id="spinner_5L9t" begin="spinner_lMMO.begin+0.1s" attributeName="r"
                                        calcMode="spline" dur="0.6s" values="1;2;1"
                                        keySplines=".27,.42,.37,.99;.53,0,.61,.73" />
                                </circle>
                                <circle cx="3.00" cy="12.00" r="1">
                                    <animate id="spinner_Khxv" begin="spinner_ld6P.begin+0.1s" attributeName="r"
                                        calcMode="spline" dur="0.6s" values="1;2;1"
                                        keySplines=".27,.42,.37,.99;.53,0,.61,.73" />
                                </circle>
                                <circle cx="19.79" cy="16.50" r="1">
                                    <animate id="spinner_BfTD" begin="spinner_5L9t.begin+0.1s" attributeName="r"
                                        calcMode="spline" dur="0.6s" values="1;2;1"
                                        keySplines=".27,.42,.37,.99;.53,0,.61,.73" />
                                </circle>
                                <circle cx="4.21" cy="16.50" r="1">
                                    <animate id="spinner_ld6P" begin="spinner_XyBs.begin+0.1s" attributeName="r"
                                        calcMode="spline" dur="0.6s" values="1;2;1"
                                        keySplines=".27,.42,.37,.99;.53,0,.61,.73" />
                                </circle>
                                <circle cx="16.50" cy="19.79" r="1">
                                    <animate id="spinner_7gAK" begin="spinner_BfTD.begin+0.1s" attributeName="r"
                                        calcMode="spline" dur="0.6s" values="1;2;1"
                                        keySplines=".27,.42,.37,.99;.53,0,.61,.73" />
                                </circle>
                                <circle cx="7.50" cy="19.79" r="1">
                                    <animate id="spinner_XyBs" begin="spinner_HiSl.begin+0.1s" attributeName="r"
                                        calcMode="spline" dur="0.6s" values="1;2;1"
                                        keySplines=".27,.42,.37,.99;.53,0,.61,.73" />
                                </circle>
                                <circle cx="12" cy="21" r="1">
                                    <animate id="spinner_HiSl" begin="spinner_7gAK.begin+0.1s" attributeName="r"
                                        calcMode="spline" dur="0.6s" values="1;2;1"
                                        keySplines=".27,.42,.37,.99;.53,0,.61,.73" />
                                </circle>
                                <animateTransform attributeName="transform" type="rotate" dur="6s"
                                    values="360 12 12;0 12 12" repeatCount="indefinite" />
                            </g>
                        </svg>
                        <p class="mt-3 text-muted font-weight-medium">Sedang mengambil data...</p>
                    </div>

                    <div id="detail-content" style="display: none;">
                        <div class="bg-light p-3 rounded mb-3">
                            <p class="mb-2"><strong>Pelapor:</strong> <span id="d-pelapor" class="text-dark">-</span>
                            </p>
                            <p class="mb-2"><strong>Kategori:</strong> <span id="d-kategori"
                                    class="badge badge-secondary">...</span></p>
                            <p class="mb-2"><strong>Lokasi:</strong> <span id="d-lokasi" class="text-dark">-</span>
                            </p>
                            <p class="mb-2"><strong>Waktu:</strong> <span id="d-waktu" class="text-dark">-</span></p>
                            <p class="mb-0"><strong>Status Saat Ini:</strong> <span id="d-status-badge"
                                    class="badge">...</span></p>
                        </div>

                        <div class="form-group">
                            <label><strong>Kronologi Kejadian:</strong></label>
                            <div class="p-3 border rounded bg-white">
                                <p class="text-muted text-justify mb-0" style="line-height: 1.8;" id="d-kronologi">
                                    -
                                </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><strong>Bukti Foto:</strong></label>
                            <div id="area-foto" class="text-center p-2 bg-light rounded border">
                                <img id="d-foto" src="" class="img-fluid rounded shadow-sm" alt="Bukti"
                                    style="display: none; max-height: 300px; width: 100%; object-fit: contain;">
                                <p id="d-no-foto" class="text-muted font-italic mb-0 py-3" style="display: none;">
                                    <i class="mdi mdi-image-off-outline"></i> Tidak ada bukti foto
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7 grid-margin stretch-card">
            <div class="card border-primary">
                <div class="card-body">
                    <h4 class="card-title text-danger">Form Tindak Lanjut Guru BK</h4>
                    <p class="card-description">Respon yang Anda tulis akan terbaca oleh siswa saat cek status.</p>

                    <form id="form-tindak-lanjut">
                        <div class="form-group">
                            <label>Update Status Laporan</label>
                            <select class="form-control form-control-lg text-dark" id="input-status"
                                style="height: 50px;">
                                <option value="">Memuat status...</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Pesan Balasan untuk Pelapor (Siswa)</label>
                            <textarea class="form-control" id="input-pesan" rows="6"
                                placeholder="Contoh: Terima kasih, kami sudah menerima laporanmu. Silakan datang ke ruang BK pada jam istirahat..."></textarea>
                        </div>

                        <div class="form-group">
                            <label class="text-muted"><small>Catatan Internal (Hanya dilihat Guru lain)</small></label>
                            <textarea class="form-control bg-light" id="input-catatan-internal" rows="2"
                                placeholder="Catatan rahasia untuk arsip sekolah..."></textarea>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2 btn-lg w-100">
                                <i class="mdi mdi-send me-1"></i> Kirim Respon & Update Status
                            </button>
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
        $(document).ready(function() {
            const pathSegments = window.location.pathname.split('/').filter(segment => segment !== '');
            let reportId = pathSegments[pathSegments.length - 1];

            // Validasi dan cleanup reportId
            if (!reportId || reportId === 'tindak-lanjut') {
                const urlParams = new URLSearchParams(window.location.search);
                reportId = urlParams.get('id') || reportId;
            }

            // Trim dan validasi
            reportId = reportId ? reportId.trim() : '';

            const token = localStorage.getItem('user_token');

            if (!token) {
                Swal.fire({
                    title: 'Akses Ditolak',
                    text: 'Silakan login terlebih dahulu',
                    icon: 'warning'
                }).then(() => window.location.href = '/login');
                return;
            }

            if (!reportId) {
                Swal.fire({
                    title: 'Error',
                    text: 'ID laporan tidak ditemukan. Silakan kembali ke halaman laporan.',
                    icon: 'error'
                }).then(() => window.location.href = '/lapor');
                return;
            }

            // Simpan reportId ke variabel global
            window.currentReportId = reportId;

            // Load data
            loadStatusOptions(token);
            getDetailData(reportId, token);

            // --- FUNGSI LOAD STATUS ---
            function loadStatusOptions(token) {
                const possibleEndpoints = [
                    'https://api-hacktown.rusnandapurnama.com/status',
                    'https://api-hacktown.rusnandapurnama.com/statuses',
                    'https://api-hacktown.rusnandapurnama.com/status/list'
                ];
                let currentEndpointIndex = 0;

                function tryLoadStatus(endpointIndex) {
                    if (endpointIndex >= possibleEndpoints.length) {
                        loadDefaultStatuses();
                        return;
                    }
                    const statusApiUrl = possibleEndpoints[endpointIndex];
                    $.ajax({
                        url: statusApiUrl,
                        type: 'GET',
                        dataType: 'json',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Content-Type': 'application/json'
                        },
                        success: function(result) {
                            const statusSelect = $('#input-status');
                            statusSelect.empty();
                            let statusList = [];

                            if (result.status === 200 && result.data) {
                                if (Array.isArray(result.data)) statusList = result.data;
                                else if (result.data.data && Array.isArray(result.data.data))
                                    statusList = result.data.data;
                                else if (result.data.id && result.data.name) statusList = [result.data];
                            } else if (Array.isArray(result)) {
                                statusList = result;
                            }

                            if (statusList.length > 0) {
                                statusList.forEach(function(status) {
                                    if (status.id && status.name) {
                                        statusSelect.append($('<option></option>').attr('value',
                                            status.id).text(status.name));
                                    }
                                });
                                if (statusSelect.children().length === 0) tryLoadStatus(endpointIndex + 1);
                            } else {
                                tryLoadStatus(endpointIndex + 1);
                            }
                        },
                        error: function() {
                            tryLoadStatus(endpointIndex + 1);
                        }
                    });
                }

                function loadDefaultStatuses() {
                    const statusSelect = $('#input-status');
                    statusSelect.empty();
                    const defaultStatuses = [{
                            id: '1',
                            name: 'Menunggu'
                        },
                        {
                            id: '2',
                            name: 'Sedang Diproses (Investigasi)'
                        },
                        {
                            id: '3',
                            name: 'Pemanggilan Orang Tua/Siswa (Minta Keterangan Lebih Lanjut)'
                        },
                        {
                            id: '4',
                            name: 'Selesai / Ditutup'
                        },
                        {
                            id: '5',
                            name: 'Laporan Ditolak (Hoax)'
                        }
                    ];
                    defaultStatuses.forEach(st => {
                        statusSelect.append($('<option></option>').attr('value', st.id).text(st.name));
                    });
                }
                tryLoadStatus(0);
            }

            // --- FUNGSI GET DETAIL DATA ---
            function getDetailData(id, token) {
                const apiUrl = 'https://api-hacktown.rusnandapurnama.com/laporans?id=' + id;

                $('#loading-indicator').show();
                $('#detail-content').hide();

                $.ajax({
                    url: apiUrl,
                    type: 'GET',
                    dataType: 'json',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Content-Type': 'application/json'
                    },
                    success: function(result) {
                        console.log("Respon API:", result);

                        let dataLaporan = null;

                        if (result.status === 200 && result.data) {
                            if (typeof result.data === 'object' && !Array.isArray(result.data) && result
                                .data.id) {
                                dataLaporan = result.data;
                            } else if (Array.isArray(result.data)) {
                                dataLaporan = result.data.find(item => item.id == id) || result.data[0];
                            } else if (result.data.data && Array.isArray(result.data.data)) {
                                dataLaporan = result.data.data.find(item => item.id == id) || result.data
                                    .data[0];
                            }
                        }

                        if (dataLaporan) {
                            window.currentReportId = dataLaporan.id;
                            renderHalaman(dataLaporan);
                        } else {
                            $('#loading-indicator').html('<p class="text-danger">Data laporan tidak ditemukan.</p>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error AJAX:", error);
                        if (xhr.status === 401) {
                            alert("Sesi habis, login ulang.");
                            window.location.href = '/login';
                        } else {
                            $('#loading-indicator').html(
                                '<p class="text-danger">Gagal koneksi server / Data tidak ditemukan.</p>');
                        }
                    }
                });
            }

            // --- FUNGSI RENDER HALAMAN (UPDATE FOTO DISINI) ---
            function renderHalaman(data) {
                $('#loading-indicator').hide();
                $('#detail-content').fadeIn();

                // Ticket ID
                if (data.ticket_id) {
                    $('#ticket_id').text(' #' + data.ticket_id).addClass('text-primary');
                } else {
                    $('#ticket_id').text('');
                }

                // Mapping Data Teks
                const nama = data.nama_lengkap || data.nama_pelapor || "Anonim";
                $('#d-pelapor').text(nama);
                $('#d-lokasi').text(data.lokasi || "-");
                $('#d-kronologi').text(data.deskripsi_kejadian || data.detail_laporan || "-");

                // Kategori
                let namaKategori = "Umum";
                if (data.kategori_bullying && data.kategori_bullying.name) {
                    namaKategori = data.kategori_bullying.name;
                } else if (data.kategori && data.kategori.name) {
                    namaKategori = data.kategori.name;
                }
                $('#d-kategori').text(namaKategori);

                // Warna Badge Kategori
                $('#d-kategori').removeClass('badge-danger badge-warning badge-info badge-secondary');
                if (namaKategori.toLowerCase().includes('fisik')) $('#d-kategori').addClass('badge-danger');
                else if (namaKategori.toLowerCase().includes('verbal')) $('#d-kategori').addClass('badge-warning');
                else $('#d-kategori').addClass('badge-info');

                // Tanggal
                const tgl = data.create_at || data.tanggal;
                if (tgl) {
                    const dateObj = new Date(tgl);
                    $('#d-waktu').text(dateObj.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    }));
                }

                // === LOGIKA FOTO BARU (ATTACHMENTS) ===
                const apiBaseUrl = 'https://api-hacktown.rusnandapurnama.com';
                let fotoUrl = "";

                // Cek 1: Attachments Array (Sesuai JSON Terbaru)
                if (data.attachments && Array.isArray(data.attachments) && data.attachments.length > 0) {
                    // Ambil path dari index 0
                    const path = data.attachments[0].path; 
                    fotoUrl = apiBaseUrl + path;
                } 
                // Cek 2: Fallback ke bukti_foto (Format Lama)
                else if (data.bukti_foto && data.bukti_foto !== 'null') {
                    fotoUrl = data.bukti_foto.startsWith('http') ? 
                              data.bukti_foto : 
                              apiBaseUrl + '/storage/' + data.bukti_foto;
                }

                // Render Foto
                if (fotoUrl) {
                    $('#d-foto').attr('src', fotoUrl).show();
                    $('#d-no-foto').hide();
                } else {
                    $('#d-foto').hide();
                    $('#d-no-foto').show();
                }
                // ======================================

                // Status & Form Values
                let currentStatusId = null;
                let statusName = "Menunggu";
                let pesanBalasan = data.pesan_balasan || "";
                let catatanInternal = data.catatan_internal || "";

                $('#input-pesan').val(pesanBalasan);
                $('#input-catatan-internal').val(catatanInternal);

                if (data.status && typeof data.status === 'object') {
                    currentStatusId = data.status.id || null;
                    statusName = data.status.name || "Menunggu";
                } else if (data.status_id) {
                    currentStatusId = data.status_id;
                }

                // Set Dropdown
                if (currentStatusId) {
                    setTimeout(() => {
                        $('#input-status').val(currentStatusId);
                        if ($('#input-status option:selected').text()) {
                            statusName = $('#input-status option:selected').text();
                            updateBadgeStatus(statusName);
                        }
                    }, 500);
                }

                updateBadgeStatus(statusName);
            }

            function updateBadgeStatus(name) {
                let badgeClass = 'badge-secondary';
                if (name.toLowerCase().includes('proses')) badgeClass = 'badge-info';
                else if (name.toLowerCase().includes('selesai') || name.toLowerCase().includes('tutup')) badgeClass =
                    'badge-success';
                else if (name.toLowerCase().includes('tolak')) badgeClass = 'badge-danger';
                else badgeClass = 'badge-warning';

                $('#d-status-badge').text(name)
                    .removeClass('badge-secondary badge-info badge-success badge-danger badge-warning')
                    .addClass(badgeClass);
            }

            // --- SUBMIT FORM TINDAK LANJUT ---
            $('#form-tindak-lanjut').on('submit', function(e) {
                e.preventDefault();
                const finalReportId = window.currentReportId || reportId;

                if (!finalReportId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'ID laporan tidak ditemukan.'
                    });
                    return;
                }

                const statusId = $('#input-status').val();
                const statusText = $('#input-status option:selected').text();
                const pesan = $('#input-pesan').val();
                const catatanInternal = $('#input-catatan-internal').val() || '';

                if (!statusId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Silakan pilih status terlebih dahulu!'
                    });
                    return;
                }
                if (!pesan || pesan.trim() === '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Silakan isi pesan balasan!'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Menyimpan...',
                    didOpen: () => Swal.showLoading(),
                    allowOutsideClick: false,
                    showConfirmButton: false
                });

                const updateData = {
                    id: finalReportId,
                    status_id: statusId,
                    pesan_balasan: pesan.trim(),
                    catatan_internal: catatanInternal.trim()
                };

                const apiEndpoint = 'https://api-hacktown.rusnandapurnama.com/laporan';

                $.ajax({
                    url: apiEndpoint,
                    type: 'PUT',
                    dataType: 'json',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    data: JSON.stringify(updateData),
                    success: function(result) {
                        if (result.status === 200 || result.message.toLowerCase().includes(
                                'berhasil')) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Laporan berhasil diupdate.',
                                confirmButtonColor: '#3085d6',
                            }).then(() => {
                                window.location.href = "{{ url('/lapor') }}";
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: result.message || 'Gagal update.'
                            });
                        }
                    },
                    error: function(xhr) {
                        let msg = 'Terjadi kesalahan.';
                        if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON
                            .message;
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: msg
                        });
                    }
                });
            });
        });
    </script>
@endpush