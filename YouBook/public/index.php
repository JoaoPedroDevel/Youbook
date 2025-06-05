<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 100vh; background-color: #f4f4f4; text-align: center; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        .button-group { margin-top: 20px; }
        .button-group a {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .button-group a.login { background-color: #007bff; color: white; }
        .button-group a.login:hover { background-color: #0056b3; }
        .button-group a.register { background-color: #28a745; color: white; }
        .button-group a.register:hover { background-color: #218838; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bem-vindo ao Sistema!</h1>
        <p>Faça login ou registre-se para continuar.</p>
        <div class="button-group">
            <a href="login.php" class="login">Login</a>
            <a href="registro.php" class="register">Registrar</a>
        </div>
    </div>
</body>
</html>