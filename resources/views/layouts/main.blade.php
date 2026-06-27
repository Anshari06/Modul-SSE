<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Antrian')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 1px;
        }
        .card-queue {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-queue:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }
        .btn-queue {
            border-radius: 12px;
            font-weight: 600;
            padding: 12px 30px;
            transition: all 0.3s ease;
        }
        .btn-queue:hover {
            transform: scale(1.05);
        }
        .queue-badge {
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 700;
        }
        .display-number {
            font-size: 6rem;
            font-weight: 800;
            line-height: 1;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
        }
        .sidebar {
            background: linear-gradient(180deg, #0d6efd 0%, #0b5ed7 100%);
            min-height: 100vh;
        }
        .nav-link.custom-nav {
            border-radius: 10px;
            transition: all 0.2s ease;
        }
        .nav-link.custom-nav:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }
        .nav-link.custom-nav.active {
            background-color: rgba(255, 255, 255, 0.25);
        }
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }
        .display-screen {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            min-height: 100vh;
            color: white;
        }
    </style>
    @stack('styles')
</head>
<body>

    @section('navbar')
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-megaphone-fill me-2"></i>Sistem Antrian
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/"><i class="bi bi-house-door me-1"></i>Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-1"></i>Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('display') }}"><i class="bi bi-tv me-1"></i>Layar Tampil</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @show

    <main>
        @yield('content')
    </main>

    @section('footer')
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <div class="container">
            <small>&copy; {{ date('Y') }} Sistem Antrian - Powered by SSE & Laravel</small>
        </div>
    </footer>
    @show

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    @stack('scripts')
</body>
</html>