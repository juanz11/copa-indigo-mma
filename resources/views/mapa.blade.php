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
    .mesa-hotspot.reservada,
    .mesa-hotspot.ocupada { background-color: #ef4444 !important; border-color: rgba(255,255,255,0.4); cursor: not-allowed; opacity: 0.95; }
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
</style>
@endsection

@section('content')
<section class="mapa-section">
    <div class="mapa-header">
        <h1>Reserva tu Mesa</h1>
        <p>Selecciona una mesa disponible en el plano</p>
    </div>

    <div class="mapa-legend">
        <div class="legend-item"><div class="legend-dot dot-disponible"></div> Disponible</div>
        <div class="legend-item"><div class="legend-dot dot-seleccionada"></div> Seleccionada</div>
        <div class="legend-item"><div class="legend-dot dot-ocupada"></div> Reservada / Ocupada</div>
    </div>

    <div id="mapa-alert" class="mapa-alert"></div>

    <div class="mapa-container" id="mapa-container">
        <img src="{{ asset('Asientos Grises.png') }}" class="mapa-imagen" alt="Plano de mesas" onerror="this.src='https://placehold.co/1920x1080/001a4d/D4AF37?text=Plano+de+Mesas'">

        @foreach($mesas as $mesa)
            <div 
                class="mesa-hotspot {{ $mesa->estado }}"
                style="left: {{ $mesa->x }}%; top: {{ $mesa->y }}%; transform: translate(-50%, -50%) rotate({{ $mesa->rotacion }}deg);"
                data-id="{{ $mesa->id }}"
                data-numero="{{ $mesa->numero }}"
                data-estado="{{ $mesa->estado }}"
                onclick="seleccionarMesa(this)">
                {{ $mesa->numero }}
            </div>
        @endforeach
    </div>

    <form id="form-mesa" action="{{ route('mapa.reservar') }}" method="POST" onsubmit="return false;">
        @csrf
        <input type="hidden" name="mesa_id" id="input-mesa-id">
        <div class="mapa-panel">
            <div class="mapa-info">
                Mesa seleccionada: <strong id="label-mesa">Ninguna</strong>
            </div>
            <div class="mapa-actions">
                <button type="button" class="btn-mesa secondary" onclick="location.href='{{ route('home') }}'">Volver</button>
                <button type="submit" class="btn-mesa" id="btn-continuar" onclick="continuarReserva()" disabled>Continuar Registro</button>
            </div>
        </div>
    </form>
</section>
@endsection

@section('scripts')
<script>
    let mesaSeleccionadaId = null;
    const form = document.getElementById('form-mesa');
    const inputMesa = document.getElementById('input-mesa-id');
    const labelMesa = document.getElementById('label-mesa');
    const btnContinuar = document.getElementById('btn-continuar');
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
        const estado = elemento.getAttribute('data-estado');
        const mesaId = elemento.getAttribute('data-id');
        const numero = elemento.getAttribute('data-numero');

        if (estado === 'ocupada' || estado === 'reservada') {
            showAlert('La mesa #' + numero + ' ya fue reservada.', 'error');
            return;
        }

        document.querySelectorAll('.mesa-hotspot.seleccionada').forEach(el => {
            if (el !== elemento) el.classList.remove('seleccionada');
        });

        if (elemento.classList.contains('seleccionada')) {
            elemento.classList.remove('seleccionada');
            mesaSeleccionadaId = null;
            inputMesa.value = '';
            labelMesa.innerText = 'Ninguna';
            btnContinuar.disabled = true;
            hideAlert();
        } else {
            elemento.classList.add('seleccionada');
            mesaSeleccionadaId = mesaId;
            inputMesa.value = mesaId;
            labelMesa.innerText = '#' + numero;
            btnContinuar.disabled = false;
            hideAlert();
        }
    }

    async function continuarReserva() {
        if (!mesaSeleccionadaId) {
            showAlert('Selecciona una mesa disponible.', 'error');
            return;
        }

        btnContinuar.disabled = true;
        btnContinuar.innerText = 'Reservando...';

        const formData = new FormData(form);

        try {
            const res = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'Accept': 'application/json' }
            });

            const data = await res.json();

            if (data.success) {
                showAlert(data.message, 'success');
                window.location.href = data.redirect;
            } else {
                showAlert(data.message || 'No se pudo reservar la mesa.', 'error');
                btnContinuar.disabled = false;
                btnContinuar.innerText = 'Continuar Registro';
            }
        } catch (e) {
            showAlert('Error de conexión. Intenta de nuevo.', 'error');
            btnContinuar.disabled = false;
            btnContinuar.innerText = 'Continuar Registro';
        }
    }
</script>
@endsection
