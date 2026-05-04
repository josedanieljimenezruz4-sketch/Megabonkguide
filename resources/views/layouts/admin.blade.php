<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel | MEGABONK GUIDE')</title>
    
    <!-- CSS Globales -->
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ time() }}">
    @stack('styles')
    
    <link rel="icon" href="/iconotlabaho.webp" type="image/x-icon">
    
    <!-- Tailwind CSS (CDN para Admin) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Base Tailwind fixes since we might conflict with estilos.css */
        *, ::before, ::after {
            border-width: 0;
            border-style: solid;
            border-color: #e5e7eb;
        }
    </style>
    
    <!-- CSS Global Atmosférico -->
    <style>
        body {
            background: radial-gradient(circle at top left, rgba(255, 0, 255, 0.05), transparent 40%),
                        radial-gradient(circle at bottom right, rgba(0, 255, 255, 0.05), transparent 40%),
                        #080808 !important;
            background-attachment: fixed !important;
            background-color: #080808 !important;
            min-height: 100vh;
            margin: 0;
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .glass-card {
            background: rgba(15, 15, 15, 0.8) !important;
            backdrop-filter: blur(10px) !important;
            -webkit-backdrop-filter: blur(10px) !important;
            border: 1px solid rgba(0, 255, 255, 0.2);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
        }

        /* Layout Structure */
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .admin-sidebar {
            width: 260px;
            background-color: #121212;
            border-right: 1px solid rgba(185, 101, 240, 0.3);
            box-shadow: 5px 0 15px rgba(0,0,0,0.5);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header h2 {
            margin: 0;
            color: #fff;
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 10px rgba(255,255,255,0.5);
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }

        .sidebar-menu li {
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: #ccc;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        /* Hover Effects per category */
        .sidebar-menu a.home:hover { color: #fff; background: rgba(255, 255, 255, 0.1); border-left: 4px solid #fff; }
        .sidebar-menu a.tierlist:hover { color: #B965F0; background: rgba(185, 101, 240, 0.1); border-left: 4px solid #B965F0; text-shadow: 0 0 8px rgba(185,101,240,0.8); }
        .sidebar-menu a.wiki:hover { color: #41E8EF; background: rgba(65, 232, 239, 0.1); border-left: 4px solid #41E8EF; text-shadow: 0 0 8px rgba(65,232,239,0.8); }
        .sidebar-menu a.leaderboard:hover { color: #F76B1C; background: rgba(247, 107, 28, 0.1); border-left: 4px solid #F76B1C; text-shadow: 0 0 8px rgba(247,107,28,0.8); }
        .sidebar-menu a.votes:hover { color: #36d1dc; background: rgba(54, 209, 220, 0.1); border-left: 4px solid #36d1dc; text-shadow: 0 0 8px rgba(54,209,220,0.8); }
        .sidebar-menu a.sugerencias:hover { color: #a8ff78; background: rgba(168, 255, 120, 0.1); border-left: 4px solid #a8ff78; text-shadow: 0 0 8px rgba(168,255,120,0.8); }
        .sidebar-menu a.users:hover { color: #ff416c; background: rgba(255, 65, 108, 0.1); border-left: 4px solid #ff416c; text-shadow: 0 0 8px rgba(255,65,108,0.8); }
        .sidebar-menu a.home-site:hover { color: #fff; background: rgba(255, 255, 255, 0.1); border-left: 4px solid #fff; }

        .sidebar-menu a i {
            margin-right: 15px;
            font-style: normal;
            font-size: 1.2rem;
        }

        /* Main Content */
        .admin-main {
            flex-grow: 1;
            margin-left: 260px; /* Same as sidebar width */
            padding: 30px;
        }

        .admin-topbar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .admin-topbar .user-info {
            background: rgba(0,0,0,0.5);
            padding: 10px 20px;
            border-radius: 20px;
            border: 1px solid #333;
            font-size: 0.9rem;
        }

    </style>
</head>
<body>

    <div class="admin-wrapper">
        
        <!-- Sidebar -->
        <x-admin.sidebar />

        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-topbar">
                <div class="user-info">
                    Hola, <strong style="color: #41E8EF;">{{ auth()->user()->username ?? auth()->user()->name }}</strong>
                </div>
            </div>

            <!-- CONTENIDO DINÁMICO -->
            @yield('content')
            
        </main>
    </div>

    @stack('scripts')
</body>
</html>
