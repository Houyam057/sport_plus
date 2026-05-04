<?php
require_once __DIR__ . '/../models/Terrain.php';
require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../models/User.php';

class AdminController {

    private function loadAll() {
        return [
            'stats' => [
                'reservations' => Reservation::countAll(),
                'terrains'     => Terrain::countAll(),
                'users'        => User::countAll(),
                'revenue'      => Reservation::totalRevenue(),
            ],
            'byCity'          => Reservation::bookingsByCity(),
            'bookingsByDate'  => Reservation::bookingsByDate(7),
            'recent'          => array_slice(Reservation::all(), 0, 5),
            'terrains'        => Terrain::all(),
            'reservations'    => Reservation::all(),
            'users'           => User::all(),
        ];
    }

    public function dashboard() {
        requireAdmin();
        $data = $this->loadAll();
        extract($data);
        $activeTab = 'dashboard';
        require __DIR__ . '/../views/admin.php';
    }

    public function terrains() {
        requireAdmin();
        $data = $this->loadAll();
        extract($data);
        $activeTab = 'terrains';
        require __DIR__ . '/../views/admin.php';
    }

    public function addTerrain() {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('admin/terrains');

        Terrain::create([
            'nom'          => trim($_POST['nom']),
            'type_sport'   => $_POST['type_sport'],
            'localisation' => $_POST['localisation'],
            'prix'         => (float)$_POST['prix'],
        ]);
        flash('success', 'Terrain added.');
        redirect('admin/terrains');
    }

    public function deleteTerrain() {
        requireAdmin();
        $id = (int)($_POST['id'] ?? 0);
        Terrain::delete($id);
        flash('success', 'Terrain deleted.');
        redirect('admin/terrains');
    }

    public function reservations() {
        requireAdmin();
        $data = $this->loadAll();
        extract($data);
        $activeTab = 'reservations';
        require __DIR__ . '/../views/admin.php';
    }

    public function validateReservation() {
        requireAdmin();
        $id = (int)($_POST['id'] ?? 0);
        Reservation::updateStatus($id, 'confirmed');
        flash('success', 'Reservation confirmed.');
        redirect('admin/reservations');
    }

    public function users() {
        requireAdmin();
        $data = $this->loadAll();
        extract($data);
        $activeTab = 'users';
        require __DIR__ . '/../views/admin.php';
    }

    public function toggleUser() {
        requireAdmin();
        redirect('admin/users');
    }
}
