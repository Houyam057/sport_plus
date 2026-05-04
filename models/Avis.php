<?php
/*
 * Avis (Reviews) Model
 *
 * Run this SQL once if the table doesn't exist:
 * CREATE TABLE IF NOT EXISTS avis (
 *   id INT AUTO_INCREMENT PRIMARY KEY,
 *   utilisateur_id INT NOT NULL,
 *   terrain_id INT NOT NULL,
 *   note TINYINT(1) NOT NULL DEFAULT 5,
 *   commentaire TEXT NOT NULL,
 *   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 *   FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
 *   FOREIGN KEY (terrain_id) REFERENCES terrains(id) ON DELETE CASCADE
 * );
 */

class Avis {

    public static function byTerrain($terrain_id) {
        try {
            $stmt = db()->prepare("
                SELECT a.*, u.nom AS user_nom
                FROM avis a
                JOIN utilisateurs u ON a.utilisateur_id = u.id
                WHERE a.terrain_id = ?
                ORDER BY a.id DESC
                LIMIT 50
            ");
            $stmt->execute([$terrain_id]);
            return $stmt->fetchAll();
        } catch (\Exception $e) {
            return [];
        }
    }

    public static function create($data) {
        $stmt = db()->prepare("
            INSERT INTO avis (utilisateur_id, terrain_id, note, commentaire)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            (int)$data['utilisateur_id'],
            (int)$data['terrain_id'],
            min(5, max(1, (int)$data['note'])),
            $data['commentaire'],
        ]);
        return db()->lastInsertId();
    }

    public static function avgNote($terrain_id) {
        try {
            $stmt = db()->prepare("SELECT COALESCE(AVG(note), 0) FROM avis WHERE terrain_id = ?");
            $stmt->execute([$terrain_id]);
            return round((float)$stmt->fetchColumn(), 1);
        } catch (\Exception $e) {
            return 0;
        }
    }

    public static function countByTerrain($terrain_id) {
        try {
            $stmt = db()->prepare("SELECT COUNT(*) FROM avis WHERE terrain_id = ?");
            $stmt->execute([$terrain_id]);
            return (int)$stmt->fetchColumn();
        } catch (\Exception $e) {
            return 0;
        }
    }
}
