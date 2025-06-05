<?php
session_start();
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Auth.php';

use App\Database;
use App\Auth;

// Pega a conexão PDO
$pdo = Database::getInstance()->getConnection();
$auth = new Auth($pdo);

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['message'] = 'Por favor, preencha todos os campos.';
    $_SESSION['message_type'] = 'error';
    header('Location: ../public/registro.php');
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['message'] = 'Formato de e-mail inválido.';
    $_SESSION['message_type'] = 'error';
    header('Location: ../public/registro.php');
    exit();
}

if ($auth->register($email, $password)) {
    $_SESSION['message'] = 'Usuário registrado com sucesso! Agora você pode fazer login.';
    $_SESSION['message_type'] = 'success';
    header('Location: ../public/login.php'); // Redireciona para a página de login
    exit();
} else {
    $_SESSION['message'] = 'Erro ao registrar usuário. O e-mail pode já estar em uso.';
    $_SESSION['message_type'] = 'error';
    header('Location: ../public/registro.php');
    exit();
}