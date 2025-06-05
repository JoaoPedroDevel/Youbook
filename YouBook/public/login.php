<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f4f4f4; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input[type="email"], input[type="password"] { width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        input[type="submit"] { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; width: 100%; }
        input[type="submit"]:hover { background-color: #0056b3; }
        .message { margin-top: 10px; padding: 10px; border-radius: 4px; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="../src/processa_login.php" method="post">
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="password" placeholder="Senha" required>
            <input type="submit" value="Entrar">
        </form>
        <p>Não tem uma conta? <a href="registro.php">Registre-se aqui</a></p>
        <?php
            session_start();
            if (isset($_SESSION['message'])) {
                $class = $_SESSION['message_type'] ?? 'info';
                echo '<div class="message ' . $class . '">' . htmlspecialchars($_SESSION['message']) . '</div>';
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            }
        ?>
    </div>
</body>
</html>