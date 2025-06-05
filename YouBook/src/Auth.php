<?php

namespace App;

use PDO;

class Auth
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Registra um novo usuário no banco de dados.
     * @param string $email O email do usuário.
     * @param string $password A senha em texto plano.
     * @return bool True se o registro for bem-sucedido, false caso contrário.
     */
    public function register($email, $password)
    {
        // Validação básica
        if (empty($email) || empty($password) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false; // Ou lançar uma exceção específica
        }

        // Hash da senha (MUITO IMPORTANTE!)
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Verifica se o email já existe
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                // Email já registrado
                return false;
            }

            // Insere o novo usuário
            $stmt = $this->pdo->prepare("INSERT INTO usuarios (email, password_hash) VALUES (:email, :password_hash)");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password_hash', $passwordHash);
            return $stmt->execute();

        } catch (\PDOException $e) {
            error_log('Erro ao registrar usuário: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Tenta autenticar um usuário.
     * @param string $email O email do usuário.
     * @param string $password A senha em texto plano.
     * @return array|false Retorna os dados do usuário se o login for bem-sucedido, false caso contrário.
     */
    public function login($email, $password)
    {
        // Validação básica
        if (empty($email) || empty($password)) {
            return false;
        }

        try {
            $stmt = $this->pdo->prepare("SELECT id, email, password_hash FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                // Senha correta, remove o hash da senha para não expor na sessão
                unset($user['password_hash']);
                return $user;
            } else {
                return false; // Usuário não encontrado ou senha incorreta
            }
        } catch (\PDOException $e) {
            error_log('Erro ao fazer login: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica se o usuário está logado.
     * @return bool True se o usuário estiver logado, false caso contrário.
     */
    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Desloga o usuário.
     */
    public static function logout()
    {
        session_unset(); // Remove todas as variáveis de sessão
        session_destroy(); // Destrói a sessão
        session_write_close(); // Garante que a sessão seja fechada
        setcookie(session_name(), '', 0, '/'); // Remove o cookie de sessão
    }
}