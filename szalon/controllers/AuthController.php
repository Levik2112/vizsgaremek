<?php
session_start();
require_once __DIR__ . '/../config/db.php';

class AuthController {

    public static function login($email, $password) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
        return false;
    }

    public static function logout() {
        session_destroy();
        header("Location: login.php");
    }
}
