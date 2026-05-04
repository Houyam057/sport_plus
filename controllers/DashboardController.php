<?php
require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../models/User.php';

class DashboardController {

    public function index() {
        requireLogin();

        $user         = User::findById($_SESSION['user_id']);
        $reservations = Reservation::byUser($_SESSION['user_id']);

        $today = date('Y-m-d');
        $stats = [
            'total'     => count($reservations),
            'upcoming'  => count(array_filter($reservations, fn($r) =>
                in_array($r['statut'], ['confirmed', 'pending']) && $r['date'] >= $today
            )),
            'completed' => count(array_filter($reservations, fn($r) => $r['statut'] === 'completed')),
        ];

        require __DIR__ . '/../views/dashboard.php';
    }

    public function updateProfile() {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('dashboard');

        $data = [];
        $nom   = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($nom !== '')   $data['nom']   = $nom;
        if ($email !== '') $data['email'] = $email;

        if (!empty($data)) {
            User::update($_SESSION['user_id'], $data);
            if (isset($data['nom']))   $_SESSION['user_nom']   = $data['nom'];
            if (isset($data['email'])) $_SESSION['user_email'] = $data['email'];
        }

        flash('profile_success', 'Profile updated successfully.');
        redirect('dashboard');
    }
}
