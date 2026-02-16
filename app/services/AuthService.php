<?php
class AuthService {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function connecter($email, $mot_de_passe) {
        $st = $this->pdo->prepare('SELECT id_user, mot_de_passe, id_role FROM users WHERE email = ? LIMIT 1');
        $st->execute([trim((string)$email)]);
        $user = $st->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return ['ok' => false, 'message' => "Email ou mot de passe incorrect."];
        }

        $mot_db = (string)($user['mot_de_passe'] ?? '');
        $ok = false;

        // Compatible: mots de passe en clair (base.sql) OU hashés (si tu changes plus tard)
        if ($mot_db === (string)$mot_de_passe) {
            $ok = true;
        } elseif (password_verify((string)$mot_de_passe, $mot_db)) {
            $ok = true;
        }

        if (!$ok) {
            return ['ok' => false, 'message' => "Email ou mot de passe incorrect."];
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION['id_user'] = (int)$user['id_user'];
        $_SESSION['id_role'] = (int)$user['id_role'];

        return ['ok' => true, 'id_user' => (int)$user['id_user'], 'id_role' => (int)$user['id_role']];
    }

    public function deconnecter() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        unset($_SESSION['id_user'], $_SESSION['id_role']);
    }
}
