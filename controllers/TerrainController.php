<?php
require_once __DIR__ . '/../models/Terrain.php';
require_once __DIR__ . '/../models/Avis.php';

class TerrainController {

    public function index() {
        Terrain::seedIfEmpty();
        $terrains = Terrain::all();
        require __DIR__ . '/../views/terrains.php';
    }

    public function show() {
        $id = (int)($_GET['id'] ?? 0);
        $terrain = Terrain::findById($id);
        if (!$terrain) redirect('terrains');

        $slots   = Terrain::getAvailableSlots($id, date('Y-m-d'));
        $avis    = Avis::byTerrain($id);
        $avgNote = Avis::avgNote($id);
        $nbAvis  = Avis::countByTerrain($id);

        require __DIR__ . '/../views/terrain-detail.php';
    }
}
