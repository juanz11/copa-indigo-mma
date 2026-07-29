@extends('layouts.app')

@section('title', 'Mis Pagos — Copa Índigo MMA')

@section('content')
<section class="section" id="mis-registros" style="padding-top:8rem;">
    <div class="section-inner">
        <p class="section-tag">🎟️ Mi Cuenta</p>
        <h2 class="section-title">Mis <span class="accent">Registros y Pagos</span></h2>

        @if($registrations->isEmpty())
            <div class="empty-state" style="text-align:center;margin-top:2rem;color:#777;">
                <i class="fas fa-inbox" style="font-size:2rem;margin-bottom:1rem;"></i>
                <p>Aún no has realizado ningún pago.</p>
                <a href="{{ route('home') }}#entradas" class="btn-primary" style="margin-top:1rem;display:inline-block;">Comprar entrada</a>
            </div>
        @else
            <div class="table-card" style="margin-top:2rem;">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Evento</th>
                            <th>Entrada</th>
                            <th>Cant.</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registrations as $reg)
                        <tr>
                            <td>{{ $reg->id }}</td>
                            <td>Copa Índigo MMA</td>
                            <td>{{ ucfirst($reg->ticket_type) }}</td>
                            <td>{{ $reg->quantity }}</td>
                            <td style="color:var(--gold);font-weight:600;">${{ number_format($reg->total_amount, 2) }}</td>
                            <td>
                                @if($reg->status === 'approved')
                                    <span class="badge badge-approved">Aprobado</span>
                                @elseif($reg->status === 'pending')
                                    <span class="badge badge-pending">Pendiente</span>
                                @else
                                    <span class="badge badge-rejected">Rechazado</span>
                                @endif
                            </td>
                            <td style="font-size:0.8rem;color:#888;">{{ $reg->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($reg->status === 'approved')
                                    <span style="color:#1cc88a;font-size:0.85rem;"><i class="fas fa-check-circle"></i> Confirmado</span>
                                @elseif($reg->status === 'rejected')
                                    <span style="color:#e74a3b;font-size:0.85rem;"><i class="fas fa-times-circle"></i> Rechazado</span>
                                @else
                                    <span style="color:#f6c23e;font-size:0.85rem;"><i class="fas fa-clock"></i> En revisión</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination">
                    {{ $registrations->links() }}
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
