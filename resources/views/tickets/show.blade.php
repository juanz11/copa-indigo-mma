<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boleto — Copa Índigo MMA</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(ellipse at top, #001a4d 0%, #0a0a0a 70%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .ticket {
            background: #141414;
            border: 1px solid rgba(212,175,55,0.2);
            border-radius: 16px;
            width: 100%;
            max-width: 480px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.6);
        }
        .ticket-header {
            background: linear-gradient(135deg, #001a4d, #0a0a0a);
            padding: 2rem;
            text-align: center;
        }
        .ticket-header h1 {
            font-family: 'Bebas Neue', sans-serif;
            color: #D4AF37;
            letter-spacing: 3px;
            font-size: 1.8rem;
        }
        .ticket-status {
            display: inline-block;
            margin-top: 0.75rem;
            padding: 0.35rem 1rem;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .status-approved { background: rgba(28,200,138,0.15); color: #1cc88a; border: 1px solid rgba(28,200,138,0.3); }
        .status-pending { background: rgba(246,194,62,0.15); color: #f6c23e; border: 1px solid rgba(246,194,62,0.3); }
        .status-rejected { background: rgba(231,74,59,0.15); color: #e74a3b; border: 1px solid rgba(231,74,59,0.3); }
        .ticket-body { padding: 1.75rem; color: #e0e0e0; }
        .ticket-row { display: flex; justify-content: space-between; padding: 0.85rem 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .ticket-row:last-child { border-bottom: none; }
        .ticket-label { color: #888; font-size: 0.85rem; }
        .ticket-value { color: #D4AF37; font-weight: 600; text-align: right; }
        .qr-wrap { text-align: center; padding: 1.5rem; background: #fff; }
        .qr-wrap img { max-width: 220px; height: auto; }
        .ticket-footer { padding: 1.25rem; text-align: center; font-size: 0.8rem; color: #666; background: #0f0f0f; }
        .invalid { padding: 3rem 1rem; text-align: center; }
    </style>
</head>
<body>
    @if($registration && $registration->status === 'approved')
    <div class="ticket">
        <div class="ticket-header">
            <h1>COPA ÍNDIGO MMA</h1>
            <span class="ticket-status status-approved"><i class="fas fa-check-circle"></i> Entrada válida</span>
        </div>
        <div class="ticket-body">
            <div class="ticket-row">
                <span class="ticket-label">Nombre</span>
                <span class="ticket-value">{{ $registration->full_name }}</span>
            </div>
            <div class="ticket-row">
                <span class="ticket-label">Cédula</span>
                <span class="ticket-value">{{ $registration->id_number }}</span>
            </div>
            <div class="ticket-row">
                <span class="ticket-label">Entrada</span>
                <span class="ticket-value">{{ ucfirst($registration->ticket_type) }}</span>
            </div>
            <div class="ticket-row">
                <span class="ticket-label">Cantidad</span>
                <span class="ticket-value">{{ $registration->quantity }}</span>
            </div>
            <div class="ticket-row">
                <span class="ticket-label">Total</span>
                <span class="ticket-value">${{ number_format($registration->total_amount, 2) }} USD</span>
            </div>
            <div class="ticket-row">
                <span class="ticket-label">Teléfono</span>
                <span class="ticket-value">{{ $registration->phone }}</span>
            </div>
            <div class="ticket-row">
                <span class="ticket-label">Correo</span>
                <span class="ticket-value">{{ $registration->email ?: 'N/A' }}</span>
            </div>
            <div class="ticket-row">
                <span class="ticket-label">Aprobado</span>
                <span class="ticket-value">{{ $registration->approved_at?->format('d/m/Y H:i') ?: 'N/A' }}</span>
            </div>
        </div>
        <div class="qr-wrap">
            <img src="{{ $qrImageUrl }}" alt="Código QR de la entrada">
        </div>
        <div class="ticket-footer">
            Sábado 24 de Octubre, 8:00 p.m. — Hotel Hesperia Valencia
        </div>
    </div>
    @else
    <div class="ticket">
        <div class="invalid">
            <h1 style="color:#e74a3b;font-family:'Bebas Neue',sans-serif;letter-spacing:2px;">ENTRADA NO VÁLIDA</h1>
            <p style="color:#888;margin-top:1rem;">Este boleto no existe o aún no ha sido aprobado.</p>
        </div>
    </div>
    @endif
</body>
</html>
