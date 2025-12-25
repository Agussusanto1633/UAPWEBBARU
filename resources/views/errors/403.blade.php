<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Denied</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .error-container { background: white; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); padding: 3rem; text-align: center; max-width: 500px; }
        .error-code { font-size: 6rem; font-weight: bold; color: #ef4444; margin-bottom: 1rem; }
        .error-title { font-size: 2rem; color: #1f2937; margin-bottom: 1rem; }
        .error-message { color: #6b7280; margin-bottom: 2rem; font-size: 1.1rem; }
        .btn { padding: 0.75rem 2rem; background: #667eea; color: white; border: none; border-radius: 6px; font-size: 1rem; text-decoration: none; display: inline-block; transition: background 0.3s; }
        .btn:hover { background: #5568d3; }
        .icon { font-size: 4rem; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="icon">🔒</div>
        <div class="error-code">403</div>
        <h1 class="error-title">Access Denied</h1>
        <p class="error-message">
            Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.<br>
            <strong>Fitur ini hanya untuk Admin.</strong>
        </p>
        <a href="{{ route('dashboard') }}" class="btn">Kembali ke Dashboard</a>
    </div>
</body>
</html>
