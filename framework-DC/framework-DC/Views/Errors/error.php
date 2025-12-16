@'
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 404 - Página no encontrada</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .error-container {
            background: white;
            padding: 50px 40px;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 600px;
            width: 100%;
        }
        h1 {
            font-size: 120px;
            color: #667eea;
            margin: 0;
            line-height: 1;
            font-weight: bold;
        }
        h2 {
            color: #333;
            margin: 20px 0 10px;
            font-size: 28px;
        }
        p {
            color: #666;
            margin: 15px 0;
            line-height: 1.6;
        }
        .url-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            margin: 25px 0;
            text-align: left;
            word-break: break-all;
        }
        .url-info strong {
            color: #667eea;
            display: block;
            margin-bottom: 8px;
        }
        ul {
            text-align: left;
            display: inline-block;
            margin: 20px 0;
        }
        li {
            color: #555;
            margin: 8px 0;
            padding-left: 5px;
        }
        a {
            display: inline-block;
            padding: 14px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            margin-top: 20px;
            font-weight: 600;
        }
        a:hover {
            background: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .emoji {
            font-size: 60px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="emoji">🔍</div>
        <h1>404</h1>
        <h2>Página no encontrada</h2>
        <p>Lo sentimos, el endpoint que estás buscando no existe.</p>
        <div class="url-info">
            <strong>📍 URL solicitada:</strong>
            <?php echo htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'Desconocida'); ?>
        </div>
        <p><strong>✅ Verifica lo siguiente:</strong></p>
        <ul>
            <li>✓ La URL esté correcta</li>
            <li>✓ El controlador exista en /Controllers</li>
            <li>✓ El método exista en el controlador</li>
            <li>✓ La primera letra del controlador sea mayúscula</li>
        </ul>
        <a href="/framework-DC/">← Volver al inicio</a>
    </div>
</body>
</html>
'@ | Out-File -FilePath "Controllers/Error.php" -Encoding UTF8 -Force
```
