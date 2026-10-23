<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 36px 40px; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #1c1c1c;
        }

        /* Encabezado */
        .header {
            width: 100%;
            background: #001a4d;
            border-bottom: 3px solid #D4AF37;
            padding: 14px 18px;
        }
        .header td { vertical-align: middle; }
        .header-logo { width: 62px; }
        .header-logo img { width: 56px; height: auto; }
        .header-title { color: #ffffff; }
        .header-title .brand { font-size: 19px; letter-spacing: 3px; font-weight: bold; }
        .header-title .brand .gold { color: #D4AF37; }
        .header-title .doc { font-size: 11px; color: #c9c9c9; letter-spacing: 2px; margin-top: 3px; text-transform: uppercase; }
        .header-meta { text-align: right; color: #c9c9c9; font-size: 9px; line-height: 1.5; }
        .header-meta strong { color: #D4AF37; }

        /* Tabla */
        .titulo-lista {
            font-size: 10px;
            letter-spacing: 2px;
            color: #001a4d;
            text-transform: uppercase;
            margin: 14px 0 6px;
            border-bottom: 1px solid #D4AF37;
            padding-bottom: 4px;
        }
        table.guests { width: 100%; border-collapse: collapse; }
        .guests thead th {
            background: #001a4d;
            color: #ffffff;
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 7px 8px;
            text-align: left;
            border: 1px solid #001a4d;
        }
        .guests tbody td {
            padding: 6px 8px;
            border: 1px solid #e0e0e0;
            vertical-align: middle;
        }
        .guests tbody tr:nth-child(even) td { background: #f6f4ec; }
        .nombre { font-weight: bold; color: #111; font-size: 10.5px; }
        .cedula { color: #555; }
        .mesas { font-weight: bold; color: #001a4d; }
        .detalle { color: #555; font-size: 8.5px; line-height: 1.5; }
        .num { text-align: center; }
        .total { text-align: right; font-weight: bold; color: #001a4d; }
        .estado { font-size: 8.5px; font-weight: bold; text-transform: uppercase; }
        .estado-aprobado { color: #1a7f4b; }
        .estado-pendiente { color: #b07c00; }
        .estado-rechazado { color: #b03030; }
        .ninos-tag { color: #1a7f4b; font-size: 8.5px; }

        /* Pie */
        .footer {
            position: fixed;
            bottom: -18px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #999;
            border-top: 1px solid #e0e0e0;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <table class="header">
        <tr>
            <td class="header-logo">
                @if($logo)
                    <img src="data:image/png;base64,{{ $logo }}" alt="Logo">
                @endif
            </td>
            <td class="header-title">
                <div class="brand"><span class="gold">COPA</span> ÍNDIGO <span class="gold">MMA</span></div>
                <div class="doc">Lista oficial de invitados</div>
            </td>
            <td class="header-meta">
                <strong>Sábado, 24 de Octubre — 8:00 p.m.</strong><br>
                Hotel Hesperia Valencia<br>
                Generado: {{ now()->format('d/m/Y H:i') }}
            </td>
        </tr>
    </table>

    <div class="titulo-lista">Detalle por invitado</div>

    <table class="guests">
        <thead>
            <tr>
                <th style="width:3%;">#</th>
                <th style="width:20%;">Nombre</th>
                <th style="width:10%;">Cédula</th>
                <th style="width:10%;">Teléfono</th>
                <th style="width:9%;">Mesas</th>
                <th style="width:26%;">Detalle de compra</th>
                <th style="width:5%;">Adul.</th>
                <th style="width:5%;">Niños</th>
                <th style="width:7%;">Total</th>
                <th style="width:5%;">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($guests as $g)
                <tr>
                    <td class="num">{{ $loop->iteration }}</td>
                    <td>
                        <div class="nombre">{{ $g['nombre'] }}</div>
                        @if($g['email'])<div style="color:#888;font-size:8px;">{{ $g['email'] }}</div>@endif
                    </td>
                    <td class="cedula">{{ $g['cedula'] }}</td>
                    <td>{{ $g['telefono'] }}</td>
                    <td class="mesas">{{ implode(' ', array_unique($g['mesas'])) ?: '—' }}</td>
                    <td class="detalle">{!! implode('<br>', $g['detalle']) !!}</td>
                    <td class="num">{{ $g['sillas'] }}</td>
                    <td class="num ninos-tag">{{ $g['ninos'] ?: '—' }}</td>
                    <td class="total">${{ number_format($g['total'], 2) }}</td>
                    <td>
                        @foreach(array_keys($g['estados']) as $estado)
                            <span class="estado estado-{{ $estado === 'Aprobado' ? 'aprobado' : ($estado === 'Pendiente' ? 'pendiente' : 'rechazado') }}">{{ $estado }}</span><br>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Copa Índigo MMA · En honor a David Brandt · Documento generado el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i') }}
    </div>

</body>
</html>
