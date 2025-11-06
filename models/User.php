<?php

/**
 * Modèle User - Gestion des utilisateurs
 */
class User extends Model
{
    /**
     * Créer un nouvel utilisateur
     *
     * @param string $pseudo
     * @param string $email
     * @param string $password
     * @return int|false ID de l'utilisateur créé ou false
     */
    public function create(string $pseudo, string $email, string $password)
    {
        $hashedPassword = password_hash($password, HASH_ALGO, ['cost' => HASH_COST]);

        $stmt = $this->db->prepare("
            INSERT INTO users (pseudo, email, password, created_at)
            VALUES (:pseudo, :email, :password, NOW())
        ");

        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    /**
     * Trouver un utilisateur par email
     *
     * @param string $email
     * @return array|false
     */
    public function findByEmail(string $email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Trouver un utilisateur par ID
     *
     * @param int $id
     * @return array|false
     */
    public function findById(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Trouver un utilisateur par pseudo
     *
     * @param string $pseudo
     * @return array|false
     */
    public function findByPseudo(string $pseudo)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE pseudo = :pseudo");
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Vérifier si un email existe déjà
     *
     * @param string $email
     * @return bool
     */
    public function emailExists(string $email): bool
    {
        return $this->findByEmail($email) !== false;
    }

    /**
     * Vérifier si un pseudo existe déjà
     *
     * @param string $pseudo
     * @return bool
     */
    public function pseudoExists(string $pseudo): bool
    {
        return $this->findByPseudo($pseudo) !== false;
    }

    /**
     * Vérifier les identifiants de connexion
     *
     * @param string $email
     * @param string $password
     * @return array|false Données utilisateur ou false
     */
    public function verifyCredentials(string $email, string $password)
    {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    /**
     * Mettre à jour un utilisateur
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = [':id' => $id];

        if (isset($data['pseudo'])) {
            $fields[] = "pseudo = :pseudo";
            $params[':pseudo'] = $data['pseudo'];
        }

        if (isset($data['email'])) {
            $fields[] = "email = :email";
            $params[':email'] = $data['email'];
        }

        if (isset($data['password'])) {
            $fields[] = "password = :password";
            $params[':password'] = password_hash($data['password'], HASH_ALGO, ['cost' => HASH_COST]);
        }

        if (isset($data['photo'])) {
            $fields[] = "photo = :photo";
            $params[':photo'] = $data['photo'];
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE users SET " . implode(', ', $fields) . ", updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute($params);
    }

    /**
     * Supprimer un utilisateur
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
