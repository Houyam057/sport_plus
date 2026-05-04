<?php
require_once __DIR__ . '/../models/Avis.php';

class AvisController {

    public function store() {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('terrains');

        $terrain_id  = (int)($_POST['terrain_id'] ?? 0);
        $note        = min(5, max(1, (int)($_POST['note'] ?? 5)));
        $commentaire = trim($_POST['commentaire'] ?? '');

        if (!$terrain_id || empty($commentaire)) {
            flash('avis_error', 'Please write a comment before submitting.');
            redirect('terrain/' . $terrain_id);
        }

        Avis::create([
            'utilisateur_id' => $_SESSION['user_id'],
            'terrain_id'     => $terrain_id,
            'note'           => $note,
            'commentaire'    => $commentaire,
        ]);

        flash('avis_success', 'Your review has been posted!');
        redirect('terrain/' . $terrain_id);
    }
}
