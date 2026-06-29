@extends('layouts.main')

@section('title', 'Layar Tampilan Antrian')

@push('styles')
    <style>
        body {
            background: linear-gradient(135deg, #0d2b6e 0%, #0a4d8c 50%, #0d2b6e 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Animated background pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.03) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .navbar {
            background: rgba(0, 0, 0, 0.2) !important;
            position: relative;
            z-index: 10;
        }

        .main-content {
            position: relative;
            z-index: 5;
            min-height: calc(100vh - 80px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        /* ============ MAIN CALLOUT CARD ============ */
        .callout-card {
            background: linear-gradient(145deg, #ffffff 0%, #f0f4ff 100%);
            border-radius: 24px;
            padding: 3rem 4rem;
            text-align: center;
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.2) inset;
            width: 100%;
            max-width: 600px;
            position: relative;
            overflow: hidden;
        }

        .callout-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #0d2b6e, #f59e0b, #0d2b6e);
        }

        .callout-label {
            font-size: 1rem;
            font-weight: 600;
            color: #0d2b6e;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 0.5rem;
            opacity: 0.7;
        }

        .callout-number {
            font-size: 9rem;
            font-weight: 900;
            line-height: 0.9;
            letter-spacing: -6px;
            color: #0d2b6e;
            text-shadow: 3px 3px 0 rgba(13, 43, 110, 0.1);
            transition: all 0.3s ease;
        }

        .callout-number.active {
            color: #f59e0b;
            text-shadow: 0 0 40px rgba(245, 158, 11, 0.5), 3px 3px 0 rgba(13, 43, 110, 0.1);
        }

        /* Pulse animation when called */
        .callout-number.pulse {
            animation: callPulse 1.2s ease-in-out infinite;
        }

        @keyframes callPulse {

            0%,
            100% {
                transform: scale(1);
                text-shadow: 0 0 40px rgba(245, 158, 11, 0.5), 3px 3px 0 rgba(13, 43, 110, 0.1);
            }

            50% {
                transform: scale(1.05);
                text-shadow: 0 0 80px rgba(245, 158, 11, 0.8), 3px 3px 0 rgba(13, 43, 110, 0.1);
            }
        }

        .callout-name {
            font-size: 2.2rem;
            font-weight: 700;
            color: #333;
            margin-top: 0.5rem;
            min-height: 2.8rem;
        }

        .callout-service {
            display: inline-block;
            background: linear-gradient(135deg, #0d2b6e, #1a4fa0);
            color: white;
            border-radius: 50px;
            padding: 8px 28px;
            font-size: 1rem;
            font-weight: 600;
            margin-top: 1rem;
            letter-spacing: 1px;
        }

        .callout-empty {
            font-size: 3rem;
            font-weight: 800;
            color: #bbb;
            padding: 3rem 1rem;
        }

        .callout-empty-label {
            font-size: 1rem;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-top: 0.5rem;
        }

        /* ============ NEXT QUEUE SECTION ============ */
        .next-section {
            width: 100%;
            max-width: 800px;
            margin-top: 2rem;
        }

        .next-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1rem;
            padding: 0 0.5rem;
        }

        .next-header h5 {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-size: 0.85rem;
            margin: 0;
        }

        .next-divider {
            flex: 1;
            height: 2px;
            background: rgba(255, 255, 255, 0.2);
        }

        .next-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
        }

        .next-item {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 14px;
            padding: 14px 10px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .next-item:first-child {
            background: rgba(255, 255, 255, 0.18);
            border-color: rgba(255, 255, 255, 0.35);
        }

        .next-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .next-item-number {
            font-size: 1.8rem;
            font-weight: 900;
            color: #ffd700;
            line-height: 1;
            letter-spacing: -1px;
        }

        .next-item-name {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.75);
            margin-top: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .next-empty {
            grid-column: 1 / -1;
            text-align: center;
            color: rgba(255, 255, 255, 0.4);
            padding: 2rem;
            font-size: 1rem;
            letter-spacing: 2px;
        }

        /* ============ STATS BAR ============ */
        .stats-row {
            width: 100%;
            max-width: 800px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 1.5rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            padding: 12px 16px;
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 900;
            color: white;
            line-height: 1;
        }

        .stat-number.waiting {
            color: #93c5fd;
        }

        .stat-number.called {
            color: #fcd34d;
        }

        .stat-number.done {
            color: #6ee7b7;
        }

        .stat-label {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 4px;
        }

        /* ============ BOTTOM STATUS BAR ============ */
        .status-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(10px);
            padding: 10px 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.6);
            z-index: 20;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .blink-text {
            animation: blinkText 1.5s ease-in-out infinite;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 600;
        }

        @keyframes blinkText {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.25;
            }
        }

        .live-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50px;
            padding: 4px 12px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.7);
        }

        .live-dot {
            width: 8px;
            height: 8px;
            background: #22c55e;
            border-radius: 50%;
            animation: livePulse 1.5s infinite;
        }

        @keyframes livePulse {

            0%,
            100% {
                opacity: 1;
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4);
            }

            50% {
                opacity: 0.6;
                box-shadow: 0 0 0 6px rgba(34, 197, 94, 0);
            }
        }

        /* Sound wave animation when audio plays */
        .sound-wave {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            margin-left: 8px;
        }

        .sound-wave span {
            display: inline-block;
            width: 4px;
            background: #ffd700;
            border-radius: 2px;
            animation: soundBar 0.8s ease-in-out infinite;
        }

        .sound-wave span:nth-child(1) {
            height: 8px;
            animation-delay: 0s;
        }

        .sound-wave span:nth-child(2) {
            height: 14px;
            animation-delay: 0.1s;
        }

        .sound-wave span:nth-child(3) {
            height: 20px;
            animation-delay: 0.2s;
        }

        .sound-wave span:nth-child(4) {
            height: 14px;
            animation-delay: 0.3s;
        }

        .sound-wave span:nth-child(5) {
            height: 8px;
            animation-delay: 0.4s;
        }

        @keyframes soundBar {

            0%,
            100% {
                transform: scaleY(0.4);
            }

            50% {
                transform: scaleY(1.4);
            }
        }

        /* Flash overlay when new call */
        .flash-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0);
            pointer-events: none;
            z-index: 100;
            transition: background 0.1s;
        }

        .flash-overlay.flash {
            animation: flashAnim 0.6s ease-out;
        }

        @keyframes flashAnim {
            0% {
                background: rgba(255, 255, 255, 0.6);
            }

            100% {
                background: rgba(255, 255, 255, 0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .callout-number {
                font-size: 5rem;
                letter-spacing: -3px;
            }

            .callout-name {
                font-size: 1.4rem;
            }

            .next-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .stats-row {
                grid-template-columns: repeat(3, 1fr);
            }

            .callout-card {
                padding: 2rem;
            }
        }

        @media (max-width: 480px) {
            .next-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .stats-row {
                grid-template-columns: repeat(3, 1fr);
            }
        }
    </style>
@endpush

@section('navbar')
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container d-flex justify-content-between align-items-center">
            <span class="navbar-brand fw-bold fs-5">
                <i class="bi bi-tv-fill me-2"></i>LAYAR ANTRIAN
            </span>
            <div class="d-flex align-items-center gap-3">
                <span class="live-badge">
                    <span class="live-dot"></span>
                    LIVE
                </span>
                <a href="{{ route('guest') }}" class="btn btn-sm btn-outline-light">
                    <i class="bi bi-house-door me-1"></i>Beranda
                </a>
            </div>
        </div>
    </nav>
@endsection

@section('content')
    <div class="flash-overlay" id="flashOverlay"></div>

    <div class="main-content">
        <div style="width:100%; max-width:800px;">

            {{-- MAIN CALLOUT --}}
            <div class="callout-card" id="calloutCard">
                <div class="callout-label">NOMOR ANTRIAN</div>

                <div id="calloutContent">
                    <div class="callout-number" id="displayNumber">-</div>
                    <div class="callout-name" id="displayName">Memuat...</div>
                    <div class="callout-service" id="displayService">
                        <i class="bi bi-hourglass-split me-2"></i>Menunggu...
                    </div>
                </div>

                <div id="emptyContent" style="display:none;">
                    <div class="callout-empty">
                        <i class="bi bi-inbox" style="font-size:4rem; display:block; margin-bottom:0.5rem; color:#ccc;"></i>
                        BELUM ADA
                    </div>
                    <div class="callout-empty-label">Nomor antrian dipanggil</div>
                </div>
            </div>

            {{-- NEXT QUEUE --}}
            <div class="next-section">
                <div class="next-header">
                    <h5><i class="bi bi-list-ol me-2"></i>ANTRIAN MENUNGGU</h5>
                    <div class="next-divider"></div>
                </div>
                <div class="next-grid" id="nextQueues">
                    <div class="next-empty">Memuat...</div>
                </div>
            </div>

            {{-- STATS --}}
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-number waiting" id="statWaiting">0</div>
                    <div class="stat-label">Menunggu</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number called" id="statCalled">0</div>
                    <div class="stat-label">Dipanggil</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number done" id="statDone">0</div>
                    <div class="stat-label">Selesai</div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('footer')
    <div class="status-bar">
        <span id="currentTime"></span>
        <span>
            <span class="blink-text" id="callStatus">
                <i class="bi bi-megaphone-fill me-1"></i>Dipanggil, silakan menuju loket
            </span>
            <span id="soundWave" class="sound-wave" style="display:none;">
                <span></span><span></span><span></span><span></span><span></span>
            </span>
        </span>
        <span id="queueCountText">0 antrian</span>
    </div>
@endsection

@push('scripts')
    <script>
        // =====================================================
        // STATE
        // =====================================================
        let eventSource;
        let lastCalledAt = null; // Gunakan timestamp untuk deteksi pemanggilan baru
        let isSpeaking = false;
        let lastQueueCount = 0; // Cegah flash saat data sama

        const serviceLabels = {
            'umum': {
                text: 'Layanan Umum',
                icon: 'bi-person-fill'
            },
            'prioritas': {
                text: 'Layanan Prioritas',
                icon: 'bi-star-fill'
            },
            'bisnis': {
                text: 'Layanan Bisnis',
                icon: 'bi-briefcase-fill'
            },
        };

        // =====================================================
        // JAM REAL-TIME
        // =====================================================
        function updateClock() {
            const el = document.getElementById('currentTime');
            if (!el) return;
            const now = new Date();
            el.textContent = now.toLocaleString('id-ID', {
                weekday: 'long',
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }
        setInterval(updateClock, 1000);
        updateClock();

        // =====================================================
        // AUDIO PLAY
        // =====================================================
        function playTingtong() {
            try {
                const audio = new Audio('/audio/tingtong.mp3');
                audio.volume = 0.8;
                audio.play().then(() => {
                    // Hentikan audio setelah 2000 milidetik (2 detik)
                    setTimeout(() => {
                        audio.pause();
                        audio.currentTime = 0; // Reset ke detik ke-0 agar siap jika dipanggil lagi
                    }, 2000);
                }).catch(() => {});
            } catch (e) {
                console.log('Audio play error:', e.message);
            }
        }

        // =====================================================
        // SPEECH SYNTHESIS
        // =====================================================
        function speakQueue(nomor, nama) {
            if (!('speechSynthesis' in window)) return;
            if (isSpeaking) {
                speechSynthesis.cancel();
            }

            const label = serviceLabels[nomor] ? serviceLabels[nomor].text : '';

            const text = `Nomor antrian ${nomor}, atas nama ${nama}. Silakan menuju loket.`;

            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'id-ID';
            utterance.rate = 0.9;
            utterance.pitch = 1;

            utterance.onstart = () => {
                isSpeaking = true;
                document.getElementById('soundWave').style.display = 'inline-flex';
            };
            utterance.onend = () => {
                isSpeaking = false;
                document.getElementById('soundWave').style.display = 'none';
            };
            utterance.onerror = () => {
                isSpeaking = false;
                document.getElementById('soundWave').style.display = 'none';
            };

            speechSynthesis.speak(utterance);
        }

        // =====================================================
        // FLASH OVERLAY
        // =====================================================
        function triggerFlash() {
            const el = document.getElementById('flashOverlay');
            if (!el) return;
            el.classList.remove('flash');
            void el.offsetWidth; // reflow
            el.classList.add('flash');
        }

        // =====================================================
        // UPDATE CALLOUT
        // =====================================================
        function updateCallout(current) {
            const numEl = document.getElementById('displayNumber');
            const nameEl = document.getElementById('displayName');
            const svcEl = document.getElementById('displayService');
            const calloutContent = document.getElementById('calloutContent');
            const emptyContent = document.getElementById('emptyContent');

            if (!current || !current.nomor) {
                // EMPTY STATE
                calloutContent.style.display = 'none';
                emptyContent.style.display = 'block';
                numEl.classList.remove('active', 'pulse');
                lastCalledAt = null;
                return;
            }

            calloutContent.style.display = 'block';
            emptyContent.style.display = 'none';

            // Deteksi pemanggilan baru berdasarkan timestamp called_at
            const isNew = lastCalledAt !== current.called_at;

            // Update teks
            numEl.textContent = current.nomor;
            nameEl.textContent = current.nama;

            const svc = serviceLabels[current.layanan] || {
                text: current.layanan,
                icon: 'bi-question-circle'
            };
            svcEl.innerHTML = `<i class="bi ${svc.icon} me-2"></i>${svc.text}`;

            // Trigger hanya kalau timestamp BERUBAH (termasuk recall)
            if (isNew) {
                lastCalledAt = current.called_at;

                // Flash screen
                triggerFlash();

                // Pulse animation
                numEl.classList.add('active', 'pulse');
                setTimeout(() => numEl.classList.remove('pulse'), 3000);

                // Audio + Speech
                playTingtong();
                speakQueue(current.nomor, current.nama);
            }
        }

        // =====================================================
        // UPDATE NEXT QUEUES
        // =====================================================
        function updateNextQueues(queues) {
            const waiting = queues.filter(q => q.status === 'waiting').slice(0, 5);
            const el = document.getElementById('nextQueues');

            if (waiting.length === 0) {
                el.innerHTML =
                    '<div class="next-empty"><i class="bi bi-check-circle me-2"></i>Tidak ada antrian menunggu</div>';
                return;
            }

            el.innerHTML = waiting.map((q, i) => `
            <div class="next-item">
                <div class="next-item-number">${q.nomor}</div>
                <div class="next-item-name">${q.nama}</div>
            </div>
        `).join('');
        }

        // =====================================================
        // UPDATE STATS
        // =====================================================
        function updateStats(stats) {
            const el1 = document.getElementById('statWaiting');
            const el2 = document.getElementById('statCalled');
            const el3 = document.getElementById('statDone');
            const cnt = document.getElementById('queueCountText');

            if (el1) el1.textContent = stats.waiting || 0;
            if (el2) el2.textContent = stats.called || 0;
            if (el3) el3.textContent = stats.done || 0;
            if (cnt) cnt.textContent = `${(stats.waiting || 0) + (stats.called || 0)} antrian`;
        }

        // =====================================================
        // INIT SSE
        // =====================================================
        function initSSE() {
            const esUrl = "{{ route('antrian.stream') }}";
            eventSource = new EventSource(esUrl);

            eventSource.onopen = function() {
                console.log('SSE Connected');
            };

            eventSource.onmessage = function(e) {
                try {
                    const data = JSON.parse(e.data);
                    const queues = data.queues || [];

                    // Selalu update next queues
                    updateNextQueues(queues);

                    // Selalu update stats
                    if (data.stats) updateStats(data.stats);

                    // Selalu update callout
                    updateCallout(data.current || null);
                } catch (err) {
                    console.error('SSE parse error:', err);
                }
            };

            eventSource.onerror = function() {
                console.log('SSE disconnected, retrying in 3s...');
                // Auto reconnect setelah 3 detik
                setTimeout(function() {
                    if (eventSource) eventSource.close();
                    initSSE();
                }, 3000);
            };
        }

        // =====================================================
        // START
        // =====================================================
        initSSE();

        // Fallback: polling AJAX jika SSE tidak connect dalam 5 detik
        setTimeout(function() {
            if (document.getElementById('displayNumber').textContent === '-' ||
                document.getElementById('displayNumber').textContent === 'Memuat...') {
                console.log('SSE timeout, using AJAX fallback...');
                fetch('/sse/antrian')
                    .then(r => r.text())
                    .then(t => {
                        // Extract first data: line
                        const match = t.match(/^data: (.+)$/m);
                        if (match) {
                            const data = JSON.parse(match[1]);
                            if (data.queues) updateNextQueues(data.queues);
                            if (data.stats) updateStats(data.stats);
                            if (data.current) updateCallout(data.current);
                        }
                    })
                    .catch(e => console.log('AJAX fallback failed:', e));
            }
        }, 5000);
    </script>
@endpush
