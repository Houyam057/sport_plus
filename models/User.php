<?php
/**
 * User Model (FINAL CLEAN VERSION)
 */

class User {

    public static function findById($id) {
        $stmt = db()->prepare("SELECT * FROM utilisateurs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function findByEmail($email) {
        $stmt = db()->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public static function authenticate($email, $password) {
        $user = self::findByEmail($email);

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            return $user;
        }

        return false;
    }

    public static function create($data) {
        $hash = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);

        $stmt = db()->prepare("
            INSERT INTO utilisateurs (nom, email, mot_de_passe, role)
            VALUES (?, ?, ?, 'user')
        ");

        $stmt->execute([
            $data['nom'],
            $data['email'],
            $hash
        ]);

        return db()->lastInsertId();
    }

    public static function all() {
        return db()->query("SELECT * FROM utilisateurs ORDER BY id DESC")->fetchAll();
    }

    public static function countAll() {
        return db()->query("SELECT COUNT(*) FROM utilisateurs WHERE role = 'user'")->fetchColumn();
    }

    public static function update($id, $data) {
        $sets = [];
        $values = [];
        foreach ($data as $key => $value) {
            $sets[] = "$key = ?";
            $values[] = $value;
        }
        $values[] = $id;
        $sql = "UPDATE utilisateurs SET " . implode(', ', $sets) . " WHERE id = ?";
        return db()->prepare($sql)->execute($values);
    }
}