<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {

    public function showLogin() {
        require __DIR__ . '/../views/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('login');
        }

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            flash('error', 'Please fill all fields.');
            redirect('login');
        }

        $user = User::findByEmail($email);

        if (!$user || !password_verify($password, $user['mot_de_passe'])) {
            flash('error', 'Invalid email or password.');
            redirect('login');
        }

        $_SESSION['user_id']    = $user['id'];
        $_SESSION['user_nom']   = $user['nom'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['role']       = $user['role'];

        if ($user['role'] === 'admin') {
            redirect('admin');
        } else {
            redirect('dashboard');
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('login');
        }

        $nom      = trim($_POST['nom'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($nom) || empty($email) || empty($password)) {
            flash('error', 'All fields are required.');
            redirect('login');
        }

        if (User::findByEmail($email)) {
            flash('error', 'This email is already in use.');
            redirect('login');
        }

        User::create([
            'nom'          => $nom,
            'email'        => $email,
            'mot_de_passe' => $password,
        ]);

        flash('success', 'Account created! You can now log in.');
        redirect('login');
    }

    public function logout() {
        session_destroy();
        redirect('login');
    }
}
