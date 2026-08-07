@extends('layouts.admin')

@section('title', 'Escaner QR — Copa Índigo MMA')
@section('page-title', '📷 Escanear QR de Entrada')

@push('styles')
<style>
    .scanner-wrap {
        max-width: 640px;
        margin: 0 auto;
    }
    .scanner-box {
        background: #141414;
        border: 1px solid rgba(212,175,55,0.2);
        border-radius: 12px;
        overflow: hidden;
        padding: 1.5rem;
    }
    #qr-reader {
        width: 100% !important;
        border: none !important;
        background: #000;
        border-radius: 8px;
    }
    #qr-reader img[alt="Info"] { display: none; }
    .scanner-controls {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1.25rem;
        align-items: center;
    }
    .scanner-status {
        margin-top: 1rem;
        padding: 1rem;
        border-radius: 8px;
        font-size: 0.95rem;
        display: none;
    }
    .scanner-status.success { background: rgba(28,200,138,0.1); color: #1cc88a; border: 1px solid rgba(28,200,138,0.3); }
    .scanner-status.error { background: rgba(231,74,59,0.1); color: #e74a3b; border: 1px solid rgba(231,74,59,0.3); }
    .manual-token {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    .manual-token input {
        flex: 1;
        background: #0f0f0f;
        border: 1px solid rgba(212,175,55,0.2);
        color: #e0e0e0;
        padding: 0.6rem 0.8rem;
        border-radius: 6px;
    }
</style>
@endpush

@section('content')
<div class="scanner-wrap">
    <div class="scanner-box">
        <p style="color:#888;margin-bottom:1rem;">
            Apunta la cámara al QR del boleto. Al escanear, se abrirá la ficha de la entrada.
        </p>

        <div id="qr-reader"></div>

        <div class="scanner-controls">
            <select id="camera-select" class="form-control" style="flex:1;min-width:200px;">
                <option value="">Seleccionar cámara</option>
            </select>
            <button class="btn btn-primary" id="start-camera" type="button">
                <i class="fas fa-play"></i> Iniciar
            </button>
            <button class="btn btn-danger" id="stop-camera" type="button" disabled>
                <i class="fas fa-stop"></i> Detener
            </button>
        </div>

        <div class="manual-token">
            <input type="text" id="manual-token" placeholder="Pegar token o URL del boleto...">
            <button class="btn btn-primary" id="open-token" type="button">
                <i class="fas fa-external-link-alt"></i> Abrir
            </button>
        </div>

        <div id="scanner-status" class="scanner-status"></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    let qrScanner = null;
    const cameraSelect = document.getElementById('camera-select');
    const startBtn = document.getElementById('start-camera');
    const stopBtn = document.getElementById('stop-camera');
    const statusEl = document.getElementById('scanner-status');
    const reader = document.getElementById('qr-reader');
    const manualInput = document.getElementById('manual-token');
    const openBtn = document.getElementById('open-token');

    function showStatus(msg, type) {
        statusEl.textContent = msg;
        statusEl.className = 'scanner-status ' + (type || '');
        statusEl.style.display = 'block';
        if (type === 'success') setTimeout(() => statusEl.style.display = 'none', 3000);
    }

    function openTicket(value) {
        if (!value) return;
        value = value.trim();

        if (value.startsWith('http://') || value.startsWith('https://')) {
            window.location.href = value;
            return;
        }

        // Token plano
        const token = value.replace(/^.*\/(entrada\/)?/, '').replace(/\?.*$/, '').replace(/\/$/, '');
        window.location.href = '{{ url('/entrada') }}/' + encodeURIComponent(token);
    }

    Html5Qrcode.getCameras().then(cameras => {
        if (cameras && cameras.length) {
            cameras.forEach((camera, i) => {
                const option = document.createElement('option');
                option.value = camera.id;
                option.text = camera.label || 'Cámara ' + (i + 1);
                cameraSelect.appendChild(option);
            });
            if (cameras.length === 1) cameraSelect.selectedIndex = 1;
        } else {
            showStatus('No se encontraron cámaras. Pega el token o URL abajo.', 'error');
        }
    }).catch(() => {
        showStatus('No se pudo acceder a las cámaras. Usa el campo manual.', 'error');
    });

    startBtn.addEventListener('click', () => {
        const cameraId = cameraSelect.value;
        if (!cameraId) {
            showStatus('Selecciona una cámara primero.', 'error');
            return;
        }

        if (qrScanner) qrScanner.stop().catch(() => {});

        qrScanner = new Html5Qrcode('qr-reader');
        qrScanner.start(
            cameraId,
            { fps: 10, qrbox: { width: 250, height: 250 } },
            decodedText => {
                showStatus('QR escaneado. Abriendo boleto...', 'success');
                qrScanner.stop().then(() => {
                    startBtn.disabled = false;
                    stopBtn.disabled = true;
                    openTicket(decodedText);
                });
            },
            () => {}
        ).then(() => {
            startBtn.disabled = true;
            stopBtn.disabled = false;
            showStatus('Cámara iniciada. Esperando QR...');
        }).catch(err => {
            showStatus('Error al iniciar cámara: ' + err.message, 'error');
        });
    });

    stopBtn.addEventListener('click', () => {
        if (qrScanner) {
            qrScanner.stop().then(() => {
                startBtn.disabled = false;
                stopBtn.disabled = true;
                statusEl.style.display = 'none';
            }).catch(() => {});
        }
    });

    openBtn.addEventListener('click', () => openTicket(manualInput.value));
    manualInput.addEventListener('keypress', e => { if (e.key === 'Enter') openTicket(manualInput.value); });
</script>
@endpush
