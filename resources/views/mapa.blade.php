@extends('layouts.app')

@section('title', 'Reservar Mesa')

@section('styles')
<style>
    .mapa-section {
        padding: 6rem 1rem 4rem;
        min-height: 100vh;
        background: radial-gradient(ellipse at top, #001a4d 0%, #0a0a0a 70%);
    }
    .mapa-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .mapa-header h1 {
        font-family: 'Bebas Neue', sans-serif;
        color: var(--gold);
        letter-spacing: 2px;
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    .mapa-header p { color: #aaa; }
    .mapa-legend {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .legend-item { display: flex; align-items: center; gap: 0.4rem; color: #ccc; font-size: 0.85rem; }
    .legend-dot { width: 16px; height: 16px; border-radius: 4px; }
    .dot-disponible { background: rgba(128,128,128,0.7); }
    .dot-seleccionada { background: #22c55e; }
    .dot-reservada { background: #f59e0b; }
    .dot-ocupada { background: #ef4444; }
    .mapa-container {
        position: relative;
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        background: #141414;
        border: 1px solid rgba(212,175,55,0.15);
        border-radius: 12px;
        overflow: hidden;
    }
    .mapa-imagen {
        width: 100%;
        height: auto;
        display: block;
    }
    .mesa-hotspot {
        position: absolute;
        width: 3.6%;
        height: 4.8%;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 12px;
        color: #fff;
        text-shadow: 0 1px 2px rgba(0,0,0,0.8);
        transition: all 0.2s ease;
        transform: translate(-50%, -50%);
        border: 2px solid rgba(255,255,255,0.5);
        user-select: none;
    }
    .mesa-hotspot.disponible { background-color: rgba(100, 116, 139, 0.85); }
    .mesa-hotspot.disponible:hover { background-color: #22c55e; transform: translate(-50%, -50%) scale(1.2); box-shadow: 0 0 10px rgba(34,197,94,0.6); }
    .mesa-hotspot.seleccionada { background-color: #22c55e !important; border-color: #fff; box-shadow: 0 0 14px rgba(34, 197, 94, 0.9); transform: translate(-50%, -50%) scale(1.15); }
    .mesa-hotspot.reservada {
        background-color: #f59e0b !important;
        border-color: rgba(255,255,255,0.4);
        cursor: pointer;
        opacity: 0.95;
    }
    .mesa-hotspot.reservada:hover {
        background-color: #fbbf24 !important;
        transform: translate(-50%, -50%) scale(1.15);
        box-shadow: 0 0 10px rgba(245, 158, 11, 0.6);
    }
    .mesa-hotspot.ocupada {
        background-color: #ef4444 !important;
        border-color: rgba(255,255,255,0.4);
        cursor: not-allowed;
        opacity: 0.95;
    }
    .mapa-panel {
        position: sticky;
        bottom: 0;
        background: #141414;
        border-top: 1px solid rgba(212,175,55,0.15);
        padding: 1.25rem;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        max-width: 1200px;
        margin: 0 auto;
        margin-top: -1px;
    }
    .mapa-info { color: #e0e0e0; }
    .mapa-info strong { color: var(--gold); }
    .mapa-actions { display: flex; gap: 0.75rem; }
    .btn-mesa {
        background: var(--gold);
        color: #000;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-weight: 700;
        cursor: pointer;
        transition: opacity 0.2s;
    }
    .btn-mesa:disabled { opacity: 0.5; cursor: not-allowed; }
    .btn-mesa.secondary { background: transparent; border: 1px solid var(--gold); color: var(--gold); }
    .mapa-alert {
        padding: 0.75rem 1rem;
        border-radius: 6px;
        display: none;
        margin: 1rem auto;
        max-width: 1200px;
        text-align: center;
    }
    .mapa-alert.error { background: rgba(231,74,59,0.1); color: #e74a3b; border: 1px solid rgba(231,74,59,0.3); }
    .mapa-alert.success { background: rgba(28,200,138,0.1); color: #1cc88a; border: 1px solid rgba(28,200,138,0.3); }

    .mapa-grid {
        display: grid;
        grid-template-columns: 340px 1fr;
        gap: 1.5rem;
        max-width: 1400px;
        margin: 0 auto;
        align-items: start;
    }
    .mapa-sidebar {
        background: #141414;
        border: 1px solid rgba(212,175,55,0.15);
        border-radius: 12px;
        padding: 1.25rem;
        position: sticky;
        top: 1rem;
    }
    .mapa-sidebar h2 {
        font-family: 'Bebas Neue', sans-serif;
        color: var(--gold);
        font-size: 1.5rem;
        margin-bottom: 1rem;
        letter-spacing: 1px;
    }
    .mapa-sidebar .empty-state { color: #888; font-size: 0.9rem; text-align: center; margin: 2rem 0; }
    .selector-tipo { display: flex; flex-direction: column; gap: 0.6rem; margin-bottom: 1rem; }
    .selector-tipo label { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; color: #e0e0e0; font-size: 0.9rem; }
    .cantidad-control { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem; }
    .cantidad-control button {
        width: 38px; height: 38px; border: 1px solid var(--gold); background: transparent; color: var(--gold); border-radius: 6px; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;
    }
    .cantidad-control input { width: 60px; text-align: center; padding: 0.5rem; border-radius: 6px; background: #0e0e0e; border: 1px solid rgba(212,175,55,0.2); color: #fff; font-size: 1rem; }
    .info-sillas { color: #aaa; font-size: 0.85rem; margin-bottom: 1rem; }
    .info-sillas strong { color: var(--gold); }
    .btn-full { width: 100%; margin-bottom: 1rem; }
    .total-box { display: flex; justify-content: space-between; align-items: center; background: rgba(212,175,55,0.08); padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; color: #e0e0e0; }
    .total-box .amount { color: var(--gold); font-weight: 700; font-size: 1.2rem; }
    .btn-continuar { display: inline-block; width: 100%; text-align: center; text-decoration: none; }
    .btn-mesa:disabled { opacity: 0.5; cursor: not-allowed; }

    @media (max-width: 900px) {
        .mapa-grid { grid-template-columns: 1fr; }
        .mapa-sidebar { position: static; }
    }
    .tipo-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-top: 1.5rem; }
    .tipo-box { border-radius: 10px; padding: 1rem; text-align: center; }
    .tipo-vip { background: rgba(128,128,128,0.1); border: 1px solid rgba(255,255,255,0.15); }
    .tipo-general { background: rgba(128,128,128,0.1); border: 1px solid rgba(255,255,255,0.15); }
    .tipo-label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; }
    .tipo-vip .tipo-label { color: #fff; }
    .tipo-general .tipo-label { color: #aaa; }
    .tipo-mesas { font-size: clamp(0.85rem, 2.5vw, 1.05rem); color: #fff; font-weight: 700; margin: 0.25rem 0; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .tipo-precio { font-size: clamp(0.75rem, 2.2vw, 0.85rem); color: #aaa; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .mesa-item { background: #0e0e0e; border: 1px solid rgba(212,175,55,0.15); border-radius: 8px; padding: 0.75rem; margin-bottom: 0.75rem; }
    .mesa-item-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem; color: #e0e0e0; font-size: 0.95rem; }
    .mesa-tag { font-size: 0.68rem; text-transform: uppercase; letter-spacing: 1px; color: #aaa; border: 1px solid rgba(255,255,255,0.15); border-radius: 4px; padding: 0.1rem 0.4rem; margin-left: 0.35rem; }
    .mesa-tag.vip { color: var(--gold); border-color: rgba(212,175,55,0.4); }
    .mesa-remove { background: transparent; border: none; color: #e74a3b; font-size: 1.3rem; cursor: pointer; line-height: 1; padding: 0 0.25rem; }
    .mesa-subtotal { margin-left: auto; color: var(--gold); font-weight: 700; font-size: 0.95rem; white-space: nowrap; }
    @media (max-width: 480px) {
        .tipo-grid { grid-template-columns: 1fr; }
        .tipo-box { padding: 0.75rem; }
        .tipo-mesas { font-size: 0.95rem; }
    }
</style>
@endsection

@section('content')
<section class="mapa-section">
    <div class="mapa-header">
        <h1>Reserva tus Mesas</h1>
        <p>Selecciona una o más mesas disponibles en el plano. El mapa siempre está a la derecha.</p>
    </div>

    <div class="mapa-legend">
        <div class="legend-item"><div class="legend-dot dot-disponible"></div> Disponible</div>
        <div class="legend-item"><div class="legend-dot dot-seleccionada"></div> Seleccionada</div>
        <div class="legend-item"><div class="legend-dot dot-reservada"></div> Parcialmente Vendida</div>
        <div class="legend-item"><div class="legend-dot dot-ocupada"></div> Ocupada</div>
    </div>

    <div id="mapa-alert" class="mapa-alert"></div>

    <div class="mapa-grid">
        <aside class="mapa-sidebar">
            <h2>Tu Selección</h2>
            <div id="panel-seleccion">
                <p class="empty-state">Haz clic en una o más mesas del plano para empezar.</p>

                <div class="tipo-grid">
                    <div class="tipo-box tipo-vip">
                        <div class="tipo-label">VIP</div>
                        <div class="tipo-mesas">Mesas 1 — 14</div>
                        <div class="tipo-precio">60 USD / silla</div>
                    </div>
                    <div class="tipo-box tipo-general">
                        <div class="tipo-label">General</div>
                        <div class="tipo-mesas">Mesas 15 — 25</div>
                        <div class="tipo-precio">50 USD / silla</div>
                    </div>
                </div>
            </div>
        </aside>

        <div class="mapa-container" id="mapa-container">
            <img src="{{ asset('Asientos Grises.png') }}" class="mapa-imagen" alt="Plano de mesas" onerror="this.src='https://placehold.co/1920x1080/001a4d/D4AF37?text=Plano+de+Mesas'">

            @foreach($mesas as $mesa)
                <div
                    class="mesa-hotspot {{ $mesa->estado }}"
                    style="left: {{ $mesa->x }}%; top: {{ $mesa->y }}%; transform: translate(-50%, -50%) rotate({{ $mesa->rotacion }}deg);"
                    data-id="{{ $mesa->id }}"
                    data-numero="{{ $mesa->numero }}"
                    data-estado="{{ $mesa->estado }}"
                    data-capacidad="{{ (int) $mesa->capacidad }}"
                    data-vendidas="{{ (int) ($mesa->registrations_sum_quantity ?? 0) }}"
                    onclick="seleccionarMesa(this)">
                    {{ $mesa->numero }}
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    const seleccionadas = new Map();
    const panel = document.getElementById('panel-seleccion');
    const defaultPanelHTML = panel.innerHTML;
    const alertEl = document.getElementById('mapa-alert');

    function showAlert(msg, type) {
        alertEl.textContent = msg;
        alertEl.className = 'mapa-alert ' + (type || '');
        alertEl.style.display = 'block';
    }

    function hideAlert() {
        alertEl.style.display = 'none';
    }

    function seleccionarMesa(elemento) {
        const mesaId = elemento.getAttribute('data-id');
        const numero = elemento.getAttribute('data-numero');
        const vendidas = parseInt(elemento.getAttribute('data-vendidas')) || 0;
        const capacidad = parseInt(elemento.getAttribute('data-capacidad')) || 8;
        const estado = elemento.getAttribute('data-estado');
        const disponibles = Math.max(0, capacidad - vendidas);

        if (estado === 'ocupada' || disponibles <= 0) {
            showAlert('La mesa #' + numero + ' está Completa.', 'error');
            return;
        }

        if (seleccionadas.has(mesaId)) {
            seleccionadas.delete(mesaId);
            elemento.classList.remove('seleccionada');
        } else {
            seleccionadas.set(mesaId, {
                id: mesaId,
                numero: numero,
                capacidad: capacidad,
                vendidas: vendidas,
                disponibles: disponibles,
                cantidad: disponibles,
                esVip: parseInt(numero) <= 14,
            });
            elemento.classList.add('seleccionada');
        }

        hideAlert();
        renderPanel();
    }

    function quitarMesa(mesaId) {
        seleccionadas.delete(mesaId);
        const el = document.querySelector('.mesa-hotspot[data-id="' + mesaId + '"]');
        if (el) el.classList.remove('seleccionada');
        renderPanel();
    }

    function cambiarCantidad(mesaId, delta) {
        const item = seleccionadas.get(mesaId);
        if (!item) return;
        item.cantidad = Math.min(Math.max(item.cantidad + delta, 1), item.disponibles);
        renderPanel();
    }

    function setCantidad(mesaId, value) {
        const item = seleccionadas.get(mesaId);
        if (!item) return;
        const v = parseInt(value) || 1;
        item.cantidad = Math.min(Math.max(v, 1), item.disponibles);
        renderPanel();
    }

    function renderPanel() {
        if (seleccionadas.size === 0) {
            panel.innerHTML = defaultPanelHTML;
            return;
        }

        let html = '';
        let total = 0;
        let totalSillas = 0;
        let hayCompletaVip = false;
        let hayCompletaGeneral = false;

        seleccionadas.forEach(item => {
            const precio = item.esVip ? 60 : 50;
            const subtotal = item.cantidad * precio;
            total += subtotal;
            totalSillas += item.cantidad;

            const esCompleta = item.vendidas === 0 && item.cantidad === item.capacidad;
            if (esCompleta && item.esVip) hayCompletaVip = true;
            if (esCompleta && !item.esVip) hayCompletaGeneral = true;

            html += `
                <div class="mesa-item">
                    <div class="mesa-item-head">
                        <span>Mesa <strong style="color:var(--gold);">#${item.numero}</strong><span class="mesa-tag ${item.esVip ? 'vip' : ''}">${item.esVip ? 'VIP' : 'General'}</span></span>
                        <button type="button" class="mesa-remove" onclick="quitarMesa('${item.id}')" title="Quitar mesa">&times;</button>
                    </div>
                    <p class="info-sillas" style="margin-bottom:0.4rem;">$${precio}/silla &middot; Disponibles: <strong>${item.disponibles}</strong>${item.vendidas > 0 ? ` &middot; Vendidas: ${item.vendidas}/${item.capacidad}` : ''}</p>
                    <div class="cantidad-control" style="margin-bottom:0;">
                        <button type="button" onclick="cambiarCantidad('${item.id}', -1)">−</button>
                        <input type="number" value="${item.cantidad}" min="1" max="${item.disponibles}" onchange="setCantidad('${item.id}', this.value)">
                        <button type="button" onclick="cambiarCantidad('${item.id}', 1)">+</button>
                        <span class="mesa-subtotal">$${subtotal.toFixed(2)}</span>
                    </div>
                </div>`;
        });

        if (hayCompletaVip || hayCompletaGeneral) {
            let promos = '';
            if (hayCompletaVip) promos += '<li>VIP: 1 Servicio Whisky + 2 Raciones de Tequeños</li>';
            if (hayCompletaGeneral) promos += '<li>General: 1 Servicio de Ron y/o Vodka + 1 Ración de Tequeños</li>';
            html += `
                <div class="promo-leyenda" style="margin-top:0.25rem;margin-bottom:0.75rem;padding:0.5rem;background:rgba(212,175,55,0.1);border:1px solid rgba(212,175,55,0.3);border-radius:6px;font-size:0.85rem;color:#e0e0e0;">
                    <strong style="color:var(--gold);display:block;margin-bottom:0.25rem;">Promoción por Mesa Completa:</strong>
                    <ul style="margin:0;padding-left:1rem;list-style:disc;">${promos}</ul>
                </div>`;
        }

        html += `
            <div class="total-box">
                <span>${seleccionadas.size} mesa(s) &middot; ${totalSillas} silla(s)</span>
                <span class="amount" id="total-display">${total.toFixed(2)} USD</span>
            </div>
            <a href="#" class="btn-mesa btn-continuar" id="btn-continuar" onclick="return continuarRegistro()">Continuar Registro</a>
        `;

        panel.innerHTML = html;
    }

    function continuarRegistro() {
        if (seleccionadas.size === 0) {
            showAlert('Selecciona al menos una mesa disponible.', 'error');
            return false;
        }

        const params = [];
        let i = 0;
        seleccionadas.forEach(item => {
            params.push(encodeURIComponent(`mesas[${i}][id]`) + '=' + encodeURIComponent(item.id));
            params.push(encodeURIComponent(`mesas[${i}][cantidad]`) + '=' + encodeURIComponent(item.cantidad));
            i++;
        });

        window.location.href = '{{ route("mma.registro") }}?' + params.join('&');
        return false;
    }

    @if(session('error'))
        showAlert(@json(session('error')), 'error');
    @endif
</script>
@endsection
