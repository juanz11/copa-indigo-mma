<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Copa Índigo MMA</title>
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
        .login-box {
            background: #1a1a1a;
            border: 1px solid rgba(212,175,55,0.2);
            border-radius: 16px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
        }
        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-logo h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            letter-spacing: 3px;
            color: #D4AF37;
        }
        .login-logo p { color: #777; font-size: 0.85rem; margin-top: 0.25rem; }
        .form-group { margin-bottom: 1.25rem; }
        .form-group label { display: block; margin-bottom: 0.4rem; font-size: 0.85rem; font-weight: 600; color: #ccc; }
        .form-group input {
            width: 100%;
            background: #111;
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s;
        }
        .form-group input:focus { border-color: #D4AF37; }
        .alert-error {
            background: rgba(231,74,59,0.15);
            border: 1px solid #e74a3b;
            color: #e74a3b;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1.25rem;
            font-size: 0.875rem;
        }
        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #D4AF37, #f0cf6a);
            color: #000;
            border: none;
            padding: 0.85rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-login:hover { transform: translateY(-1px); box-shadow: 0 4px 20px rgba(212,175,55,0.4); }
        .back-link { text-align: center; margin-top: 1.25rem; }
        .back-link a { color: #777; font-size: 0.85rem; text-decoration: none; transition: color 0.2s; }
        .back-link a:hover { color: #D4AF37; }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="login-logo">
            <h1>COPA <span style="color:#fff;">ÍNDIGO</span> MMA</h1>
         
        </div>

        @if($errors->any())
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label>Correo Electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@ejemplo.com">
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn-login"><i class="fas fa-sign-in-alt"></i> Ingresar</button>
        </form>

        <div class="back-link" style="flex-direction:column; gap:0.75rem;">
            <a href="{{ route('home') }}"><i class="fas fa-arrow-left"></i> Volver al sitio</a>
            <p style="color:#888; font-size:0.85rem;">¿No tienes una cuenta? <a href="{{ route('register') }}" style="color:#D4AF37; text-decoration:none;">Regístrate aquí</a></p>
        </div>
    </div>
</body>
</html>
