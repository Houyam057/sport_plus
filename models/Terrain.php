<?php
/**
 * Terrain Model
 */

class Terrain {

    public static function all($filters = []) {
        $sql    = "SELECT * FROM terrains WHERE 1=1";
        $params = [];

        if (!empty($filters['localisation']) && $filters['localisation'] !== 'all') {
            $sql .= " AND localisation = ?";
            $params[] = $filters['localisation'];
        }

        if (!empty($filters['type_sport']) && $filters['type_sport'] !== 'all') {
            $sql .= " AND type_sport = ?";
            $params[] = $filters['type_sport'];
        }

        $sql .= " ORDER BY nom ASC";

        $stmt = db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function findById($id) {
        $stmt = db()->prepare("SELECT * FROM terrains WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function byLocalisation($localisation) {
        $stmt = db()->prepare("SELECT * FROM terrains WHERE localisation = ?");
        $stmt->execute([$localisation]);
        return $stmt->fetchAll();
    }

    public static function create($data) {
        $stmt = db()->prepare("
            INSERT INTO terrains (nom, localisation, type_sport, prix)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['nom'],
            $data['localisation'],
            $data['type_sport'],
            $data['prix'],
        ]);
    }

    public static function delete($id) {
        return db()->prepare("DELETE FROM terrains WHERE id = ?")->execute([$id]);
    }

    public static function countAll() {
        return db()->query("SELECT COUNT(*) FROM terrains")->fetchColumn();
    }

    /**
     * Seed 9 default terrains if the table is empty (first-run helper)
     */
    public static function seedIfEmpty() {
        if ((int)db()->query("SELECT COUNT(*) FROM terrains")->fetchColumn() >= 18) {
            return; // already fully seeded
        }
        $seeds = [
            // Tangier (6)
            ['nom'=>'Stade Al Majd',          'localisation'=>'Tangier',    'type_sport'=>'Football',   'prix'=>120],
            ['nom'=>'Royal Tennis Club',      'localisation'=>'Tangier',    'type_sport'=>'Tennis',     'prix'=>130],
            ['nom'=>'Tanger Balia Court',     'localisation'=>'Tangier',    'type_sport'=>'Basketball', 'prix'=>80],
            ['nom'=>'Tanger Padel Center',    'localisation'=>'Tangier',    'type_sport'=>'Padel',      'prix'=>150],
            ['nom'=>'Stade Ibn Batouta',      'localisation'=>'Tangier',    'type_sport'=>'Football',   'prix'=>90],
            ['nom'=>'Beach Tennis Malabata',  'localisation'=>'Tangier',    'type_sport'=>'Tennis',     'prix'=>110],
            // Marrakesh (6)
            ['nom'=>'Palmeraie Tennis Club',  'localisation'=>'Marrakesh',  'type_sport'=>'Tennis',     'prix'=>95],
            ['nom'=>'Atlas Football Park',    'localisation'=>'Marrakesh',  'type_sport'=>'Football',   'prix'=>100],
            ['nom'=>'Palmeraie Padel',        'localisation'=>'Marrakesh',  'type_sport'=>'Padel',      'prix'=>145],
            ['nom'=>'Gueliz Basketball Club', 'localisation'=>'Marrakesh',  'type_sport'=>'Basketball', 'prix'=>75],
            ['nom'=>'Red City Football',      'localisation'=>'Marrakesh',  'type_sport'=>'Football',   'prix'=>85],
            ['nom'=>'Hivernage Padel Club',   'localisation'=>'Marrakesh',  'type_sport'=>'Padel',      'prix'=>155],
            // Casablanca (6)
            ['nom'=>'Casa Padel Arena',       'localisation'=>'Casablanca', 'type_sport'=>'Padel',      'prix'=>160],
            ['nom'=>'Court Central Casa',     'localisation'=>'Casablanca', 'type_sport'=>'Basketball', 'prix'=>80],
            ['nom'=>'Arena Sport Casa',       'localisation'=>'Casablanca', 'type_sport'=>'Football',   'prix'=>140],
            ['nom'=>'Ain Diab Tennis Club',   'localisation'=>'Casablanca', 'type_sport'=>'Tennis',     'prix'=>120],
            ['nom'=>'Maarif Football Ground', 'localisation'=>'Casablanca', 'type_sport'=>'Football',   'prix'=>100],
            ['nom'=>'Anfa Basketball Arena',  'localisation'=>'Casablanca', 'type_sport'=>'Basketball', 'prix'=>90],
        ];
        $check  = db()->prepare("SELECT COUNT(*) FROM terrains WHERE nom = ?");
        $insert = db()->prepare("INSERT INTO terrains (nom, localisation, type_sport, prix) VALUES (?, ?, ?, ?)");
        foreach ($seeds as $s) {
            $check->execute([$s['nom']]);
            if ((int)$check->fetchColumn() === 0) {
                $insert->execute([$s['nom'], $s['localisation'], $s['type_sport'], $s['prix']]);
            }
        }
    }

    /**
     * Get available time slots for a terrain on a given date
     */
    public static function getAvailableSlots($terrain_id, $date) {
        $allSlots = ['08:00','09:00','10:00','11:00','12:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00'];

        $stmt = db()->prepare("
            SELECT TIME_FORMAT(heure_debut, '%H:%i') as time
            FROM reservations
            WHERE terrain_id = ? AND date = ? AND statut IN ('confirmed','pending')
        ");
        $stmt->execute([$terrain_id, $date]);
        $booked = array_column($stmt->fetchAll(), 'time');

        $slots = [];
        foreach ($allSlots as $slot) {
            $slots[] = [
                'time'      => $slot,
                'available' => !in_array($slot, $booked),
            ];
        }
        return $slots;
    }
}