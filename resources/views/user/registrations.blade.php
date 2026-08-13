@extends('layouts.app')

@section('title', 'Mis Pagos — Copa Índigo MMA')

@section('styles')
<style>
    .registrations-section {
        padding: 8rem 1rem 4rem;
        min-height: 100vh;
        background: radial-gradient(ellipse at top, #001a4d 0%, #0a0a0a 70%);
    }
    .registrations-inner { max-width: 1200px; margin: 0 auto; }
    .registrations-header { text-align: center; margin-bottom: 2.5rem; }
    .registrations-header .tag { color: var(--gold); font-weight: 600; letter-spacing: 1px; font-size: 0.85rem; margin-bottom: 0.5rem; }
    .registrations-header h1 { font-family: 'Bebas Neue', sans-serif; color: #fff; font-size: 2.2rem; letter-spacing: 2px; }
    .registrations-header h1 span { color: var(--gold); }
    .ticket-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 1.5rem; }
    .ticket-card { background: #141414; border: 1px solid rgba(212,175,55,0.12); border-radius: 16px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.35); display: flex; flex-direction: column; }
    .ticket-card-header { background: linear-gradient(135deg, rgba(212,175,55,0.15), rgba(212,175,55,0.05)); padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(212,175,55,0.1); }
    .ticket-event { font-family: 'Bebas Neue', sans-serif; color: var(--gold); letter-spacing: 1.5px; font-size: 1.3rem; }
    .ticket-status { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
    .status-approved { background: rgba(28,200,138,0.12); color: #1cc88a; border: 1px solid rgba(28,200,138,0.25); }
    .status-pending { background: rgba(246,194,62,0.12); color: #f6c23e; border: 1px solid rgba(246,194,62,0.25); }
    .status-rejected { background: rgba(231,74,59,0.12); color: #e74a3b; border: 1px solid rgba(231,74,59,0.25); }
    .ticket-card-body { padding: 1.5rem; flex: 1; }
    .ticket-name { color: #fff; font-size: 1.15rem; font-weight: 700; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.05); }
    .ticket-meta { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem 1.5rem; }
    .ticket-meta div { display: flex; flex-direction: column; gap: 0.25rem; }
    .ticket-meta span { color: #888; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .ticket-meta strong { color: var(--gold); font-weight: 600; font-size: 0.95rem; }
    .ticket-stub { background: #0e0e0e; border-top: 2px dashed rgba(255,255,255,0.08); padding: 1.25rem 1.5rem; text-align: center; }
    .qr-thumb { width: 110px; height: 110px; background: #fff; border-radius: 8px; padding: 0.5rem; margin-bottom: 0.75rem; }
    .btn-ticket { display: inline-flex; align-items: center; gap: 0.5rem; background: var(--gold); color: #000; padding: 0.65rem 1.25rem; border-radius: 8px; font-weight: 700; text-decoration: none; }
    .stub-msg { color: #888; font-size: 0.85rem; margin: 0; }
    .empty-state-card { text-align: center; background: #141414; border: 1px solid rgba(212,175,55,0.12); border-radius: 16px; padding: 3rem 1rem; color: #888; max-width: 480px; margin: 0 auto; }
    .empty-state-card i { font-size: 2.5rem; margin-bottom: 1rem; color: var(--gold); }
    .empty-state-card h2 { color: #fff; font-size: 1.25rem; margin-bottom: 0.5rem; }
    .pagination { display: flex; justify-content: center; margin-top: 2.5rem; }
</style>
@endsection

@section('content')
<section class="registrations-section">
    <div class="registrations-inner">
        <div class="registrations-header">
            <p class="tag">🎟️ Mi Cuenta</p>
            <h1>Mis <span>Registros y Pagos</span></h1>
        </div>

        @if($registrations->isEmpty())
            <div class="empty-state-card">
                <i class="fas fa-inbox"></i>
                <h2>Aún no has realizado ningún pago</h2>
                <p>Compra tus entradas o reserva tu mesa para el evento.</p>
                <a href="{{ route('home') }}#entradas" class="btn-ticket" style="margin-top:1.25rem;">Comprar entrada</a>
            </div>
        @else
            <div class="ticket-grid">
                @foreach($registrations as $reg)
                    @php
                        $typeMap = [
                            'mesa_general' => 'Mesa General',
                            'mesa_vip' => 'Mesa VIP',
                            'mesa' => 'Mesa',
                            'general' => 'Entrada General',
                            'vip' => 'Entrada VIP',
                            'ringside' => 'Ringside',
                        ];
                        $typeLabel = $typeMap[$reg->ticket_type] ?? ucfirst(str_replace('_', ' ', $reg->ticket_type));
                        $statusLabel = $reg->status === 'approved' ? 'Aprobado' : ($reg->status === 'pending' ? 'Pendiente' : 'Rechazado');
                    @endphp
                    <article class="ticket-card">
                        <div class="ticket-card-header">
                            <span class="ticket-event">Copa Índigo MMA</span>
                            <span class="ticket-status status-{{ $reg->status }}">{{ $statusLabel }}</span>
                        </div>
                        <div class="ticket-card-body">
                            <div class="ticket-name">{{ $reg->full_name }}</div>
                            <div class="ticket-meta">
                                <div>
                                    <span>Entrada</span>
                                    <strong>{{ $typeLabel }}</strong>
                                </div>
                                <div>
                                    <span>Cantidad</span>
                                    <strong>{{ $reg->quantity }}</strong>
                                </div>
                                <div>
                                    <span>Total</span>
                                    <strong>{{ number_format($reg->total_amount, 2) }} USD</strong>
                                </div>
                                <div>
                                    <span>Fecha</span>
                                    <strong>{{ $reg->created_at->format('d/m/Y H:i') }}</strong>
                                </div>
                                @if($reg->mesa)
                                    <div>
                                        <span>Mesa</span>
                                        <strong>#{{ $reg->mesa->numero }}</strong>
                                    </div>
                                @endif
                                <div>
                                    <span>Método de pago</span>
                                    <strong>{{ $reg->payment_method ? ucfirst($reg->payment_method) : 'N/A' }}</strong>
                                </div>
                                <div>
                                    <span>Referencia</span>
                                    <strong>{{ $reg->payment_reference ?: 'N/A' }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="ticket-stub">
                            @if($reg->status === 'approved' && $reg->ticket_token)
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('ticket.show', $reg->ticket_token)) }}" alt="QR" class="qr-thumb">
                                <a href="{{ route('ticket.show', $reg->ticket_token) }}" class="btn-ticket" target="_blank">
                                    <i class="fas fa-ticket-alt"></i> Ver Boleto Digital
                                </a>
                            @elseif($reg->status === 'approved')
                                <p class="stub-msg">Pago aprobado — boleto en proceso.</p>
                            @elseif($reg->status === 'rejected')
                                <p class="stub-msg">Pago rechazado. Contacta soporte.</p>
                            @else
                                <p class="stub-msg">Pago en revisión. Te notificaremos por correo o WhatsApp.</p>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="pagination">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
