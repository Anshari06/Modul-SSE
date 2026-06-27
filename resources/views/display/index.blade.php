@extends('layouts.main')

@section('title', 'Layar Tampilan Antrian')

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
        color: white;
    }
    .navbar {
        background: transparent !important;
    }
    .display-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 80px);
        padding: 2rem;
    }
    .queue-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 24px;
        padding: 4rem;
        text-align: center;
        width: 100%;
        max-width: 600px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }
    .queue-number {
        font-size: 10rem;
        font-weight: 900;
        line-height: 1;
        text-shadow: 4px 4px 8px rgba(0, 0, 0, 0.2);
        letter-spacing: -5px;
        color: #ffd700;
        animation: bounceIn 0.5s ease-out;
    }
    @keyframes bounceIn {
        0% { transform: scale(0.5); opacity: 0; }
        70% { transform: scale(1.1); }
        100% { transform: scale(1); opacity: 1; }
    }
    .queue-label {
        font-size: 1.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 3px;
        opacity: 0.9;
    }
    .service-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 50px;
        padding: 8px 24px;
        font-size: 1.2rem;
        font-weight: 600;
        margin-top: 1rem;
    }
    .next-queues {
        margin-top: 3rem;
        width: 100%;
    }
    .next-queue-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 12px 24px;
        margin-bottom: 8px;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }
    .next-queue-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #ffd700;
    }
    .next-queue-name {
        font-size: 1rem;
        opacity: 0.8;
    }
    .status-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.3);
        padding: 10px 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.9rem;
    }
    .blink-animation {
        animation: blinkText 1s infinite;
    }
    @keyframes blinkText {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }
    .live-indicator {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .live-dot {
        width: 10px;
        height: 10px;
        background: #28a745;
        border-radius: 50%;
        animation: livePulse 1.5s infinite;
    }
    @keyframes livePulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(0.8); }
    }
</style>
@endpush

@section('navbar')
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <span class="navbar-brand fw-bold">
            <i class="bi bi-tv-fill me-2"></i>Layar Tampilan Antrian
        </span>
        <div class="d-flex align-items-center gap-3">
            <span class="live-indicator">
                <span class="live-dot"></span>
                <span>LIVE</span>
            </span>
            <a href="/" class="btn btn-sm btn-outline-light">
                <i class="bi bi-house-door me-1"></i>Beranda
            </a>
        </div>
    </div>
</nav>
@endsection

@section('content')
<div class="display-container">
    <div class="queue-card">
        <p class="queue-label mb-2">Nomor Antrian</p>
        <div class="queue-number" id="displayNumber">-</div>
        <div class="service-badge" id="displayService">
            <i class="bi bi-hourglass-split me-2"></i>Menunggu...
        </div>
    </div>

    {{-- Antrian Berikutnya --}}
    <div class="next-queues mt-4">
        <h5 class="text-center mb-3 opacity-75">
            <i class="bi bi-list-ol me-2"></i>Antrian Berikutnya
        </h5>
        <div id="nextQueues">
            <div class="next-queue-item">
                <span class="next-queue-name">Memuat...</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<div class="status-bar">
    <span id="currentTime"></span>
    <span class="blink-animation">
        <i class="bi bi-megaphone-fill me-1"></i>Dipanggil, silakan menuju loket
    </span>
    <span id="queueCount">0 antrian menunggu</span>
</div>
@endsection

@push('scripts')
<script>
    let eventSource;

    function updateClock() {
        const now = new Date();
        document.getElementById('currentTime').textContent = now.toLocaleString('id-ID', {
            weekday: 'long', day: '2-digit', month: 'long', year: 'numeric',
            hour: '2-digit', minute: '2-digit', second: '2-digit'
        });
    }
    setInterval(updateClock, 1000);
    updateClock();

    function initSSE() {
        eventSource = new EventSource("{{ route('antrian.stream') }}");

        eventSource.addEventListener('now_serving', function(e) {
            const data = JSON.parse(e.data);
            const numEl = document.getElementById('displayNumber');
            const svcEl = document.getElementById('displayService');

            if (data.nomor) {
                numEl.textContent = data.nomor;
                numEl.style.animation = 'none';
                numEl.offsetHeight; // trigger reflow
                numEl.style.animation = 'bounceIn 0.5s ease-out';

                const serviceIcons = {
                    'umum': 'bi-person-fill',
                    'prioritas': 'bi-star-fill',
                    'bisnis': 'bi-briefcase-fill'
                };
                const serviceLabels = {
                    'umum': 'Layanan Umum',
                    'prioritas': 'Layanan Prioritas',
                    'bisnis': 'Layanan Bisnis'
                };
                const icon = serviceIcons[data.layanan] || 'bi-question-circle';
                const label = serviceLabels[data.layanan] || data.layanan;

                svcEl.innerHTML = `<i class="bi ${icon} me-2"></i>${label}`;
            } else {
                numEl.textContent = '-';
                svcEl.innerHTML = `<i class="bi bi-hourglass-split me-2"></i>Menunggu...`;
            }
        });

        eventSource.addEventListener('queue_update', function(e) {
            const queues = JSON.parse(e.data);
            const waiting = queues.filter(q => q.status === 'menunggu');
            document.getElementById('queueCount').textContent = `${waiting.length} antrian menunggu`;

            const nextEl = document.getElementById('nextQueues');
            if (waiting.length === 0) {
                nextEl.innerHTML = '<div class="next-queue-item"><span class="next-queue-name text-white-50">Tidak ada antrian menunggu</span></div>';
                return;
            }

            nextEl.innerHTML = waiting.slice(0, 3).map((q, i) => {
                const serviceIcons = {
                    'umum': 'bi-person-fill',
                    'prioritas': 'bi-star-fill',
                    'bisnis': 'bi-briefcase-fill'
                };
                const icon = serviceIcons[q.layanan] || 'bi-question-circle';
                const label = q.layanan.charAt(0).toUpperCase() + q.layanan.slice(1);

                return `<div class="next-queue-item">
                    <span class="next-queue-number">${q.nomor}</span>
                    <span class="next-queue-name">${q.nama}</span>
                    <span><i class="bi ${icon} me-1"></i>${label}</span>
                </div>`;
            }).join('');
        });

        eventSource.onerror = function() {
            console.log('SSE error, retrying...');
        };
    }

    initSSE();
</script>
@endpush