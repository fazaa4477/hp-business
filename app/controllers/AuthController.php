<?php
declare(strict_types=1);

class AuthController
{
    public function __construct(private PDO $pdo) {}
    public function form(): void { if (!empty($_SESSION['user'])) redirect('./'); $hasUser=(bool)$this->pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(); render('auth/login', ['pageTitle'=>$hasUser?'Masuk — HP Business':'Buat akun — HP Business','hasUser'=>$hasUser]); }
    public function login(): void { verify_csrf(); $email=trim($_POST['email']??''); $password=$_POST['password']??''; $s=$this->pdo->prepare("SELECT id,name,email,password FROM users WHERE email=? AND status='active' LIMIT 1"); $s->execute([$email]); $user=$s->fetch(); if (!$user || !password_verify($password,$user['password'])) { flash('Email atau kata sandi salah.'); redirect('login'); } unset($user['password']); $_SESSION['user']=$user; redirect('./'); }
    public function logout(): void { session_destroy(); session_start(); flash('Anda sudah keluar.'); redirect('login'); }
    public function setup(): void { verify_csrf(); if ((int)$this->pdo->query('SELECT COUNT(*) FROM users')->fetchColumn() > 0) redirect('login'); $name=trim($_POST['name']??''); $email=filter_var($_POST['email']??'',FILTER_VALIDATE_EMAIL); $password=$_POST['password']??''; if(!$name || !$email || strlen($password)<8){ flash('Nama, email valid, dan kata sandi minimal 8 karakter wajib diisi.'); redirect('login'); } $s=$this->pdo->prepare("INSERT INTO users (name,email,username,password,role,status) VALUES (?,?,?,?, 'admin','active')"); $s->execute([$name,$email,explode('@',$email)[0],password_hash($password,PASSWORD_DEFAULT)]); flash('Akun dibuat. Silakan masuk.'); redirect('login'); }
}
