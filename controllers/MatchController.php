<?php
require_once __DIR__ . '/../models/Gamematch.php';
require_once __DIR__ . '/../models/Terrain.php';

class MatchController {

    public function index() {
        $filters = ['city' => $_GET['city'] ?? 'all'];
        require __DIR__ . '/../views/matches.php';
    }

    public function create() {
        requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('matches');

        GameMatch::create([
            'creator_id'  => $_SESSION['user_id'],
            'terrain_id'  => (int)$_POST['terrain_id'],
            'title'       => trim($_POST['title']),
            'date'        => $_POST['date'],
            'start_time'  => $_POST['start_time'],
            'max_players' => (int)$_POST['max_players'],
            'level'       => $_POST['level'] ?? 'any',
        ]);

        flash('success', 'Match created!');
        redirect('matches');
    }

    public function join() {
        requireLogin();
        $matchId  = (int)($_POST['match_id'] ?? 0);
        $back     = $_POST['redirect'] ?? null;

        if (GameMatch::join($matchId)) {
            flash('success', 'You joined the match!');
        } else {
            flash('error', 'Could not join — match may be full or you already joined.');
        }

        if ($back) {
            header('Location: ' . $back);
            exit;
        }
        redirect('matches');
    }
}
