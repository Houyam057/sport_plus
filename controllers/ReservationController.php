<?php
require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../models/Terrain.php';

class ReservationController {

    public function create() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('terrains');

        $terrain_id = (int)$_POST['terrain_id'];
        $date       = $_POST['date'];
        $heure_debut = $_POST['heure_debut'];
        $duration   = (int)($_POST['duration'] ?? 1);

        if (empty($heure_debut)) {
            flash('booking_error', 'Please select a time slot.');
            redirect('dashboard');
        }

        $terrain = Terrain::findById($terrain_id);
        if (!$terrain) redirect('terrains');

        $heure_fin   = date('H:i:s', strtotime($heure_debut) + $duration * 3600);

        $reservationId = Reservation::create([
            'utilisateur_id' => $_SESSION['user_id'],
            'terrain_id'     => $terrain_id,
            'date'           => $date,
            'heure_debut'    => $heure_debut,
            'heure_fin'      => $heure_fin,
        ]);

        if ($reservationId) {
            flash('booking_success', 'Booking confirmed successfully! Awaiting admin approval.');
        } else {
            flash('booking_error', 'This time slot is already booked. Please choose another.');
        }

        redirect('dashboard');
    }

    public function cancel() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        requireLogin();
        $id = (int)($_POST['id'] ?? 0);

        $reservation = Reservation::findById($id);
        if (!$reservation) redirect('dashboard');

        // Only owner or admin can cancel
        if ($reservation['utilisateur_id'] != $_SESSION['user_id'] && !isAdmin()) {
            redirect('dashboard');
        }

        Reservation::updateStatus($id, 'cancelled');
        flash('success', 'Reservation cancelled.');
        redirect(isAdmin() ? 'admin/reservations' : 'dashboard');
    }
}