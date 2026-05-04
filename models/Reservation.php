<?php
/**
 * Reservation Model
 */

class Reservation {

    public static function create($data) {
        // Conflict check (no double-booking)
        if (self::hasConflict($data['terrain_id'], $data['date'], $data['heure_debut'])) {
            return false;
        }

        $stmt = db()->prepare("
            INSERT INTO reservations (utilisateur_id, terrain_id, date, heure_debut, heure_fin, statut)
            VALUES (?, ?, ?, ?, ?, 'pending')
        ");
        $stmt->execute([
            $data['utilisateur_id'],
            $data['terrain_id'],
            $data['date'],
            $data['heure_debut'],
            $data['heure_fin'],
        ]);
        return db()->lastInsertId();
    }

    public static function hasConflict($terrain_id, $date, $heure_debut) {
        $stmt = db()->prepare("
            SELECT COUNT(*) FROM reservations
            WHERE terrain_id = ? AND date = ? AND heure_debut = ?
              AND statut IN ('confirmed','pending')
        ");
        $stmt->execute([$terrain_id, $date, $heure_debut]);
        return $stmt->fetchColumn() > 0;
    }

    public static function findById($id) {
        $stmt = db()->prepare("SELECT * FROM reservations WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function byUser($user_id) {
        $stmt = db()->prepare("
            SELECT r.*, t.nom AS terrain_name, t.localisation, t.type_sport, t.prix
            FROM reservations r
            JOIN terrains t ON r.terrain_id = t.id
            WHERE r.utilisateur_id = ?
            ORDER BY r.date DESC, r.heure_debut DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public static function all() {
        return db()->query("
            SELECT r.*,
                   t.nom AS terrain_name, t.localisation, t.type_sport, t.prix,
                   u.nom AS user_nom, u.email AS user_email
            FROM reservations r
            JOIN terrains t ON r.terrain_id = t.id
            JOIN utilisateurs u ON r.utilisateur_id = u.id
            ORDER BY r.date DESC
        ")->fetchAll();
    }

    public static function updateStatus($id, $status) {
        return db()->prepare("UPDATE reservations SET statut = ? WHERE id = ?")
                   ->execute([$status, $id]);
    }

    public static function countAll() {
        return db()->query("SELECT COUNT(*) FROM reservations")->fetchColumn();
    }

    public static function totalRevenue() {
        return db()->query("SELECT COALESCE(SUM(t.prix),0) FROM reservations r JOIN terrains t ON r.terrain_id = t.id WHERE r.statut = 'confirmed'")
                   ->fetchColumn();
    }

    public static function bookingsByCity() {
        return db()->query("
            SELECT t.localisation,
                   COUNT(r.id) AS bookings,
                   COALESCE(SUM(t.prix),0) AS revenue,
                   COUNT(DISTINCT t.id) AS terrain_count
            FROM terrains t
            LEFT JOIN reservations r ON t.id = r.terrain_id
            GROUP BY t.localisation
        ")->fetchAll();
    }

    public static function bookingsByDate($days = 7) {
        $stmt = db()->prepare("
            SELECT DATE(date) AS booking_date, COUNT(*) AS count
            FROM reservations
            WHERE date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
            GROUP BY DATE(date)
            ORDER BY booking_date ASC
        ");
        $stmt->execute([$days]);
        return $stmt->fetchAll();
    }
}