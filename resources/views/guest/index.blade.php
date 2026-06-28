@extends('layouts.main')

@section('title', 'Ambil Nomor Antrian')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- Header --}}
            <div class="text-center mb-5">
                <h1 class="fw-bold text-primary mb-2">
                    <i class="bi bi-ticket-perforated me-2"></i>Ambil Nomor Antrian
                </h1>
                <p class="text-muted">Ambil nomor antrian Anda dengan mudah dan cepat</p>
            </div>

            {{-- Info Antrian Sekarang --}}
            <div class="card card-queue mb-4">
                <div class="card-body text-center py-4">
                    <h5 class="card-title text-muted mb-3">Nomor Antrian Sekarang</h5>
                    <h1 class="display-1 fw-bold text-primary mb-0" id="currentNumber">-</h1>
                    <p class="text-muted mt-2 mb-0" id="currentService">Memuat...</p>
                </div>
            </div>

            {{-- Form Ambil Antrian --}}
            <div class="card card-queue">
                <div class="card-body p-4">
                    <h5 class="card-title fw-semibold mb-3">
                        <i class="bi bi-clipboard-plus text-primary me-2"></i>Formulir Pengambilan Antrian
                    </h5>

                    <form id="formAmbilAntrian">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama lengkap Anda" required>
                        </div>

                        <div class="mb-3">
                            <label for="no_hp" class="form-label">Nomor HP / WhatsApp</label>
                            <input type="tel" class="form-control" id="no_hp" name="no_hp" placeholder="Contoh: 081234567890">
                        </div>

                        <div class="mb-4">
                            <label for="layanan" class="form-label">Pilih Layanan <span class="text-danger">*</span></label>
                            <select class="form-select" id="layanan" name="layanan" required>
                                <option value="" selected disabled>-- Pilih Layanan --</option>
                                <option value="umum">Layanan Umum</option>
                                <option value="bisnis">Layanan Bisnis</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-queue w-100">
                            <i class="bi bi-ticket-fill me-2"></i>Ambil Nomor Antrian
                        </button>
                    </form>
                </div>
            </div>

            {{-- Notifikasi --}}
            <div id="notification" class="alert alert-success mt-4 text-center" style="display: none;">
                <i class="bi bi-check-circle-fill me-2"></i>
                <span id="notificationText"></span>
            </div>

            {{-- Daftar Antrian Saya --}}
            <div class="card card-queue mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title fw-semibold mb-3">
                        <i class="bi bi-list-check text-primary me-2"></i>Nomor Antrian Saya
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-primary">
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Layanan</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Waktu</th>
                                </tr>
                            </thead>
                            <tbody id="myQueueTable">
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Belum ada data antrian</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Keterangan Status --}}
            <div class="row mt-4">
                <div class="col-md-4 mb-2">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-secondary queue-badge me-2 stat-menunggu-num">0</span>
                        <small class="text-muted">Menunggu</small>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-warning queue-badge me-2 stat-diproses-num">0</span>
                        <small class="text-muted">Diproses</small>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success queue-badge me-2 stat-selesai-num">0</span>
                        <small class="text-muted">Selesai</small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let eventSource;

    function initSSE() {
        eventSource = new EventSource("{{ route('antrian.stream') }}");

        // Event tunggal unified: dapat queues, current, stats
        eventSource.onmessage = function(e) {
            const data = JSON.parse(e.data);

            // Update antrian sekarang (yang sedang dipanggil)
            if (data.current) {
                document.getElementById('currentNumber').textContent = data.current.nomor ?? '-';
                const layananLabels = { umum: 'Layanan Umum', prioritas: 'Layanan Prioritas', bisnis: 'Layanan Bisnis' };
                document.getElementById('currentService').textContent = data.current.nama
                    ? `${data.current.nama} - ${layananLabels[data.current.layanan] || data.current.layanan}`
                    : 'Tidak ada antrian aktif';
            }

            // Update statistik
            if (data.stats) {
                const el = document.querySelector('.stat-menunggu-num');
                if (el) el.textContent = data.stats.waiting;
                const el2 = document.querySelector('.stat-diproses-num');
                if (el2) el2.textContent = data.stats.called;
                const el3 = document.querySelector('.stat-selesai-num');
                if (el3) el3.textContent = data.stats.done;
            }

            // Update tabel antrian saya (tampilkan semua status)
            const tbody = document.getElementById('myQueueTable');
            const queues = data.queues || [];

            if (queues.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">Belum ada data antrian</td></tr>';
                return;
            }

            tbody.innerHTML = queues.map(q => {
                const statusMap = {
                    'waiting': ['bg-secondary', 'Menunggu'],
                    'called':  ['bg-warning text-dark', 'Dipanggil'],
                    'missed':  ['bg-danger', 'Terlewat'],
                    'done':    ['bg-success', 'Selesai'],
                };
                const [statusClass, statusText] = statusMap[q.status] || ['bg-secondary', 'Unknown'];
                const layananText = { umum: 'Umum', prioritas: 'Prioritas', bisnis: 'Bisnis' }[q.layanan] || q.layanan;
                const waktu = new Date(q.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

                return `<tr>
                    <td><span class="badge bg-primary queue-badge">${q.nomor}</span></td>
                    <td>${q.nama}</td>
                    <td><span class="badge bg-info">${layananText}</span></td>
                    <td><span class="badge ${statusClass}">${statusText}</span></td>
                    <td>${waktu}</td>
                </tr>`;
            }).join('');
        };

        eventSource.onerror = function() {
            console.log('SSE Connection error, retrying...');
        };
    }

    document.getElementById('formAmbilAntrian').addEventListener('submit', function(e) {
        e.preventDefault();

        const nama = document.getElementById('nama').value;
        const no_hp = document.getElementById('no_hp').value;
        const layanan = document.getElementById('layanan').value;

        fetch("{{ route('antrian.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ nama, no_hp, layanan })
        })
        .then(res => res.json())
        .then(data => {
            const notif = document.getElementById('notification');
            const notifText = document.getElementById('notificationText');

            if (data.success) {
                notif.className = 'alert alert-success mt-4 text-center';
                notifText.textContent = `Berhasil! Nomor antrian Anda adalah ${data.nomor}`;
                notif.style.display = 'block';
                this.reset();
            } else {
                notif.className = 'alert alert-danger mt-4 text-center';
                notifText.textContent = data.message || 'Terjadi kesalahan';
                notif.style.display = 'block';
            }

            setTimeout(() => { notif.style.display = 'none'; }, 5000);
        })
        .catch(err => {
            const notif = document.getElementById('notification');
            notif.className = 'alert alert-danger mt-4 text-center';
            notif.querySelector('span').textContent = 'Gagal mengambil nomor antrian';
            notif.style.display = 'block';
        });
    });

    initSSE();
</script>
@endpush
