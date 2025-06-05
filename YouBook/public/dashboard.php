<?php
session_start();
require_once __DIR__ . '/../src/Auth.php';

use App\Auth;

// Se o usuário não estiver logado, redireciona para a página de login
if (!Auth::isLoggedIn()) {
    $_SESSION['message'] = 'Você precisa fazer login para acessar esta página.';
    $_SESSION['message_type'] = 'error';
    header('Location: login.php');
    exit();
}

// Lógica para deslogar
if (isset($_GET['logout'])) {
    Auth::logout();
    $_SESSION['message'] = 'Você foi desconectado com sucesso.';
    $_SESSION['message_type'] = 'success';
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 100vh; background-color: #f4f4f4; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); text-align: center; }
        .logout-btn { background-color: #dc3545; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 20px; }
        .logout-btn:hover { background-color: #c82333; }
        .message { margin-bottom: 10px; padding: 10px; border-radius: 4px; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
    <div class="container">
        <?php
            if (isset($_SESSION['message'])) {
                $class = $_SESSION['message_type'] ?? 'info';
                echo '<div class="message ' . $class . '">' . htmlspecialchars($_SESSION['message']) . '</div>';
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            }
        ?>
        <form action="busca_livros.php" method="get">
    <input type="text" name="q" placeholder="Buscar livros...">
    <button type="submit">Buscar</button>
    </form>
    </div>
</body>
</html> 