<?php
session_start();
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Auth.php';

use App\Database;
use App\Auth;

$pdo = Database::getInstance()->getConnection();
$auth = new Auth($pdo);

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['message'] = 'Por favor, preencha todos os campos.';
    $_SESSION['message_type'] = 'error';
    header('Location: ../public/login.php');
    exit();
}

$user = $auth->login($email, $password);

if ($user) {
    // Login bem-sucedido
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['message'] = 'Login realizado com sucesso!';
    $_SESSION['message_type'] = 'success';
    header('Location: ../public/dashboard.php'); // Redireciona para a página restrita
    exit();
} else {
    // Login falhou
    $_SESSION['message'] = 'E-mail ou senha incorretos!';
    $_SESSION['message_type'] = 'error';
    header('Location: ../public/login.php');
    exit();
}