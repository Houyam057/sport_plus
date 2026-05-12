<?php
/**
 * GameMatch Model (table: matchs)
 * Named GameMatch because `match` is a reserved word in PHP 8+.
 */

class GameMatch {

    /**
     * Add missing columns if they don't exist (runs once per request, safe to call repeatedly).
     */
    public static function ensureTable(): void
    {
        static $done = false;
        if ($done) return;
        $done = true;

        $db = db();
        // MariaDB 10.0.2+ supports IF NOT EXISTS on ADD COLUMN
        $db->exec("ALTER TABLE matchs
            ADD COLUMN IF NOT EXISTS terrain_id     INT          DEFAULT NULL,
            ADD COLUMN IF NOT EXISTS creator_id     INT          DEFAULT NULL,
            ADD COLUMN IF NOT EXISTS title          VARCHAR(100) DEFAULT 'Match ouvert',
            ADD COLUMN IF NOT EXISTS start_time     TIME         DEFAULT NULL,
            ADD COLUMN IF NOT EXISTS max_players    INT          DEFAULT 10,
            ADD COLUMN IF NOT EXISTS current_players INT         DEFAULT 0,
            ADD COLUMN IF NOT EXISTS level          VARCHAR(20)  DEFAULT 'any',
            ADD COLUMN IF NOT EXISTS status         VARCHAR(20)  DEFAULT 'open'
        ");
    }

    /**
     * Get all open matches for a specific terrain (with participant count).
     */
    public static function forTerrain(int $terrain_id): array
    {
        self::ensureTable();
        $stmt = db()->prepare("
            SELECT m.*,
                   u.nom AS creator_nom,
                   (m.max_players - m.current_players) AS spots_left
            FROM matchs m
            LEFT JOIN utilisateurs u ON m.creator_id = u.id
            WHERE m.terrain_id = ?
              AND m.status IN ('open','full')
              AND m.date >= CURDATE()
            ORDER BY m.date ASC, m.start_time ASC
            LIMIT 10
        ");
        $stmt->execute([$terrain_id]);
        return $stmt->fetchAll();
    }

    /**
     * Check if the current user already joined a match.
     */
    public static function alreadyJoined(int $match_id): bool
    {
        if (!isset($_SESSION['user_id'])) return false;
        $stmt = db()->prepare(
            "SELECT COUNT(*) FROM participations WHERE match_id = ? AND utilisateur_id = ?"
        );
        $stmt->execute([$match_id, $_SESSION['user_id']]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public static function all($filters = []): array
    {
        self::ensureTable();
        $sql = "
            SELECT m.*, t.nom AS terrain_name, t.localisation, t.type_sport,
                   u.nom AS creator_nom,
                   (m.max_players - m.current_players) AS spots_left
            FROM matchs m
            LEFT JOIN terrains t ON m.terrain_id = t.id
            LEFT JOIN utilisateurs u ON m.creator_id = u.id
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

    public static function findById($id)
    {
        self::ensureTable();
        $stmt = db()->prepare("SELECT * FROM matchs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($data): int
    {
        self::ensureTable();
        $stmt = db()->prepare("
            INSERT INTO matchs (creator_id, terrain_id, title, date, start_time, max_players, current_players, level, status)
            VALUES (?, ?, ?, ?, ?, ?, 1, ?, 'open')
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
        $matchId = (int)db()->lastInsertId();

        // Creator automatically participates
        db()->prepare("INSERT IGNORE INTO participations (match_id, utilisateur_id) VALUES (?, ?)")
            ->execute([$matchId, $data['creator_id']]);

        return $matchId;
    }

    public static function join(int $match_id): bool
    {
        self::ensureTable();
        if (!isset($_SESSION['user_id'])) return false;
        $userId = (int)$_SESSION['user_id'];

        // Check match is still open and has spots
        $stmt = db()->prepare("SELECT * FROM matchs WHERE id = ? AND status = 'open'");
        $stmt->execute([$match_id]);
        $match = $stmt->fetch();
        if (!$match) return false;

        if ((int)$match['current_players'] >= (int)$match['max_players']) return false;

        // Insert participation (ignore duplicate)
        $ins = db()->prepare("INSERT IGNORE INTO participations (match_id, utilisateur_id) VALUES (?, ?)");
        $ins->execute([$match_id, $userId]);
        if ($ins->rowCount() === 0) return false; // already joined

        // Increment counter, mark full if needed
        db()->prepare("
            UPDATE matchs
            SET current_players = current_players + 1,
                status = CASE WHEN current_players + 1 >= max_players THEN 'full' ELSE 'open' END
            WHERE id = ?
        ")->execute([$match_id]);

        return true;
    }

    /**
     * Seed demo matches for today+future if none exist for a terrain.
     */
    public static function seedForTerrain(int $terrain_id, string $sport): void
    {
        self::ensureTable();
        $count = (int)db()->prepare("SELECT COUNT(*) FROM matchs WHERE terrain_id = ?")
                          ->execute([$terrain_id]) ? db()->query("SELECT COUNT(*) FROM matchs WHERE terrain_id = $terrain_id")->fetchColumn() : 0;
        if ($count > 0) return;

        $titles = [
            'Football'   => ['Match amical 5v5', 'Tournoi Football', 'Partie libre Football'],
            'Tennis'     => ['Single Match', 'Double Tennis', 'Entraînement Tennis'],
            'Basketball' => ['3v3 Streetball', 'Match Basketball', '5v5 Basketball'],
            'Padel'      => ['Match Padel Débutants', 'Tournoi Padel', 'Partie Padel'],
        ];
        $maxPlayers = ['Football'=>10,'Tennis'=>4,'Basketball'=>6,'Padel'=>4];

        $t = $titles[$sport] ?? ['Match ouvert','Match amical','Partie libre'];
        $max = $maxPlayers[$sport] ?? 8;

        $insert = db()->prepare("
            INSERT INTO matchs (creator_id, terrain_id, title, date, start_time, max_players, current_players, level, status)
            VALUES (1, ?, ?, ?, ?, ?, ?, 'any', 'open')
        ");

        $seeds = [
            [$t[0], date('Y-m-d', strtotime('+1 day')),  '18:00:00', rand(1, $max-1)],
            [$t[1], date('Y-m-d', strtotime('+3 days')), '10:00:00', rand(0, $max-2)],
            [$t[2] ?? $t[0], date('Y-m-d', strtotime('+5 days')), '20:00:00', 0],
        ];

        foreach ($seeds as [$title, $date, $time, $cur]) {
            $insert->execute([$terrain_id, $title, $date, $time, $max, $cur]);
        }
    }
}
