<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class AntrianController extends Controller
{
    // ============================================
    // HALAMAN GUEST
    // ============================================
    public function guest()
    {
        return view('guest.index');
    }

    // ============================================
    // SIMPAN ANTRIAN BARU (POST)
    // ============================================
    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'layanan' => 'required|in:umum,prioritas,bisnis',
        ]);

        // Generate nomor antrian hari ini (format: A001, A002, ...)
        $lastNomor = Antrian::today()->max('nomor');
        $lastNumber = $lastNomor ? (int) substr($lastNomor, 1) : 0;
        $newNomor = 'A' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        $antrian = Antrian::create([
            'nomor'   => $newNomor,
            'nama'    => $request->nama,
            'no_hp'   => $request->no_hp,
            'layanan' => $request->layanan,
            'status'  => 'waiting',
        ]);

        return response()->json([
            'success' => true,
            'nomor'   => $antrian->nomor,
            'nama'    => $antrian->nama,
            'layanan' => $antrian->layanan,
            'message' => "Berhasil! Nomor antrian Anda adalah {$antrian->nomor}",
        ]);
    }

    // ============================================
    // HALAMAN ADMIN
    // ============================================
    public function admin()
    {
        return view('admin.index');
    }

    // ============================================
    // PANGGIL NOMOR ANTRIAN
    // ============================================
    public function call(Request $request, $id)
    {
        // 1. Tandai semua yang sedang 'called' jadi 'missed'
        Antrian::where('status', 'called')->update(['status' => 'missed']);

        // 2. Ambil antrian yang akan dipanggil
        $antrian = Antrian::find($id);

        if (!$antrian) {
            return response()->json(['success' => false, 'message' => 'Antrian tidak ditemukan']);
        }

        if ($antrian->status !== 'waiting') {
            return response()->json(['success' => false, 'message' => 'Antrian sudah tidak waiting']);
        }

        // 3. Update status jadi 'called'
        $antrian->update(['status' => 'called']);

        // 4. Simpan ke cache agar SSE bisa baca
        Cache::put('current_queue', [
            'id'      => $antrian->id,
            'nomor'   => $antrian->nomor,
            'nama'    => $antrian->nama,
            'layanan' => $antrian->layanan,
            'called_at' => now()->toDateTimeString(),
        ]);

        return response()->json([
            'success' => true,
            'nomor'   => $antrian->nomor,
            'nama'    => $antrian->nama,
        ]);
    }

    // ============================================
    // SELESAIKAN ANTRIAN (DONE)
    // ============================================
    public function done($id)
    {
        $antrian = Antrian::find($id);

        if (!$antrian) {
            return response()->json(['success' => false, 'message' => 'Antrian tidak ditemukan']);
        }

        $antrian->update(['status' => 'done']);

        // Clear cache kalau ini antrian yang sedang dipanggil
        $current = Cache::get('current_queue');
        if ($current && $current['id'] == $id) {
            Cache::forget('current_queue');
        }

        return response()->json(['success' => true]);
    }

    // ============================================
    // RESET SEMUA ANTRIAN (HARI INI)
    // ============================================
    public function reset()
    {
        // 1. Hapus semua antrian hari ini dari DB
        Antrian::today()->delete();

        // 2. Clear cache current queue
        Cache::forget('current_queue');

        // 3. Clear last_called_id tracking (lewat cache juga)
        Cache::forget('last_called_id');

        return response()->json(['success' => true, 'message' => 'Semua antrian hari ini telah direset']);
    }

    // ============================================
    // ENDPOINT SSE - STREAM DATA REALTIME
    // ============================================
    public function stream()
    {
        $response = Response::stream(function () {
            $lastData = '';

            while (true) {
                // Ambil data antrian hari ini
                $queues = Antrian::today()
                    ->orderBy('id', 'asc')
                    ->get()
                    ->toArray();

                $current = Cache::get('current_queue');

                $data = json_encode([
                    'queues'   => $queues,
                    'current'  => $current,
                    'stats'    => [
                        'waiting' => Antrian::today()->where('status', 'waiting')->count(),
                        'called'  => Antrian::today()->where('status', 'called')->count(),
                        'done'    => Antrian::today()->where('status', 'done')->count(),
                        'missed'  => Antrian::today()->where('status', 'missed')->count(),
                    ],
                    'timestamp' => now()->toDateTimeString(),
                ]);

                // Hanya kirim kalau ada perubahan data
                if ($data !== $lastData) {
                    echo "data: {$data}\n\n";
                    $lastData = $data;
                }

                // Keep-alive comment setiap 15 detik
                echo ": keep-alive " . time() . "\n\n";

                ob_flush();
                flush();

                // Cek koneksi client (jika断开则退出)
                if (connection_aborted()) {
                    break;
                }

                sleep(1);
            }
        }, 200, [
            'Content-Type'  => 'text/event-stream',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Connection'    => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('X-Accel-Buffering', 'no');

        return $response;
    }
}