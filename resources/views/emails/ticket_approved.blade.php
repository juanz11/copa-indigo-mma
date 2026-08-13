<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu entrada — Copa Índigo MMA</title>
    <style>
        body { margin: 0; padding: 0; background: #0a0a0a; font-family: 'Inter', Arial, sans-serif; color: #e0e0e0; }
        .wrapper { width: 100%; max-width: 600px; margin: 0 auto; background: #141414; border: 1px solid #2a2a2a; }
        .header { background: linear-gradient(135deg, #001a4d, #0a0a0a); padding: 2rem; text-align: center; }
        .header h1 { color: #D4AF37; font-family: 'Bebas Neue', Impact, sans-serif; letter-spacing: 3px; margin: 0; font-size: 1.8rem; }
        .body { padding: 2rem; }
        h2 { color: #D4AF37; margin-top: 0; }
        .details { background: #1a1a1a; border-radius: 8px; padding: 1.25rem; margin: 1.25rem 0; border: 1px solid rgba(212,175,55,0.15); }
        .details p { margin: 0.5rem 0; font-size: 0.95rem; }
        .qr-wrap { text-align: center; margin: 1.5rem 0; }
        .qr-wrap img { background: #fff; padding: 10px; border-radius: 8px; }
        .footer { text-align: center; padding: 1.5rem; font-size: 0.8rem; color: #777; border-top: 1px solid #2a2a2a; }
        .cta { display: inline-block; margin-top: 1rem; background: #D4AF37; color: #000; padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 6px; font-weight: 700; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>COPA ÍNDIGO MMA</h1>
        </div>
        <div class="body">
            <h2>¡Tu entrada fue aprobada!</h2>
            <p>Hola <strong>{{ $registration->full_name }}</strong>,</p>
            <p>Tu pago fue verificado y tu entrada para la <strong>Copa Índigo MMA</strong> está confirmada.</p>

            <div class="details">
                <p><strong>Entrada:</strong> {{ ucfirst($registration->ticket_type) }}</p>
                <p><strong>Cantidad:</strong> {{ $registration->quantity }}</p>
                <p><strong>Total pagado:</strong> {{ number_format($registration->total_amount, 2) }} USD</p>
                <p><strong>Evento:</strong> Sábado 24 de Octubre, 8:00 p.m.</p>
                <p><strong>Lugar:</strong> Hotel Hesperia Valencia</p>
            </div>

            <p>Presenta este código QR en la entrada. También puedes abrir tu boleto digital aquí:</p>
            <div class="qr-wrap">
                <img src="{{ $qrImageUrl }}" alt="Código QR de tu entrada" width="220" height="220">
            </div>

            <p style="text-align:center;">
                <a href="{{ $ticketUrl }}" class="cta" target="_blank">Ver mi entrada</a>
            </p>

            <p style="font-size:0.85rem;color:#999;margin-top:1.5rem;">
                Si no puedes ver el QR, guarda este correo y muestra el enlace del boleto en la puerta.
            </p>
        </div>
        <div class="footer">
            Copa Índigo MMA — Una noche histórica
        </div>
    </div>
</body>
</html>
