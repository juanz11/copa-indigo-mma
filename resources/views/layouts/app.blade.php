<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Copa Índigo MMA') — Copa Índigo MMA</title>
    <meta name="description" content="1era Edición Copa Índigo MMA — Valencia, 24 de Octubre. En honor a David Brandt.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --indigo: #0066FF;
            --indigo-light: #38BFFF;
            --indigo-dark: #001a4d;
            --gold: #D4AF37;
            --gold-light: #f0cf6a;
            --dark: #0a0a0a;
            --dark-2: #111111;
            --dark-3: #1a1a1a;
            --text: #e8e8e8;
            --text-muted: #999;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--dark);
            color: var(--text);
            line-height: 1.6;
        }
        a { color: inherit; text-decoration: none; }

        /* NAV */
        nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 999;
            background: rgba(10,10,10,0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(212,175,55,0.15);
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
        }
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
        }
        .nav-brand-img {
            height: 44px;
            width: auto;
            filter: drop-shadow(0 0 8px rgba(212,175,55,0.5));
            transition: filter 0.3s;
        }
        .nav-brand:hover .nav-brand-img {
            filter: drop-shadow(0 0 14px rgba(212,175,55,0.9));
        }
        .nav-brand-text {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.5rem;
            letter-spacing: 2px;
            color: var(--gold);
            line-height: 1;
        }
        .nav-brand-text span { color: #fff; }
        .nav-yt {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            color: #ff4444 !important;
            font-weight: 700 !important;
            font-size: 0.875rem;
            transition: color 0.2s, text-shadow 0.2s !important;
        }
        .nav-yt:hover { color: #ff2222 !important; text-shadow: 0 0 10px rgba(255,68,68,0.5); }
        .nav-yt i { font-size: 1.1rem; }
        .nav-links { display: flex; gap: 2rem; align-items: center; }
        .nav-links a {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-muted);
            transition: color 0.2s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .nav-links a:hover { color: var(--gold); }
        .nav-cta {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: #000 !important;
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            font-weight: 700 !important;
            transition: transform 0.2s, box-shadow 0.2s !important;
        }
        .nav-cta:hover { transform: translateY(-1px); box-shadow: 0 4px 20px rgba(212,175,55,0.4); }

        /* FOOTER */
        footer {
            background: var(--dark-2);
            border-top: 1px solid rgba(212,175,55,0.15);
            padding: 3rem 2rem 2rem;
            text-align: center;
        }
        .footer-logo-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        .footer-logo-img {
            height: 80px;
            width: auto;
            filter: drop-shadow(0 0 12px rgba(212,175,55,0.4));
        }
        footer .footer-logo {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            color: var(--gold);
            letter-spacing: 3px;
        }
        footer p { color: var(--text-muted); font-size: 0.875rem; }
        footer .footer-links {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            margin: 1rem 0;
            flex-wrap: wrap;
        }
        footer .footer-links a { color: var(--text-muted); font-size: 0.875rem; transition: color 0.2s; }
        footer .footer-links a:hover { color: var(--gold); }
        footer .footer-divider { border: none; border-top: 1px solid rgba(255,255,255,0.08); margin: 1.5rem 0; }
        footer .social-icons { display: flex; gap: 1rem; justify-content: center; margin-top: 1rem; }
        footer .social-icons a {
            width: 38px; height: 38px;
            border-radius: 50%;
            border: 1px solid rgba(212,175,55,0.3);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted);
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        footer .social-icons a:hover { background: var(--gold); color: #000; border-color: var(--gold); }

        /* BUTTONS */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: #000;
            font-weight: 700;
            padding: 0.9rem 2rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            transition: transform 0.2s, box-shadow 0.2s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(212,175,55,0.4); }
        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: transparent;
            color: var(--gold);
            font-weight: 600;
            padding: 0.85rem 2rem;
            border-radius: 8px;
            border: 2px solid var(--gold);
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.2s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-outline:hover { background: var(--gold); color: #000; }

        /* ALERT */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }
        .alert-success { background: rgba(28,200,138,0.15); border: 1px solid #1cc88a; color: #1cc88a; }
        .alert-error { background: rgba(231,74,59,0.15); border: 1px solid #e74a3b; color: #e74a3b; }

        /* ===== FLOATING BUTTONS ===== */
        .floating-buttons {
            position: fixed;
            bottom: 2rem;
            right: 1.5rem;
            z-index: 9998;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.75rem;
        }
        .float-btn {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.7rem 1.1rem;
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: 700;
            text-decoration: none;
            color: #fff;
            box-shadow: 0 4px 20px rgba(0,0,0,0.4);
            transition: transform 0.2s, box-shadow 0.2s;
            white-space: nowrap;
        }
        .float-btn:hover {
            transform: translateY(-3px) scale(1.04);
            box-shadow: 0 8px 30px rgba(0,0,0,0.5);
        }
        .float-btn i { font-size: 1.3rem; }
        .float-btn-label {
            max-width: 0;
            overflow: hidden;
            opacity: 0;
            transition: max-width 0.3s ease, opacity 0.3s ease;
        }
        .float-btn:hover .float-btn-label {
            max-width: 160px;
            opacity: 1;
        }
        .float-wa  { background: linear-gradient(135deg, #25D366, #128C7E); }
        .float-yt  { background: linear-gradient(135deg, #ff4444, #cc0000); }
        .float-ig  { background: linear-gradient(135deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); }

        @yield('extra-styles')
    </style>
    @yield('styles')
</head>
<body>
    <nav>
        <a href="#inicio" class="nav-brand">
            <img src="/IMG_1257.PNG" alt="Copa Índigo MMA" class="nav-brand-img">
            <div class="nav-brand-text"><span>COPA </span>ÍNDIGO<span> MMA</span></div>
        </a>
        <div class="nav-links">
            <a href="#evento">El Evento</a>
            <a href="#galeria">Galería</a>
            <a href="#entradas">Entradas</a>
            <a href="#contacto">Contacto</a>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}"><i class="fas fa-shield-alt"></i> Admin</a>
                @else
                    <a href="{{ route('user.registrations') }}"><i class="fas fa-receipt"></i> Mis Entradas</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" style="background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:0.875rem;font-weight:500;text-transform:uppercase;letter-spacing:1px;">Salir</button>
                </form>
            @else
                <a href="{{ route('login') }}">Iniciar sesión</a>
                <a href="{{ route('register') }}">Registrarse</a>
            @endauth
            <a href="https://www.youtube.com/@copaindigomma" target="_blank" class="nav-yt"><i class="fab fa-youtube"></i> YouTube</a>
            <a href="#entradas" class="nav-cta">🎟️ Comprar</a>
        </div>
    </nav>

    @yield('content')

    <!-- FLOATING QUICK BUTTONS -->
    <div class="floating-buttons">
        <a href="https://www.youtube.com/@copaindigomma" target="_blank" class="float-btn float-yt">
            <i class="fab fa-youtube"></i>
            <span class="float-btn-label">@copaindigomma</span>
        </a>
        <a href="https://www.instagram.com/copaindigomma/" target="_blank" class="float-btn float-ig">
            <i class="fab fa-instagram"></i>
            <span class="float-btn-label">@copaindigomma</span>
        </a>
        <a href="https://wa.me/584242818836" target="_blank" class="float-btn float-wa">
            <i class="fab fa-whatsapp"></i>
            <span class="float-btn-label">Escríbenos</span>
        </a>
    </div>

    <footer>
        <div class="footer-logo-wrap">
            <img src="/IMG_0356.PNG" alt="Copa Índigo MMA" class="footer-logo-img">
            <div class="footer-logo">COPA ÍNDIGO MMA</div>
        </div>
        <p>En honor a David Brandt 💙🕊️</p>
        <div class="footer-links">
            <a href="#evento">El Evento</a>
            <a href="#entradas">Entradas</a>
            <a href="#contacto">Contacto</a>
        </div>
        <div class="social-icons">
            <a href="https://www.instagram.com/copaindigomma/" target="_blank" title="@copaindigomma"><i class="fab fa-instagram"></i></a>
            <a href="https://www.instagram.com/sncpharma" target="_blank" title="@sncpharma"><i class="fab fa-instagram"></i></a>
            <a href="https://wa.me/584242818836" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
            <a href="https://www.youtube.com/@copaindigomma" target="_blank" title="YouTube @copaindigomma" style="border-color:rgba(255,68,68,0.4);color:#ff4444;"><i class="fab fa-youtube"></i></a>
        </div>
        <hr class="footer-divider">
        <p>Organización y Producción: <strong>@julio_brandt</strong> · Patrocinador: <strong>@sncpharma</strong></p>
        <p style="margin-top:0.5rem;">© {{ date('Y') }} Copa Índigo MMA — Todos los derechos reservados.</p>
    </footer>

    @yield('scripts')
</body>
</html>
