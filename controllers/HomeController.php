<?php
require_once __DIR__ . '/../models/Terrain.php';

class HomeController {
    public function index() {
        Terrain::seedIfEmpty();
        $terrains = Terrain::all();
        require __DIR__ . '/../views/index.php';
    }

    public function about() {
        require __DIR__ . '/../views/about.php';
    }
}
