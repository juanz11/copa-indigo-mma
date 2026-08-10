@extends('layouts.app')

@section('title', 'Completar Registro')

@section('styles')
<style>
    .registro-section {
        padding: 6rem 1rem 4rem;
        min-height: 100vh;
        background: radial-gradient(ellipse at top, #001a4d 0%, #0a0a0a 70%);
    }
    .registro-container {
        max-width: 720px;
        margin: 0 auto;
        background: #141414;
        border: 1px solid rgba(212,175,55,0.15);
        border-radius: 12px;
        overflow: hidden;
    }
    .registro-header {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(212,175,55,0.1);
    }
    .registro-header h1 {
        font-family: 'Bebas Neue', sans-serif;
        color: var(--gold);
        letter-spacing: 2px;
        font-size: 1.75rem;
        margin-bottom: 0.25rem;
    }
    .registro-header p { color: #aaa; }
    .resumen-box {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1rem;
        padding: 1.25rem;
        background: rgba(212,175,55,0.05);
        border-bottom: 1px solid rgba(212,175,55,0.1);
    }
    .resumen-item strong { color: var(--gold); display: block; font-size: 1.1rem; }
    .resumen-item span { color: #e0e0e0; font-size: 0.9rem; }
    .registro-body { padding: 1.5rem; }
    .form-row { display: flex; gap: 1rem; flex-wrap: wrap; }
    .form-group { flex: 1; min-width: 220px; margin-bottom: 1.25rem; }
    .form-group label { display: block; margin-bottom: 0.5rem; color: var(--gold); font-weight: 500; font-size: 0.9rem; }
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        background: #0e0e0e;
        border: 1px solid rgba(212,175,55,0.2);
        border-radius: 8px;
        color: #fff;
        font-size: 0.95rem;
        outline: none;
    }
    .form-group input:focus,
    .form-group select:focus {
        border-color: var(--gold);
    }
    .form-hint { color: #888; font-size: 0.8rem; margin-top: 0.35rem; }
    .payment-info {
        background: rgba(212,175,55,0.08);
        border: 1px solid rgba(212,175,55,0.15);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.25rem;
        color: #e0e0e0;
        font-size: 0.9rem;
    }
    #payment-details-box {
        display: none;
        background: #0e0e0e;
        border: 1px solid rgba(212,175,55,0.15);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.25rem;
        font-size: 0.9rem;
        line-height: 1.6;
    }
    .btn-submit, .btn-volver, .btn-aceptar {
        display: inline-block;
        padding: 0.85rem 1.75rem;
        border-radius: 8px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        border: none;
        font-size: 1rem;
    }
    .btn-submit { background: var(--gold); color: #000; }
    .btn-submit:disabled { opacity: 0.6; cursor: not-allowed; }
    .btn-volver { background: transparent; border: 1px solid var(--gold); color: var(--gold); margin-right: 0.75rem; }
    .alert {
        padding: 0.9rem 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        display: none;
        font-size: 0.9rem;
    }
    .alert.success { background: rgba(28,200,138,0.1); color: #1cc88a; border: 1px solid rgba(28,200,138,0.3); }
    .alert.error { background: rgba(231,74,59,0.1); color: #e74a3b; border: 1px solid rgba(231,74,59,0.3); }
    .exito-section { display: none; flex-direction: column; align-items: center; justify-content: center; min-height: 50vh; text-align: center; padding: 2rem 1rem; }
    .exito-section i { font-size: 4rem; color: #22c55e; margin-bottom: 1rem; }
    .exito-section h2 { color: var(--gold); margin-bottom: 0.5rem; }
    .exito-section p { color: #aaa; margin-bottom: 1.5rem; }
    .checklist { text-align: left; max-width: 400px; margin: 0 auto 1.5rem; color: #e0e0e0; list-style: none; font-size: 0.9rem; }
    .checklist li { margin-bottom: 0.6rem; }
    .checklist i { color: #22c55e; margin-right: 0.5rem; }
</style>
@endsection

@section('content')
<section class="registro-section">
    <div class="registro-container">
        <div class="registro-header">
            <h1>Completar Registro</h1>
            <p>Confirma tus datos y elige el método de pago</p>
        </div>

        <div class="resumen-box">
            <div class="resumen-item">
                <strong>Mesa</strong>
                <span>#{{ $numero }}</span>
            </div>
            <div class="resumen-item">
                <strong>Tipo</strong>
                <span>{{ $tipo === 'mesa_vip' ? 'Mesa VIP' : 'Mesa General' }}</span>
            </div>
            <div class="resumen-item">
                <strong>Sillas</strong>
                <span>{{ $cantidad }}</span>
            </div>
            <div class="resumen-item">
                <strong>Total</strong>
                <span>${{ number_format($total, 2) }} USD</span>
            </div>
        </div>

        <div class="registro-body" id="form-section">
            <div class="alert success" id="alert-success"></div>
            <div class="alert error" id="alert-error"></div>

            <form id="registroForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="ticket_type" value="{{ $tipo }}">
                <input type="hidden" name="quantity" value="{{ $cantidad }}">
                <input type="hidden" name="total_amount" value="{{ $total }}">
                <input type="hidden" name="mesa_id" value="{{ $mesa->id }}">

                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre y Apellido *</label>
                        <input type="text" name="full_name" placeholder="Ej: Juan Pérez" required value="{{ old('full_name', auth()->user()->name ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Cédula *</label>
                        <input type="text" name="id_number" placeholder="Ej: V-12345678" required value="{{ old('id_number', auth()->user()->id_number ?? '') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Teléfono *</label>
                        <input type="tel" name="phone" placeholder="Ej: 0424-1234567" required value="{{ old('phone', auth()->user()->phone ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Correo Electrónico</label>
                        <input type="email" name="email" placeholder="ejemplo@correo.com" value="{{ old('email', auth()->user()->email ?? '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>Instagram / Red Social</label>
                    <input type="text" name="social_media" placeholder="@usuario" value="{{ old('social_media') }}">
                </div>

                <div class="payment-info">
                    <strong>💳 Métodos de Pago Disponibles:</strong>
                    Transferencia Mercantil · Pago Móvil Mercantil · Efectivo
                </div>

                <div class="form-group">
                    <label>Método de Pago</label>
                    <select name="payment_method" id="payment_method">
                        <option value="">Selecciona un método</option>
                        <option value="transferencia" {{ old('payment_method') == 'transferencia' ? 'selected' : '' }}>Transferencia Mercantil</option>
                        <option value="pago_movil" {{ old('payment_method') == 'pago_movil' ? 'selected' : '' }}>Pago Móvil Mercantil</option>
                        <option value="efectivo" {{ old('payment_method') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                    </select>
                </div>

                <div id="payment-details-box">
                    <strong style="color:var(--gold);display:block;margin-bottom:0.75rem;"><i class="fas fa-info-circle"></i> Datos para completar tu pago</strong>
                    <div id="payment-details-content" style="color:#e0e0e0;"></div>
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

                <div style="display:flex;justify-content:flex-end;align-items:center;margin-top:1rem;">
                    <a href="{{ route('mapa.index') }}" class="btn-volver">Volver al mapa</a>
                    <button type="button" class="btn-submit" id="submitBtn" onclick="submitRegistro()">
                        <i class="fas fa-check"></i> Confirmar Registro
                    </button>
                </div>
            </form>
        </div>

        <div class="exito-section" id="exito-section">
            <i class="fas fa-check-circle"></i>
            <h2>¡Registro Exitoso!</h2>
            <p>Tu reserva fue recibida. Te contactaremos para confirmar el pago.</p>
            <ul class="checklist">
                <li><i class="fas fa-check"></i> Mesa reservada</li>
                <li><i class="fas fa-check"></i> Pago registrado</li>
                <li><i class="fas fa-check"></i> Confirmación vía WhatsApp/Email</li>
                <li><i class="fas fa-check"></i> Ticket digital al aprobar</li>
            </ul>
            <a href="{{ route('home') }}" class="btn-aceptar btn-submit" style="text-decoration:none;">Aceptar</a>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
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

        try {
            await navigator.clipboard.writeText(text);
            showToast('success', 'Copiado', 'Dato copiado al portapapeles');
        } catch (e) {
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            showToast('success', 'Copiado', 'Dato copiado al portapapeles');
        }
    }

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

    function showToast(type, title, msg) {
        const err = document.getElementById('alert-error');
        const ok = document.getElementById('alert-success');
        if (type === 'success') {
            ok.textContent = msg;
            ok.style.display = 'block';
            err.style.display = 'none';
        } else {
            err.textContent = msg;
            err.style.display = 'block';
            ok.style.display = 'none';
        }
    }

    function submitRegistro() {
        const btn = document.getElementById('submitBtn');
        const form = document.getElementById('registroForm');
        const ok = document.getElementById('alert-success');
        const err = document.getElementById('alert-error');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

        const formData = new FormData(form);

        fetch('{{ route("mma.register") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': formData.get('_token'), 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('form-section').style.display = 'none';
                document.getElementById('exito-section').style.display = 'flex';
                ok.textContent = data.message;
                ok.style.display = 'block';
            } else {
                err.textContent = data.message || 'Error al procesar el registro.';
                err.style.display = 'block';
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check"></i> Confirmar Registro';
            }
        })
        .catch(() => {
            err.textContent = 'Error de conexión. Por favor, intenta nuevamente.';
            err.style.display = 'block';
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check"></i> Confirmar Registro';
        });
    }
</script>
@endsection
