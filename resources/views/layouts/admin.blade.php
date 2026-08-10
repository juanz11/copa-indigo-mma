<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Admin Copa Índigo MMA</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --gold: #D4AF37;
            --gold-light: #f0cf6a;
            --indigo: #4B0082;
            --sidebar-w: 260px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #0f0f0f; color: #e8e8e8; line-height: 1.6; }
        a { color: inherit; text-decoration: none; }

        .admin-wrap { display: flex; min-height: 100vh; }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-w);
            background: #111;
            border-right: 1px solid rgba(212,175,55,0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
        }
        .sidebar-logo {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(212,175,55,0.1);
        }
        .sidebar-logo h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.3rem;
            letter-spacing: 2px;
            color: var(--gold);
        }
        .sidebar-logo p { font-size: 0.75rem; color: #666; margin-top: 0.15rem; }
        .sidebar-menu { list-style: none; padding: 1rem 0; }
        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1.5rem;
            color: #888;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            color: var(--gold);
            background: rgba(212,175,55,0.07);
            border-right: 2px solid var(--gold);
        }
        .sidebar-menu li a i { width: 18px; text-align: center; }
        .badge-count {
            background: #e74a3b;
            color: #fff;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.15rem 0.45rem;
            border-radius: 999px;
            margin-left: auto;
        }
        .sidebar-divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.05);
            margin: 0.5rem 1.5rem;
        }

        /* MAIN */
        .main { flex: 1; margin-left: var(--sidebar-w); }
        .topbar {
            background: #111;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar h1 { font-size: 1.1rem; font-weight: 600; color: #fff; }
        .topbar-actions { display: flex; align-items: center; gap: 1rem; }
        .topbar-user { font-size: 0.85rem; color: #888; }
        .btn-logout {
            background: none;
            border: 1px solid rgba(255,255,255,0.1);
            color: #888;
            padding: 0.4rem 0.9rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.2s;
        }
        .btn-logout:hover { border-color: #e74a3b; color: #e74a3b; }

        .content { padding: 2rem; }

        /* ALERTS */
        .alert { padding: 0.85rem 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem; }
        .alert-success { background: rgba(28,200,138,0.1); border: 1px solid #1cc88a; color: #1cc88a; }
        .alert-error { background: rgba(231,74,59,0.1); border: 1px solid #e74a3b; color: #e74a3b; }

        /* CARDS */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .stat-card {
            background: #1a1a1a;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 10px;
            padding: 1.25rem;
        }
        .stat-card .stat-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: #666; margin-bottom: 0.5rem; }
        .stat-card .stat-value { font-size: 2rem; font-weight: 700; color: #fff; }
        .stat-card.warning .stat-value { color: #f6c23e; }
        .stat-card.success .stat-value { color: #1cc88a; }
        .stat-card.danger .stat-value { color: #e74a3b; }
        .stat-card.gold .stat-value { color: var(--gold); }

        /* TABLE CARD */
        .table-card {
            background: #1a1a1a;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 12px;
            overflow: hidden;
        }
        .table-card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .table-card-header h2 { font-size: 1rem; font-weight: 600; color: #fff; }
        table { width: 100%; border-collapse: collapse; }
        th {
            background: #111;
            padding: 0.75rem 1rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #666;
            text-align: left;
            font-weight: 600;
        }
        td {
            padding: 0.85rem 1rem;
            font-size: 0.875rem;
            color: #ccc;
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255,255,255,0.02); }

        /* BADGES */
        .badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-pending { background: rgba(246,194,62,0.15); color: #f6c23e; border: 1px solid rgba(246,194,62,0.3); }
        .badge-approved { background: rgba(28,200,138,0.15); color: #1cc88a; border: 1px solid rgba(28,200,138,0.3); }
        .badge-rejected { background: rgba(231,74,59,0.15); color: #e74a3b; border: 1px solid rgba(231,74,59,0.3); }
        .badge-general { background: rgba(255,255,255,0.08); color: #ccc; }
        .badge-vip { background: rgba(212,175,55,0.15); color: var(--gold); }
        .badge-ringside { background: rgba(0,102,255,0.15); color: #38BFFF; }

        /* ACTION BTNS */
        .action-btns { display: flex; gap: 0.4rem; }
        .btn-action {
            background: none;
            border: 1px solid rgba(255,255,255,0.1);
            color: #888;
            width: 30px; height: 30px;
            border-radius: 6px;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem;
            transition: all 0.2s;
        }
        .btn-action.approve:hover { border-color: #1cc88a; color: #1cc88a; background: rgba(28,200,138,0.1); }
        .btn-action.reject:hover { border-color: #e74a3b; color: #e74a3b; background: rgba(231,74,59,0.1); }
        .btn-action.view:hover { border-color: var(--gold); color: var(--gold); background: rgba(212,175,55,0.1); }
        .btn-action.delete:hover { border-color: #e74a3b; color: #e74a3b; background: rgba(231,74,59,0.1); }

        /* MODAL */
        .m-overlay {
            display: none;
            position: fixed; inset: 0; z-index: 9999;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(4px);
            align-items: center; justify-content: center;
        }
        .m-overlay.show { display: flex; }
        .m-box {
            background: #1a1a1a;
            border: 1px solid rgba(212,175,55,0.2);
            border-radius: 12px;
            width: 90%; max-width: 520px;
            max-height: 85vh; overflow-y: auto;
        }
        .m-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            display: flex; align-items: center; justify-content: space-between;
        }
        .m-header h3 { font-size: 1rem; font-weight: 600; color: #fff; }
        .m-close { background: none; border: none; color: #666; font-size: 1.3rem; cursor: pointer; }
        .m-close:hover { color: #fff; }
        .m-body { padding: 1.5rem; }
        .m-footer { padding: 1rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.07); display: flex; justify-content: flex-end; gap: 0.5rem; }
        .m-body .detail-row { display: flex; gap: 0.5rem; margin-bottom: 0.75rem; font-size: 0.875rem; }
        .m-body .detail-label { color: #666; min-width: 140px; font-weight: 500; }
        .m-body .detail-value { color: #ccc; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.35rem; font-size: 0.8rem; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-group textarea {
            width: 100%; background: #111; border: 1px solid rgba(255,255,255,0.1); color: #fff;
            padding: 0.65rem 0.9rem; border-radius: 8px; font-size: 0.875rem; resize: vertical;
            font-family: 'Inter', sans-serif; outline: none;
        }
        .form-group textarea:focus { border-color: var(--gold); }
        .btn-confirm { background: linear-gradient(135deg,var(--gold),var(--gold-light)); color: #000; border: none; padding: 0.65rem 1.5rem; border-radius: 8px; font-weight: 700; font-size: 0.875rem; cursor: pointer; }
        .btn-secondary { background: none; border: 1px solid rgba(255,255,255,0.1); color: #888; padding: 0.65rem 1.25rem; border-radius: 8px; cursor: pointer; font-size: 0.875rem; }
        .btn-secondary:hover { border-color: rgba(255,255,255,0.25); color: #fff; }
        .btn-danger-solid { background: #e74a3b; color: #fff; border: none; padding: 0.65rem 1.5rem; border-radius: 8px; font-weight: 700; font-size: 0.875rem; cursor: pointer; }

        /* Pagination */
        .pagination { display: flex; gap: 0.4rem; justify-content: center; padding: 1.25rem; flex-wrap: wrap; }
        .pagination span, .pagination a {
            display: inline-flex; align-items: center; justify-content: center;
            width: 34px; height: 34px; border-radius: 6px;
            font-size: 0.85rem; border: 1px solid rgba(255,255,255,0.1); color: #888;
            transition: all 0.2s;
        }
        .pagination a:hover { border-color: var(--gold); color: var(--gold); }
        .pagination [aria-current="page"] span { background: var(--gold); border-color: var(--gold); color: #000; font-weight: 700; }

        /* Empty state */
        .empty-state { text-align: center; padding: 4rem 2rem; color: #555; }
        .empty-state i { font-size: 3rem; margin-bottom: 1rem; display: block; }

        @media(max-width:768px) {
            .sidebar { transform: translateX(-100%); }
            .main { margin-left: 0; }
        }
    </style>
    @yield('styles')
</head>
<body>
<div class="admin-wrap">
    <aside class="sidebar">
        <div class="sidebar-logo">
            <h2>COPA ÍNDIGO MMA</h2>
            <p>Panel Administrativo</p>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('admin.mma.index') }}" class="{{ request()->routeIs('admin.mma.*') ? 'active' : '' }}">
                    <i class="fas fa-ticket-alt"></i> Registros
                    @if(\App\Models\MmaRegistration::pending()->count() > 0)
                        <span class="badge-count">{{ \App\Models\MmaRegistration::pending()->count() }}</span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('admin.mesas.index') }}" class="{{ request()->routeIs('admin.mesas.*') ? 'active' : '' }}">
                    <i class="fas fa-chair"></i> Disponibilidad de Mesas
                </a>
            </li>
            <hr class="sidebar-divider">
            <li>
                <a href="{{ url('/') }}">
                    <i class="fas fa-globe"></i> Ver Sitio
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="background:none;border:none;cursor:pointer;width:100%;text-align:left;display:flex;align-items:center;gap:0.75rem;padding:0.85rem 1.5rem;color:#888;font-size:0.875rem;font-weight:500;transition:color 0.2s;" onmouseover="this.style.color='#e74a3b'" onmouseout="this.style.color='#888'">
                        <i class="fas fa-sign-out-alt" style="width:18px;text-align:center;"></i> Cerrar Sesión
                    </button>
                </form>
            </li>
        </ul>
    </aside>

    <div class="main">
        <div class="topbar">
            <h1>@yield('page-title', 'Dashboard')</h1>
            <div class="topbar-actions">
                <span class="topbar-user"><i class="fas fa-user-shield" style="color:var(--gold);margin-right:0.4rem;"></i>{{ auth()->user()->name }}</span>
            </div>
        </div>
        <div class="content">
            @if(session('success'))
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
            @endif
            @yield('content')
        </div>
    </div>
</div>
@yield('scripts')
</body>
</html>
