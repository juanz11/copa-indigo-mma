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
</style>
@endsection

@section('content')
<section class="mapa-section">
    <div class="mapa-header">
        <h1>Reserva tu Mesa</h1>
        <p>Selecciona una mesa disponible en el plano. El mapa siempre está a la derecha.</p>
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
            <h2>Detalle de la Mesa</h2>
            <div id="panel-seleccion">
                <p class="empty-state">Haz clic en una mesa del plano para empezar.</p>
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
    let mesaSeleccionadaId = null;
    let mesaSeleccionadaNumero = null;
    const panel = document.getElementById('panel-seleccion');
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

        if (estado === 'ocupada' || vendidas >= capacidad) {
            showAlert('La mesa #' + numero + ' está completa.', 'error');
            return;
        }

        document.querySelectorAll('.mesa-hotspot.seleccionada').forEach(el => {
            if (el !== elemento) el.classList.remove('seleccionada');
        });

        if (elemento.classList.contains('seleccionada')) {
            elemento.classList.remove('seleccionada');
            mesaSeleccionadaId = null;
            mesaSeleccionadaNumero = null;
            panel.innerHTML = '<p class="empty-state">Haz clic en una mesa del plano para empezar.</p>';
            hideAlert();
        } else {
            elemento.classList.add('seleccionada');
            mesaSeleccionadaId = mesaId;
            mesaSeleccionadaNumero = numero;
            mostrarPanel(elemento);
            hideAlert();
        }
    }

    function mostrarPanel(elemento) {
        const vendidas = parseInt(elemento.getAttribute('data-vendidas')) || 0;
        const capacidad = parseInt(elemento.getAttribute('data-capacidad')) || 8;
        const disponibles = capacidad - vendidas;
        const puedeCompleta = vendidas === 0;
        const numero = elemento.getAttribute('data-numero');
        const esVip = parseInt(numero) <= 14;
        const precio = esVip ? 60 : 50;

        panel.innerHTML = `
            <div style="margin-bottom:0.75rem;">
                <span style="color:#aaa;font-size:0.9rem;">Mesa</span>
                <div style="color:var(--gold);font-size:1.5rem;font-weight:700;">#${numero}</div>
            </div>
            <p class="info-sillas">Sillas vendidas: <strong>${vendidas}/${capacidad}</strong>. Disponibles: <strong>${disponibles}</strong></p>

            <p class="info-sillas">Tipo: <strong>${esVip ? 'VIP' : 'General'}</strong> — $${precio}/silla</p>

            <label style="display:block;color:var(--gold);margin-bottom:0.5rem;font-size:0.9rem;font-weight:500;">Cantidad de Sillas</label>
            <div class="cantidad-control">
                <button type="button" onclick="cambiarCantidad(-1)">−</button>
                <input type="number" id="cantidad" value="1" min="1" max="${disponibles}" onchange="validarCantidad()">
                <button type="button" onclick="cambiarCantidad(1)">+</button>
            </div>

            <button type="button" class="btn-mesa btn-full" id="btn-completa" onclick="comprarCompleta(${disponibles})" ${puedeCompleta ? '' : 'disabled'}>Comprar mesa completa (${disponibles} sillas)</button>
            <p id="msg-completa" class="info-sillas" style="display:${puedeCompleta ? 'none' : 'block'};">No puedes comprar la mesa completa porque ya se vendieron sillas.</p>

            <div class="total-box">
                <span>Total a pagar</span>
                <span class="amount" id="total-display">$${(1 * precio).toFixed(2)} USD</span>
            </div>

            <a href="#" class="btn-mesa btn-continuar" id="btn-continuar" onclick="return continuarRegistro()">Continuar Registro</a>
        `;
    }

    function cambiarCantidad(delta) {
        const input = document.getElementById('cantidad');
        if (!input) return;
        let value = parseInt(input.value) || 1;
        const min = parseInt(input.min) || 1;
        const max = parseInt(input.max) || 8;
        value = Math.min(Math.max(value + delta, min), max);
        input.value = value;
        calcularTotal();
    }

    function validarCantidad() {
        const input = document.getElementById('cantidad');
        if (!input) return;
        const min = parseInt(input.min) || 1;
        const max = parseInt(input.max) || 8;
        let value = parseInt(input.value) || 1;
        value = Math.min(Math.max(value, min), max);
        input.value = value;
        calcularTotal();
    }

    function comprarCompleta(disponibles) {
        const input = document.getElementById('cantidad');
        if (input) input.value = disponibles;
        calcularTotal();
    }

    function calcularTotal() {
        const input = document.getElementById('cantidad');
        if (!input) return;
        const qty = parseInt(input.value) || 1;
        const esVip = parseInt(mesaSeleccionadaNumero) <= 14;
        const precio = esVip ? 60 : 50;
        const total = (qty * precio).toFixed(2);
        document.getElementById('total-display').textContent = '$' + total + ' USD';
    }

    function continuarRegistro() {
        if (!mesaSeleccionadaId) {
            showAlert('Selecciona una mesa disponible.', 'error');
            return false;
        }

        const input = document.getElementById('cantidad');
        if (!input) {
            showAlert('Indica la cantidad de sillas.', 'error');
            return false;
        }

        const cantidad = input.value;

        const base = '{{ route("mma.registro") }}';
        const url = base + '?mesa_id=' + encodeURIComponent(mesaSeleccionadaId) +
                    '&numero=' + encodeURIComponent(mesaSeleccionadaNumero) +
                    '&cantidad=' + encodeURIComponent(cantidad);

        window.location.href = url;
        return false;
    }
</script>
@endsection
