<?php
/**
 * Match Model (table: matches)
 * NOTE: class is named GameMatch because `Match` is a reserved word in PHP 8+
 */

class GameMatch {

    public static function all($filters = []) {
        $sql = "
            SELECT m.*, t.nom AS terrain_name, t.localisation, t.type_sport,
                   u.nom AS creator_nom
            FROM matchs m
            JOIN terrains t ON m.terrain_id = t.id
            JOIN utilisateurs u ON m.creator_id = u.id
            WHERE m.status != 'closed'
        ";
        $params = [];

        if (!empty($filters['city']) && $filters['city'] !== 'all') {
            $sql .= " AND t.localisation = ?";
            $params[] = $filters['city'];
        }

        $sql .= " ORDER BY m.date ASC, m.start_time ASC";

        $stmt = db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function findById($id) {
        $stmt = db()->prepare("SELECT * FROM matchs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($data) {
        $stmt = db()->prepare("
            INSERT INTO matchs (creator_id, terrain_id, title, date, start_time, max_players, level)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['creator_id'],
            $data['terrain_id'],
            $data['title'],
            $data['date'],
            $data['start_time'],
            $data['max_players'],
            $data['level'] ?? 'any',
        ]);
        return db()->lastInsertId();
    }

    public static function join($match_id) {
        // Increment current_players, mark full if reached
        db()->prepare("
            UPDATE matchs
            SET current_players = current_players + 1,
                status = CASE WHEN current_players + 1 >= max_players THEN 'full' ELSE status END
            WHERE id = ? AND status = 'open'
        ")->execute([$match_id]);

        return db()->prepare("SELECT id FROM matchs WHERE id = ?")
                   ->execute([$match_id]);
    }
}