<?php
declare(strict_types=1);

class AuthController
{
    public function __construct(private PDO $pdo) {}
    public function loginForm(): void
    {
        render('auth/login', [
            'pageTitle' => 'Masuk — HP Business',
            'currentUser' => $_SESSION['user'] ?? null,
        ]);
    }

    public function registerForm(): void
    {
        render('auth/register', ['pageTitle' => 'Daftar — HP Business']);
    }
    public function login(): void { verify_csrf(); $email=trim($_POST['email']??''); $password=$_POST['password']??''; $s=$this->pdo->prepare("SELECT id,name,email,password FROM users WHERE email=? AND status='active' LIMIT 1"); $s->execute([$email]); $user=$s->fetch(); if (!$user || !password_verify($password,$user['password'])) { flash('Email atau kata sandi salah.'); redirect('login'); } unset($user['password']); $_SESSION['user']=$user; redirect(); }
    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
        session_start();
        session_regenerate_id(true);
        flash('Anda telah berhasil keluar dari sistem.');
        redirect('login');
    }
    public function register(): void
    {
        verify_csrf();
        $name = trim($_POST['name'] ?? '');
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        if (!$name || !$email || strlen($password) < 8) { flash('Nama, email valid, dan kata sandi minimal 8 karakter wajib diisi.'); redirect('register'); }
        try {
            $username = 'user_' . bin2hex(random_bytes(5));
            $s = $this->pdo->prepare("INSERT INTO users (name,email,username,password,role,status) VALUES (?,?,?,?, 'admin','active')");
            $s->execute([$name, $email, $username, password_hash($password, PASSWORD_DEFAULT)]);
            flash('Akun berhasil dibuat. Silakan masuk.');
            redirect('login');
        } catch (PDOException $e) {
            flash('Email tersebut sudah terdaftar. Silakan masuk dengan akun Anda.');
            redirect('login');
        }
    }
}
