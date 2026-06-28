@extends('layouts.main')

@section('title', 'Dashboard Admin - Kelola Antrian')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex">

        {{-- Sidebar --}}
        <aside class="sidebar p-3" style="width: 260px; min-height: 100vh;">
            <div class="mb-4 text-center text-white">
                <h4 class="fw-bold mb-0"><i class="bi bi-gear-fill me-2"></i>Admin Panel</h4>
                <small class="text-white-50">Sistem Antrian</small>
            </div>

            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link custom-nav active text-white">
                        <i class="bi bi-list-task me-2"></i>Daftar Antrian
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link custom-nav text-white">
                        <i class="bi bi-bar-chart me-2"></i>Statistik
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link custom-nav text-white">
                        <i class="bi bi-gear me-2"></i>Pengaturan
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <a href="/" class="nav-link custom-nav text-white">
                        <i class="bi bi-box-arrow-left me-2"></i>Kembali ke Beranda
                    </a>
                </li>
                <li class="nav-item mt-2">
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="nav-link custom-nav text-white w-100 text-start bg-transparent border-0">
                            <i class="bi bi-power me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </aside>

        {{-- Main Content --}}
        <div class="flex-grow-1 p-4">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1"><i class="bi bi-clipboard-data text-primary me-2"></i>Kelola Antrian</h3>
                    <p class="text-muted mb-0">Panggil dan kelola antrian pelanggan</p>
                </div>
                <div>
                    <span class="status-dot bg-success me-2"></span>
                    <small class="text-muted">Live</small>
                </div>
            </div>

            {{-- Statistik Cards --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card card-queue text-center">
                        <div class="card-body py-3">
                            <h2 class="fw-bold text-primary mb-0" id="statMenunggu">0</h2>
                            <small class="text-muted">Menunggu</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-queue text-center">
                        <div class="card-body py-3">
                            <h2 class="fw-bold text-warning mb-0" id="statDiproses">0</h2>
                            <small class="text-muted">Diproses</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-queue text-center">
                        <div class="card-body py-3">
                            <h2 class="fw-bold text-success mb-0" id="statSelesai">0</h2>
                            <small class="text-muted">Selesai</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-queue text-center">
                        <div class="card-body py-3">
                            <h2 class="fw-bold text-info mb-0" id="statTotal">0</h2>
                            <small class="text-muted">Total Hari Ini</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Antrian Sekarang --}}
            <div class="card card-queue mb-4">
                <div class="card-header bg-primary text-white fw-semibold">
                    <i class="bi bi-mic-fill me-2"></i>Antrian Sekarang
                </div>
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <span class="status-dot bg-success"></span>
                        <small class="text-muted ms-1">Sedang Dipanggil</small>
                    </div>
                    <h1 class="display-1 fw-bold text-primary mb-2" id="nowNumber">-</h1>
                    <p class="text-muted mb-0" id="nowService">Tidak ada antrian aktif</p>
                    <div class="mt-4">
                        <button class="btn btn-success btn-lg me-2" id="btnPanggil" disabled>
                            <i class="bi bi-bell-fill me-2"></i>Panggil Nomor Berikutnya
                        </button>
                        <button class="btn btn-outline-secondary" id="btnReset" title="Reset Semua Antrian">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Daftar Antrian --}}
            <div class="card card-queue">
                <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-list-ol text-primary me-2"></i>Daftar Antrian</span>
                    <div>
                        <select class="form-select form-select-sm d-inline-block w-auto" id="filterLayanan">
                            <option value="">Semua Layanan</option>
                            <option value="umum">Umum</option>
                            <option value="prioritas">Prioritas</option>
                            <option value="bisnis">Bisnis</option>
                        </select>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="text-center">No. Antrian</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">No. HP</th>
                                    <th scope="col">Layanan</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Waktu Ambil</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="queueTable">
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Memuat data...</td>
                                </tr>
                            </tbody>
                        </table>
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
    let allQueues = [];

    function initSSE() {
        eventSource = new EventSource("{{ route('antrian.stream') }}");

        // Unified SSE event
        eventSource.onmessage = function(e) {
            const data = JSON.parse(e.data);
            allQueues = data.queues || [];

            renderQueueTable(allQueues);

            if (data.stats) {
                document.getElementById('statMenunggu').textContent = data.stats.waiting;
                document.getElementById('statDiproses').textContent = data.stats.called;
                document.getElementById('statSelesai').textContent = data.stats.done;
                document.getElementById('statTotal').textContent = allQueues.length;
            }

            if (data.current) {
                document.getElementById('nowNumber').textContent = data.current.nomor ?? '-';
                const layananLabels = { umum: 'Layanan Umum', prioritas: 'Layanan Prioritas', bisnis: 'Layanan Bisnis' };
                document.getElementById('nowService').textContent = data.current.nama
                    ? `${data.current.nama} - ${layananLabels[data.current.layanan] || data.current.layanan}`
                    : 'Tidak ada antrian aktif';
                document.getElementById('btnPanggil').disabled = false;
            } else {
                document.getElementById('nowNumber').textContent = '-';
                document.getElementById('nowService').textContent = 'Tidak ada antrian aktif';
                document.getElementById('btnPanggil').disabled = allQueues.filter(q => q.status === 'waiting').length === 0;
            }
        };

        eventSource.onerror = function() {
            console.log('SSE Connection error, retrying...');
        };
    }

    function capitalize(str) {
        return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
    }

    function renderQueueTable(queues) {
        const tbody = document.getElementById('queueTable');
        const filter = document.getElementById('filterLayanan').value;
        const filtered = filter ? queues.filter(q => q.layanan === filter) : queues;

        if (filtered.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">Tidak ada antrian</td></tr>';
            return;
        }

        tbody.innerHTML = filtered.map(q => {
            const statusMap = {
                'waiting': ['bg-secondary', 'Menunggu'],
                'called':  ['bg-warning text-dark', 'Dipanggil'],
                'missed':  ['bg-danger', 'Terlewat'],
                'done':    ['bg-success', 'Selesai'],
            };
            const [statusClass, statusText] = statusMap[q.status] || ['bg-secondary', 'Unknown'];
            const layananClass = q.layanan === 'umum' ? 'bg-primary' :
                                 q.layanan === 'prioritas' ? 'bg-danger' : 'bg-info';
            const layananText = capitalize(q.layanan);
            const waktu = new Date(q.created_at).toLocaleString('id-ID', {
                day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit'
            });

            const btnPanggil = q.status === 'waiting'
                ? `<button class="btn btn-sm btn-success" onclick="panggil(${q.id})"><i class="bi bi-bell"></i> Panggil</button>
                   <button class="btn btn-sm btn-outline-danger" onclick="selesai(${q.id})"><i class="bi bi-check-lg"></i></button>`
                : q.status === 'called'
                ? `<button class="btn btn-sm btn-warning" onclick="selesai(${q.id})"><i class="bi bi-check-lg"></i> Selesai</button>`
                : `<span class="badge ${statusClass}"><i class="bi bi-check"></i> ${statusText}</span>`;

            return `<tr>
                <td class="text-center"><span class="badge bg-dark queue-badge">${q.nomor}</span></td>
                <td>${q.nama}</td>
                <td>${q.no_hp || '-'}</td>
                <td><span class="badge ${layananClass}">${layananText}</span></td>
                <td><span class="badge ${statusClass}">${statusText}</span></td>
                <td>${waktu}</td>
                <td class="text-center">${btnPanggil}</td>
            </tr>`;
        }).join('');
    }

    function panggil(id) {
        fetch("{{ url('/call') }}/" + id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) alert('Gagal memanggil: ' + (data.message || 'Terjadi kesalahan'));
        });
    }

    function selesai(id) {
        fetch("{{ url('/done') }}/" + id, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) alert('Gagal menyelesaikan: ' + (data.message || 'Terjadi kesalahan'));
        });
    }

    document.getElementById('btnPanggil').addEventListener('click', function() {
        const waitingQueues = allQueues.filter(q => q.status === 'waiting');
        if (waitingQueues.length > 0) {
            panggil(waitingQueues[0].id);
        }
    });

    document.getElementById('btnReset').addEventListener('click', function() {
        if (confirm('Yakin ingin mereset semua antrian hari ini?')) {
            fetch("{{ route('antrian.reset') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) alert('Antrian berhasil direset');
            });
        }
    });

    document.getElementById('filterLayanan').addEventListener('change', function() {
        renderQueueTable(allQueues);
    });

    initSSE();
</script>
@endpush