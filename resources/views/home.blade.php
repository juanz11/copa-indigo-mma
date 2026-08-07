@extends('layouts.app')

@section('title', 'Copa Índigo MMA — 24 de Octubre, Valencia')

@section('styles')
<style>
    /* ===== HERO ===== */
    .hero {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 6rem 2rem 4rem;
        position: relative;
        overflow: hidden;
        background: #000;
    }
    .hero-bg {
        position: absolute;
        inset: 0;
        background-image: url('/IMG_0942.PNG');
        background-size: cover;
        background-position: center top;
        opacity: 0.55;
        z-index: 0;
    }
    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.65) 60%, rgba(10,10,10,1) 100%);
        z-index: 1;
    }
    .hero > * { position: relative; z-index: 2; }
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(212,175,55,0.15);
        border: 1px solid rgba(212,175,55,0.4);
        color: var(--gold);
        padding: 0.4rem 1.2rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 1.5rem;
        position: relative;
    }
    .hero h1 {
        font-family: 'Bebas Neue', sans-serif;
        font-size: clamp(3.5rem, 10vw, 8rem);
        line-height: 0.9;
        letter-spacing: 4px;
        color: #fff;
        position: relative;
        margin-bottom: 0.5rem;
    }
    .hero h1 .accent { color: var(--gold); }
    .hero-subtitle {
        font-family: 'Bebas Neue', sans-serif;
        font-size: clamp(1.5rem, 4vw, 3rem);
        letter-spacing: 6px;
        color: var(--indigo-light);
        margin-bottom: 1.5rem;
        position: relative;
    }
    .hero-desc {
        max-width: 680px;
        color: var(--text-muted);
        font-size: 1.05rem;
        line-height: 1.7;
        position: relative;
        margin-bottom: 2.5rem;
    }
    .hero-meta {
        display: flex;
        gap: 2rem;
        justify-content: center;
        flex-wrap: wrap;
        position: relative;
        margin-bottom: 2.5rem;
    }
    .hero-meta-item {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.95rem;
        font-weight: 600;
    }
    .hero-meta-item i { color: var(--gold); font-size: 1.1rem; }
    .hero-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        position: relative;
    }
    .hero-scroll {
        position: absolute;
        bottom: 2rem;
        left: 50%;
        transform: translateX(-50%);
        color: var(--text-muted);
        font-size: 0.8rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        animation: bounce 2s infinite;
    }
    @keyframes bounce {
        0%, 100% { transform: translateX(-50%) translateY(0); }
        50% { transform: translateX(-50%) translateY(6px); }
    }

    /* ===== ABOUT SECTION ===== */
    .section { padding: 5rem 2rem; }
    .section-inner { max-width: 1100px; margin: 0 auto; }
    .section-tag {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--gold);
        margin-bottom: 0.75rem;
    }
    .section-title {
        font-family: 'Bebas Neue', sans-serif;
        font-size: clamp(2rem, 5vw, 3.5rem);
        letter-spacing: 2px;
        color: #fff;
        margin-bottom: 1rem;
    }
    .section-title .accent { color: var(--gold); }
    .section-text {
        color: var(--text-muted);
        font-size: 1rem;
        line-height: 1.8;
        max-width: 700px;
    }

    /* Event info grid + flyer */
    .evento-layout {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 3rem;
        align-items: start;
    }
    @media (max-width: 900px) {
        .evento-layout { grid-template-columns: 1fr; }
        .evento-flyer { display: none; }
    }
    .evento-flyer {
        width: 220px;
        flex-shrink: 0;
    }
    .evento-flyer img {
        width: 100%;
        height: auto;
        border-radius: 16px;
        box-shadow: 0 16px 50px rgba(0,0,0,0.5), 0 0 30px rgba(0,102,255,0.2);
        border: 1px solid rgba(212,175,55,0.2);
    }
    /* Event info grid */
    .event-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-top: 3rem;
    }
    .event-card {
        background: var(--dark-3);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 12px;
        padding: 1.75rem;
        transition: border-color 0.3s, transform 0.3s;
    }
    .event-card:hover { border-color: rgba(212,175,55,0.3); transform: translateY(-3px); }
    .event-card-icon {
        font-size: 2rem;
        margin-bottom: 1rem;
    }
    .event-card h3 {
        font-size: 1rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--gold);
        margin-bottom: 0.5rem;
    }
    .event-card p { color: var(--text-muted); font-size: 0.95rem; }
    .event-card strong { color: #fff; }

    /* ===== LEGACY ===== */
    .legacy-section {
        background: linear-gradient(135deg, #001a4d 0%, #001f3f 40%, #0a0a0a 100%);
        border-top: 1px solid rgba(212,175,55,0.1);
        border-bottom: 1px solid rgba(212,175,55,0.1);
    }
    .legacy-inner {
        max-width: 1100px;
        margin: 0 auto;
        padding: 5rem 2rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
    }
    @media (max-width: 768px) {
        .legacy-inner { grid-template-columns: 1fr; gap: 2rem; }
        .legacy-flyer { order: -1; }
    }
    .legacy-flyer {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.6), 0 0 40px rgba(212,175,55,0.15);
        border: 1px solid rgba(212,175,55,0.2);
    }
    .legacy-flyer img {
        width: 100%;
        height: auto;
        display: block;
    }
    .legacy-content { text-align: left; }
    .legacy-content blockquote {
        font-size: clamp(1rem, 2.5vw, 1.35rem);
        font-style: italic;
        color: var(--text);
        line-height: 1.7;
        position: relative;
        padding: 0 0 0 2rem;
        border-left: 3px solid var(--gold);
        margin-bottom: 1.5rem;
    }
    .legacy-name {
        font-family: 'Bebas Neue', sans-serif;
        font-size: 1.8rem;
        letter-spacing: 3px;
        color: var(--gold);
        margin-top: 1rem;
    }
    .legacy-sub { color: var(--text-muted); font-size: 0.9rem; margin-top: 0.3rem; }

    /* ===== GALLERY ===== */
    .gallery-section { background: var(--dark); padding: 5rem 2rem; }
    .gallery-inner { max-width: 1100px; margin: 0 auto; }
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-top: 2.5rem;
    }
    @media (max-width: 768px) {
        .gallery-grid { grid-template-columns: 1fr; }
    }
    .gallery-item {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.06);
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
    }
    .gallery-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(212,175,55,0.2);
        border-color: rgba(212,175,55,0.3);
    }
    .gallery-item img { width: 100%; height: auto; display: block; }
    .gallery-item.tall { grid-row: span 2; }

    /* ===== TICKETS ===== */
    .tickets-section { background: var(--dark-2); }
    .tickets-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.5rem;
        margin-top: 3rem;
    }
    .ticket-card {
        background: var(--dark-3);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }
    .ticket-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, #0066FF, #38BFFF);
    }
    .ticket-card.featured { border-color: rgba(212,175,55,0.4); transform: scale(1.03); }
    .ticket-card.featured::before { background: linear-gradient(90deg, var(--gold), var(--gold-light)); }
    .ticket-card:hover { border-color: rgba(212,175,55,0.3); transform: translateY(-4px); }
    .ticket-card.featured:hover { transform: scale(1.03) translateY(-4px); }
    .ticket-badge {
        display: inline-block;
        background: linear-gradient(135deg, var(--gold), var(--gold-light));
        color: #000;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        padding: 0.25rem 0.75rem;
        border-radius: 999px;
        margin-bottom: 1rem;
    }
    .ticket-type {
        font-family: 'Bebas Neue', sans-serif;
        font-size: 1.8rem;
        letter-spacing: 2px;
        color: #fff;
        margin-bottom: 0.5rem;
    }
    .ticket-price {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--gold);
        margin-bottom: 0.25rem;
    }
    .ticket-price span { font-size: 1rem; font-weight: 400; color: var(--text-muted); }
    .ticket-perks {
        list-style: none;
        margin: 1.25rem 0 1.75rem;
        text-align: left;
    }
    .ticket-perks li {
        padding: 0.4rem 0;
        color: var(--text-muted);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .ticket-perks li i { color: var(--gold); font-size: 0.75rem; }
    .ticket-btn {
        width: 100%;
        padding: 0.85rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        border: 2px solid var(--gold);
        background: transparent;
        color: var(--gold);
        transition: all 0.2s;
    }
    .ticket-btn:hover, .ticket-card.featured .ticket-btn {
        background: linear-gradient(135deg, var(--gold), var(--gold-light));
        color: #000;
    }

    /* ===== CONTACT ===== */
    .contact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-top: 3rem;
    }
    .contact-card {
        background: var(--dark-3);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        transition: border-color 0.3s;
    }
    .contact-card:hover { border-color: rgba(212,175,55,0.3); }
    .contact-card i { font-size: 1.75rem; color: var(--gold); margin-bottom: 0.75rem; display: block; }
    .contact-card h3 { font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #fff; margin-bottom: 0.5rem; }
    .contact-card p, .contact-card a { color: var(--text-muted); font-size: 0.9rem; display: block; }
    .contact-card a:hover { color: var(--gold); }

    /* ===== MODAL ===== */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0,0,0,0.85);
        backdrop-filter: blur(6px);
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .modal-overlay.active { display: flex; }
    .modal-box {
        background: #1a1a1a;
        border: 1px solid rgba(212,175,55,0.25);
        border-radius: 16px;
        width: 100%;
        max-width: 560px;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
    }
    .modal-header {
        padding: 1.5rem 1.75rem 1rem;
        border-bottom: 1px solid rgba(255,255,255,0.07);
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        background: #1a1a1a;
        z-index: 1;
    }
    .modal-header h2 {
        font-family: 'Bebas Neue', sans-serif;
        font-size: 1.6rem;
        letter-spacing: 2px;
        color: var(--gold);
    }
    .modal-header .selected-type {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 500;
    }
    .modal-close {
        background: none;
        border: none;
        color: var(--text-muted);
        font-size: 1.5rem;
        cursor: pointer;
        line-height: 1;
        padding: 0.25rem;
        transition: color 0.2s;
    }
    .modal-close:hover { color: #fff; }
    .modal-body { padding: 1.5rem 1.75rem; }

    /* Form */
    .form-group { margin-bottom: 1.25rem; }
    .form-group label {
        display: block;
        margin-bottom: 0.4rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: #ccc;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        background: #111;
        border: 1px solid rgba(255,255,255,0.1);
        color: #fff;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        font-size: 0.95rem;
        font-family: 'Inter', sans-serif;
        transition: border-color 0.2s;
        outline: none;
    }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus { border-color: var(--gold); }
    .form-group select option { background: #1a1a1a; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .form-hint { font-size: 0.8rem; color: var(--text-muted); margin-top: 0.3rem; }
    .form-total-box {
        background: rgba(212,175,55,0.08);
        border: 1px solid rgba(212,175,55,0.25);
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .form-total-box .label { font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }
    .form-total-box .amount { font-size: 1.5rem; font-weight: 800; color: var(--gold); }

    .payment-info {
        background: rgba(0,102,255,0.1);
        border: 1px solid rgba(56,191,255,0.3);
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
        font-size: 0.85rem;
        color: #ccc;
        line-height: 1.6;
    }
    .payment-info strong { color: var(--gold); display: block; margin-bottom: 0.25rem; }

    .modal-footer {
        padding: 1rem 1.75rem 1.5rem;
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
    }
    .btn-cancel {
        background: none;
        border: 1px solid rgba(255,255,255,0.15);
        color: var(--text-muted);
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .btn-cancel:hover { border-color: rgba(255,255,255,0.3); color: #fff; }
    .btn-submit {
        background: linear-gradient(135deg, var(--gold), var(--gold-light));
        color: #000;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: transform 0.2s, box-shadow 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 4px 20px rgba(212,175,55,0.4); }
    .btn-submit:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

    /* Notification toast */
    .toast {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 99999;
        background: #1a1a1a;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        padding: 1rem 1.5rem;
        max-width: 360px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.5);
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        transform: translateX(120%);
        transition: transform 0.3s ease;
    }
    .toast.show { transform: translateX(0); }
    .toast.success { border-color: #1cc88a; }
    .toast.success .toast-icon { color: #1cc88a; }
    .toast.error { border-color: #e74a3b; }
    .toast.error .toast-icon { color: #e74a3b; }
    .toast-icon { font-size: 1.25rem; flex-shrink: 0; margin-top: 0.1rem; }
    .toast-body .toast-title { font-weight: 700; color: #fff; font-size: 0.95rem; }
    .toast-body .toast-msg { color: var(--text-muted); font-size: 0.85rem; margin-top: 0.25rem; }

    /* Sponsors bar */
    .sponsors-bar {
        background: var(--dark-2);
        border-top: 1px solid rgba(255,255,255,0.05);
        border-bottom: 1px solid rgba(255,255,255,0.05);
        padding: 1.5rem 2rem;
        text-align: center;
    }
    .sponsors-bar p { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); margin-bottom: 0.75rem; }
    .sponsors-list { display: flex; justify-content: center; gap: 2.5rem; flex-wrap: wrap; align-items: center; }
    .sponsors-list a {
        color: var(--text-muted);
        font-weight: 700;
        font-size: 0.95rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        transition: color 0.2s;
    }
    .sponsors-list a:hover { color: var(--gold); }

    @media (max-width: 768px) {
        .form-row { grid-template-columns: 1fr; }
        .hero-meta { gap: 1rem; }
        .nav-links a:not(.nav-cta):not(:last-child) { display: none; }
    }
</style>
@endsection

@section('content')

<!-- HERO -->
<section class="hero" id="inicio">
    <div class="hero-bg"></div>
    <div class="hero-badge">🥊 1era Edición · Valencia, Venezuela</div>
    <h1>COPA <span class="accent">ÍNDIGO</span><br>MMA</h1>
    <p class="hero-subtitle">Acción · Técnica · Legado</p>
    <p class="hero-desc">
        Llega a Valencia la 1era Edición de la Copa Índigo MMA, un espectáculo deportivo de alto nivel donde la adrenalina y la competencia se unen con un propósito claro: rendir homenaje al <strong>ÍNDIGO, David Brandt</strong> 💙🕊️
    </p>
    <div class="hero-meta">
        <div class="hero-meta-item"><i class="fas fa-calendar-alt"></i> Sábado, 24 de Octubre</div>
        <div class="hero-meta-item"><i class="fas fa-clock"></i> 8:00 P.M.</div>
        <div class="hero-meta-item"><i class="fas fa-map-marker-alt"></i> Hotel Hesperia Valencia</div>
    </div>
    <div class="hero-actions">
        <button class="btn-primary" onclick="openModal('vip')"><i class="fas fa-ticket-alt"></i> Asegurar mi Entrada</button>
        <a href="#evento" class="btn-outline"><i class="fas fa-info-circle"></i> Más Información</a>
    </div>
    <div class="hero-scroll"><i class="fas fa-chevron-down"></i> Descubre</div>
</section>

<!-- SPONSORS BAR -->
<div class="sponsors-bar">
    <p>Organización y Patrocinadores Oficiales</p>
    <div class="sponsors-list">
        <a href="https://instagram.com/julio_brandt" target="_blank">🔹 @julio_brandt — Fundador y Promotor</a>
        <a href="https://instagram.com/sncpharma" target="_blank">🔹 @sncpharma — Patrocinador Exclusivo</a>
        <a href="https://www.youtube.com/@copaindigomma" target="_blank" style="color:#ff4444;font-weight:700;"><i class="fab fa-youtube"></i> @copaindigomma — Canal Oficial YouTube</a>
    </div>
</div>

<!-- EL EVENTO -->
<section class="section" id="evento">
    <div class="section-inner">
        <div class="evento-layout">
            <div>
                <p class="section-tag">⚡ El Evento</p>
                <h2 class="section-title">Un evento. Un propósito.<br><span class="accent">Un legado.</span></h2>
                <p class="section-text">
                    Más que una cartelera de combates, hemos diseñado un gran evento pensado para que los verdaderos fanáticos de las MMA disfruten de un show sin precedentes. Celebraremos este deporte tal como él lo vivía: <strong>dándolo todo hasta el último segundo.</strong>
                </p>
                <div class="event-grid">
                    <div class="event-card">
                        <div class="event-card-icon">📅</div>
                        <h3>Fecha</h3>
                        <p><strong>Sábado, 24 de Octubre</strong><br>Apertura: 8:00 P.M.</p>
                    </div>
                    <div class="event-card">
                        <div class="event-card-icon">📍</div>
                        <h3>Locación</h3>
                        <p><strong>Hotel Hesperia Valencia</strong><br>@wtchesperiavalencia — Valencia, Venezuela</p>
                    </div>
                    <div class="event-card">
                        <div class="event-card-icon">🥊</div>
                        <h3>El Show</h3>
                        <p>Cartelera de combates MMA de alto nivel, entretenimiento y una noche histórica para las artes marciales mixtas.</p>
                    </div>
                    <div class="event-card">
                        <div class="event-card-icon">💙</div>
                        <h3>El Propósito</h3>
                        <p>Rendir homenaje a <strong>David Brandt — El Índigo</strong>, un guerrero que lo dio todo dentro y fuera del octágono.</p>
                    </div>
                </div>
            </div>
            <div class="evento-flyer">
                <img src="/IMG_1053.PNG" alt="Copa Índigo MMA — Flyer Oficial">
            </div>
        </div>
    </div>
</section>

<!-- LEGACY -->
<section class="legacy-section">
    <div class="legacy-inner">
        <div class="legacy-flyer">
            <img src="/IMG_1185.PNG" alt="Flyer Copa Índigo MMA — David Brandt">
        </div>
        <div class="legacy-content">
            <p class="section-tag">💙 En su memoria</p>
            <h2 class="section-title" style="margin-bottom:1.5rem;">Un homenaje que<br><span class="accent">trasciende el deporte.</span></h2>
            <blockquote>
                Más que un luchador, fue un guerrero que vivió las MMA con el alma. Este evento es para él, por él y gracias a todo lo que nos enseñó dentro del octágono.
            </blockquote>
            <p class="legacy-name">DAVID BRANDT</p>
            <p class="legacy-sub">El Índigo · Siempre en nuestros corazones 🕊️</p>
            <div style="margin-top:2rem; display:flex; gap:0.75rem; flex-wrap:wrap;">
                <span style="background:rgba(212,175,55,0.1);border:1px solid rgba(212,175,55,0.3);color:var(--gold);padding:0.4rem 1rem;border-radius:999px;font-size:0.8rem;font-weight:600;">#CopaÍndigoMMA</span>
                <span style="background:rgba(212,175,55,0.1);border:1px solid rgba(212,175,55,0.3);color:var(--gold);padding:0.4rem 1rem;border-radius:999px;font-size:0.8rem;font-weight:600;">#DavidBrandt</span>
                <span style="background:rgba(212,175,55,0.1);border:1px solid rgba(212,175,55,0.3);color:var(--gold);padding:0.4rem 1rem;border-radius:999px;font-size:0.8rem;font-weight:600;">#FundaciónDavidBrandt</span>
                <span style="background:rgba(212,175,55,0.1);border:1px solid rgba(212,175,55,0.3);color:var(--gold);padding:0.4rem 1rem;border-radius:999px;font-size:0.8rem;font-weight:600;">#SNCPHARMA</span>
            </div>
        </div>
    </div>
</section>

<!-- GALERÍA -->
<section class="gallery-section" id="galeria">
    <div class="gallery-inner">
        <div class="section-inner" style="padding:0;margin:0;max-width:100%;">
            <p class="section-tag">📸 Identidad Visual</p>
            <h2 class="section-title">El arte detrás del<br><span class="accent">evento.</span></h2>
        </div>
        <div class="gallery-grid">
            <div class="gallery-item">
                <img src="/IMG_0941.PNG" alt="Copa Índigo MMA Banner">
            </div>
            <div class="gallery-item">
                <img src="/IMG_0942.PNG" alt="Copa Índigo MMA Arena">
            </div>
            <div class="gallery-item">
                <img src="/IMG_0939.PNG" alt="Copa Índigo MMA Octágono">
            </div>
            <div class="gallery-item">
                <img src="/IMG_0955.PNG" alt="Copa Índigo MMA David Brandt">
            </div>
            <div class="gallery-item">
                <img src="/IMG_0940.PNG" alt="Copa Índigo MMA Luces">
            </div>
            <div class="gallery-item" style="display:flex;align-items:center;justify-content:center;background:var(--dark-3);border:1px solid rgba(212,175,55,0.15);border-radius:12px;padding:2rem;">
                <img src="/IMG_0292.PNG" alt="Copa Índigo MMA Logo" style="max-height:220px;width:auto;filter:drop-shadow(0 0 20px rgba(212,175,55,0.4));">
            </div>
        </div>
    </div>
</section>

<!-- ENTRADAS -->
<section class="section tickets-section" id="entradas">
    <div class="section-inner">
        <p class="section-tag">🎟️ Entradas</p>
        <h2 class="section-title">Asegura tu acceso a esta<br><span class="accent">noche histórica</span></h2>
        <p class="section-text">Elige tu tipo de entrada y vive la experiencia desde donde quieras. Cupos limitados.</p>
        <div class="tickets-grid">
            <!-- General -->
            <div class="ticket-card">
                <p class="ticket-type">General</p>
                <p class="ticket-price">$30 <span>USD / persona</span></p>
                <ul class="ticket-perks">
                    <li><i class="fas fa-check-circle"></i> Acceso al evento</li>
                    <li><i class="fas fa-check-circle"></i> Tribuna general</li>
                    <li><i class="fas fa-check-circle"></i> Experiencia MMA completa</li>
                </ul>
                <button class="ticket-btn" onclick="openModal('general')">Comprar Entrada</button>
            </div>

            <!-- VIP -->
            <div class="ticket-card featured">
                <div class="ticket-badge">⭐ Más Popular</div>
                <p class="ticket-type">VIP</p>
                <p class="ticket-price">$60 <span>USD / persona</span></p>
                <ul class="ticket-perks">
                    <li><i class="fas fa-check-circle"></i> Zona VIP preferencial</li>
                    <li><i class="fas fa-check-circle"></i> Consumición incluida</li>
                    <li><i class="fas fa-check-circle"></i> Acceso prioritario</li>
                    <li><i class="fas fa-check-circle"></i> Experiencia premium</li>
                </ul>
                <button class="ticket-btn" onclick="openModal('vip')">Comprar VIP</button>
            </div>

            <!-- Ringside -->
            <div class="ticket-card">
                <p class="ticket-type">Ringside</p>
                <p class="ticket-price">$100 <span>USD / persona</span></p>
                <ul class="ticket-perks">
                    <li><i class="fas fa-check-circle"></i> Primera fila al octágono</li>
                    <li><i class="fas fa-check-circle"></i> Zona exclusiva Ringside</li>
                    <li><i class="fas fa-check-circle"></i> Consumición premium</li>
                    <li><i class="fas fa-check-circle"></i> Meet & Greet con atletas</li>
                </ul>
                <button class="ticket-btn" onclick="openModal('ringside')">Comprar Ringside</button>
            </div>
        </div>

        <p style="text-align:center;margin-top:2rem;color:var(--text-muted);font-size:0.85rem;">
            <i class="fas fa-info-circle" style="color:var(--gold);"></i>
            Los pagos se realizan por transferencia, Pago Móvil, Zelle o efectivo. Te contactaremos para confirmar tu registro.
        </p>
    </div>
</section>

<!-- CONTACTO -->
<section class="section" id="contacto">
    <div class="section-inner">
        <p class="section-tag">📲 Contacto</p>
        <h2 class="section-title">¿Necesitas <span class="accent">más información?</span></h2>
        <p class="section-text">Contáctanos directamente y te responderemos a la brevedad.</p>
        <div class="contact-grid">
            <div class="contact-card">
                <i class="fab fa-whatsapp"></i>
                <h3>WhatsApp</h3>
                <a href="https://wa.me/584242818836" target="_blank">+58 424-2818836</a>
            </div>
            <div class="contact-card">
                <i class="fas fa-envelope"></i>
                <h3>Correo</h3>
                <a href="mailto:copaindigodavidbrandt@gmail.com">copaindigodavidbrandt@gmail.com</a>
            </div>
            <div class="contact-card">
                <i class="fab fa-instagram"></i>
                <h3>Instagram</h3>
                <a href="https://instagram.com/julio_brandt" target="_blank">@julio_brandt</a>
                <a href="https://instagram.com/sncpharma" target="_blank">@sncpharma</a>
            </div>
            <div class="contact-card">
                <i class="fab fa-youtube" style="color:#ff4444;"></i>
                <h3>YouTube</h3>
                <a href="https://www.youtube.com/@copaindigomma" target="_blank">@copaindigomma</a>
                <p style="font-size:0.8rem;margin-top:0.3rem;">Suscríbete al canal oficial</p>
            </div>
            <div class="contact-card">
                <i class="fas fa-map-marker-alt"></i>
                <h3>Lugar</h3>
                <p>Hotel Hesperia Valencia<br>Valencia, Carabobo, Venezuela</p>
            </div>
        </div>
    </div>
</section>

<!-- MODAL DE REGISTRO -->
<div class="modal-overlay" id="registroModal">
    <div class="modal-box">
        <div class="modal-header">
            <div>
                <h2>🎟️ Registro de Entrada</h2>
                <p class="selected-type" id="modalTicketLabel">Entrada General</p>
            </div>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>

        <div class="modal-body">
            <div id="modal-success" class="alert alert-success" style="display:none;"></div>
            <div id="modal-error" class="alert alert-error" style="display:none;"></div>

            <form id="registroForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="ticket_type" id="ticket_type" value="general">
                <input type="hidden" name="total_amount" id="total_amount" value="30">

                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre y Apellido *</label>
                        <input type="text" name="full_name" placeholder="Ej: Juan Pérez" required>
                    </div>
                    <div class="form-group">
                        <label>Cédula *</label>
                        <input type="text" name="id_number" placeholder="Ej: V-12345678" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Teléfono *</label>
                        <input type="tel" name="phone" placeholder="Ej: 0424-1234567" required>
                    </div>
                    <div class="form-group">
                        <label>Correo Electrónico</label>
                        <input type="email" name="email" placeholder="ejemplo@correo.com">
                    </div>
                </div>

                <div class="form-group">
                    <label>Instagram / Red Social</label>
                    <input type="text" name="social_media" placeholder="@usuario">
                </div>

                <div class="form-group">
                    <label>Cantidad de Entradas *</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="20" required>
                    <p class="form-hint" id="price-hint">Precio: $30 USD por persona</p>
                </div>

                <div class="form-total-box">
                    <span class="label">Total a Pagar</span>
                    <span class="amount" id="total-display">$30.00 USD</span>
                </div>

                <div class="payment-info">
                    <strong>💳 Métodos de Pago Disponibles:</strong>
                    Transferencia Mercantil · Pago Móvil Mercantil · Efectivo
                </div>

                <div class="form-group">
                    <label>Método de Pago</label>
                    <select name="payment_method" id="payment_method">
                        <option value="">Selecciona un método</option>
                        <option value="transferencia">Transferencia Mercantil</option>
                        <option value="pago_movil">Pago Móvil Mercantil</option>
                        <option value="efectivo">Efectivo</option>
                    </select>
                </div>

                <div id="payment-details-box" style="display:none;background:var(--dark-3);border:1px solid rgba(212,175,55,0.15);border-radius:8px;padding:1rem;margin-bottom:1.25rem;font-size:0.9rem;line-height:1.6;">
                    <strong style="color:var(--gold);display:block;margin-bottom:0.75rem;"><i class="fas fa-info-circle"></i> Datos para completar tu pago</strong>
                    <div id="payment-details-content" style="color:var(--text);"></div>
                </div>

                <div class="form-group" id="reference-group">
                    <label>Número de Referencia *</label>
                    <input type="text" name="payment_reference" id="payment_reference" placeholder="Ingresa el número de referencia" required>
                </div>

                <div class="form-group">
                    <label>Comprobante de Pago</label>
                    <input type="file" name="payment_proof" accept="image/*,.pdf">
                    <p class="form-hint">Formatos: JPG, PNG, PDF (máx. 5MB)</p>
                </div>
            </form>
        </div>

        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal()">Cancelar</button>
            <button class="btn-submit" id="submitBtn" onclick="submitForm()">
                <i class="fas fa-check"></i> Confirmar Registro
            </button>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="toast" id="toast">
    <div class="toast-icon"><i class="fas fa-check-circle"></i></div>
    <div class="toast-body">
        <p class="toast-title" id="toast-title"></p>
        <p class="toast-msg" id="toast-msg"></p>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const PRICES = { general: 30, vip: 60, ringside: 100 };
    const LABELS = { general: 'Entrada General — $30 USD', vip: 'Entrada VIP — $60 USD', ringside: 'Entrada Ringside — $100 USD' };
    const isLoggedIn = @json(auth()->check());

    let currentType = 'general';

    function openModal(type) {
        if (!isLoggedIn) {
            window.location.href = '{{ route('register') }}';
            return;
        }
        currentType = type;
        document.getElementById('ticket_type').value = type;
        document.getElementById('modalTicketLabel').textContent = LABELS[type];
        document.getElementById('price-hint').textContent = 'Precio: $' + PRICES[type] + ' USD por persona';
        updateTotal();
        document.getElementById('registroModal').classList.add('active');
        document.body.style.overflow = 'hidden';
        document.getElementById('modal-success').style.display = 'none';
        document.getElementById('modal-error').style.display = 'none';
    }

    function closeModal() {
        document.getElementById('registroModal').classList.remove('active');
        document.body.style.overflow = '';
        document.getElementById('registroForm').reset();
        document.getElementById('submitBtn').disabled = false;
    }

    function updateTotal() {
        const qty = parseInt(document.getElementById('quantity').value) || 1;
        const price = PRICES[currentType] || 30;
        const total = (qty * price).toFixed(2);
        document.getElementById('total_amount').value = total;
        document.getElementById('total-display').textContent = '$' + total + ' USD';
    }

    document.getElementById('quantity').addEventListener('input', updateTotal);

    const paymentDetails = @json(config('mma.payments'));

    function copyRow(label, value) {
        return `<div style="display:flex;justify-content:space-between;align-items:flex-start;gap:0.5rem;margin-bottom:0.4rem;" data-copy="${value}">
                    <span style="word-break:break-word;"><strong>${label}:</strong> ${value}</span>
                    <button type="button" onclick="copyField(this)" style="background:rgba(212,175,55,0.12);border:none;border-radius:4px;color:var(--gold);padding:0.2rem 0.5rem;font-size:0.75rem;cursor:pointer;white-space:nowrap;flex-shrink:0;">
                        <i class="fas fa-copy"></i> Copiar
                    </button>
                </div>`;
    }

    function renderPaymentDetails(method) {
        const box = document.getElementById('payment-details-box');
        const content = document.getElementById('payment-details-content');
        const data = paymentDetails[method];

        if (!data) {
            box.style.display = 'none';
            content.innerHTML = '';
            return;
        }

        let html = '';
        if (method === 'transferencia') {
            html = copyRow('Banco', data.bank) +
                   copyRow('Titular', data.holder) +
                   copyRow('Cuenta', data.account) +
                   copyRow('Cédula / RIF', data.id);
        } else if (method === 'pago_movil') {
            html = copyRow('Banco', data.bank) +
                   copyRow('Titular', data.holder) +
                   copyRow('Teléfono', data.phone) +
                   copyRow('Cédula / RIF', data.id);
        }

        content.innerHTML = html;
        box.style.display = 'block';
    }

    async function copyField(btn) {
        const row = btn.closest('[data-copy]');
        const text = row ? row.getAttribute('data-copy') : '';
        if (!text) return;

        const doCopy = async () => {
            try {
                await navigator.clipboard.writeText(text);
                return true;
            } catch (e) {
                const ta = document.createElement('textarea');
                ta.value = text;
                ta.style.position = 'fixed';
                ta.style.opacity = '0';
                document.body.appendChild(ta);
                ta.select();
                const ok = document.execCommand('copy');
                document.body.removeChild(ta);
                return ok;
            }
        };

        if (await doCopy()) {
            showToast('success', 'Copiado', 'Dato copiado al portapapeles');
        } else {
            showToast('error', 'Error', 'No se pudo copiar el dato');
        }
    }

    // Show/hide reference field and payment details based on payment method
    document.getElementById('payment_method').addEventListener('change', function () {
        const ref = document.getElementById('payment_reference');
        const group = document.getElementById('reference-group');
        if (this.value === 'efectivo' || this.value === '') {
            ref.required = false;
            group.style.opacity = '0.5';
        } else {
            ref.required = true;
            group.style.opacity = '1';
        }
        renderPaymentDetails(this.value);
    });

    // Close on overlay click
    document.getElementById('registroModal').addEventListener('click', function (e) {
        if (e.target === this) closeModal();
    });

    // Close on ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });

    function showToast(type, title, msg) {
        const toast = document.getElementById('toast');
        const icon = toast.querySelector('.toast-icon i');
        toast.className = 'toast ' + type;
        icon.className = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
        document.getElementById('toast-title').textContent = title;
        document.getElementById('toast-msg').textContent = msg;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 5000);
    }

    function submitForm() {
        const btn = document.getElementById('submitBtn');
        const form = document.getElementById('registroForm');
        const formData = new FormData(form);

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

        fetch('{{ route("mma.register") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': formData.get('_token'), 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                closeModal();
                showToast('success', '¡Registro exitoso!', data.message);
            } else {
                const errEl = document.getElementById('modal-error');
                errEl.textContent = data.message || 'Error al procesar el registro.';
                errEl.style.display = 'block';
                errEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check"></i> Confirmar Registro';
            }
        })
        .catch(() => {
            const errEl = document.getElementById('modal-error');
            errEl.textContent = 'Error de conexión. Por favor, intenta nuevamente.';
            errEl.style.display = 'block';
            errEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check"></i> Confirmar Registro';
        });
    }
</script>
@endsection
