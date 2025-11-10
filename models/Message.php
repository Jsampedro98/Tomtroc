<?php

/**
 * Modèle Message
 *
 * Gère toutes les opérations liées aux messages entre utilisateurs.
 * Permet l'envoi, la réception, le marquage comme lu et la récupération
 * des conversations.
 *
 * @package    TomTroc
 * @subpackage Models
 * @author     TomTroc Team
 * @version    1.0.0
 * @since      Version 1.0
 */
class Message extends Model
{

    /**
     * Récupérer toutes les conversations de l'utilisateur
     *
     * Retourne la liste des utilisateurs avec qui l'utilisateur a échangé,
     * avec le dernier message de chaque conversation et le nombre de non-lus.
     *
     * @param int $userId ID de l'utilisateur connecté
     * @return array Liste des conversations avec derniers messages
     */
    public function getConversations(int $userId): array
    {
        $sql = "
            SELECT
                CASE
                    WHEN m.sender_id = :user_id THEN m.receiver_id
                    ELSE m.sender_id
                END as other_user_id,
                u.pseudo as other_user_pseudo,
                u.photo as other_user_photo,
                MAX(m.created_at) as last_message_date,
                (
                    SELECT content
                    FROM messages m2
                    WHERE (m2.sender_id = :user_id2 AND m2.receiver_id = other_user_id)
                       OR (m2.receiver_id = :user_id3 AND m2.sender_id = other_user_id)
                    ORDER BY m2.created_at DESC
                    LIMIT 1
                ) as last_message_content,
                (
                    SELECT sender_id
                    FROM messages m3
                    WHERE (m3.sender_id = :user_id4 AND m3.receiver_id = other_user_id)
                       OR (m3.receiver_id = :user_id5 AND m3.sender_id = other_user_id)
                    ORDER BY m3.created_at DESC
                    LIMIT 1
                ) as last_message_sender_id,
                COUNT(CASE WHEN m.receiver_id = :user_id6 AND m.is_read = 0 THEN 1 END) as unread_count
            FROM messages m
            INNER JOIN users u ON u.id = CASE
                WHEN m.sender_id = :user_id7 THEN m.receiver_id
                ELSE m.sender_id
            END
            WHERE m.sender_id = :user_id8 OR m.receiver_id = :user_id9
            GROUP BY other_user_id, u.pseudo, u.photo
            ORDER BY last_message_date DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'user_id2' => $userId,
            'user_id3' => $userId,
            'user_id4' => $userId,
            'user_id5' => $userId,
            'user_id6' => $userId,
            'user_id7' => $userId,
            'user_id8' => $userId,
            'user_id9' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer tous les messages d'une conversation entre deux utilisateurs
     *
     * @param int $userId ID de l'utilisateur connecté
     * @param int $otherUserId ID de l'autre utilisateur
     * @return array Liste des messages de la conversation
     */
    public function getConversationMessages(int $userId, int $otherUserId): array
    {
        $sql = "
            SELECT m.*,
                   sender.pseudo as sender_pseudo,
                   sender.photo as sender_photo,
                   receiver.pseudo as receiver_pseudo,
                   receiver.photo as receiver_photo
            FROM messages m
            INNER JOIN users sender ON m.sender_id = sender.id
            INNER JOIN users receiver ON m.receiver_id = receiver.id
            WHERE (m.sender_id = :user_id AND m.receiver_id = :other_user_id)
               OR (m.sender_id = :other_user_id2 AND m.receiver_id = :user_id2)
            ORDER BY m.created_at ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'other_user_id' => $otherUserId,
            'other_user_id2' => $otherUserId,
            'user_id2' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Envoyer un nouveau message
     *
     * @param int $senderId ID de l'expéditeur
     * @param int $receiverId ID du destinataire
     * @param string $content Contenu du message
     * @param int|null $bookId ID du livre associé (optionnel)
     * @return bool True si succès, false sinon
     */
    public function send(int $senderId, int $receiverId, string $content, ?int $bookId = null): bool
    {
        $sql = "
            INSERT INTO messages (sender_id, receiver_id, content, book_id, is_read, created_at)
            VALUES (:sender_id, :receiver_id, :content, :book_id, 0, NOW())
        ";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'content' => $content,
            'book_id' => $bookId
        ]);
    }

    /**
     * Marquer tous les messages d'une conversation comme lus
     *
     * @param int $userId ID de l'utilisateur connecté (destinataire)
     * @param int $senderId ID de l'expéditeur
     * @return bool True si succès, false sinon
     */
    public function markAsRead(int $userId, int $senderId): bool
    {
        $sql = "
            UPDATE messages
            SET is_read = 1
            WHERE receiver_id = :user_id
              AND sender_id = :sender_id
              AND is_read = 0
        ";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id' => $userId,
            'sender_id' => $senderId
        ]);
    }

    /**
     * Compter le nombre total de messages non lus pour un utilisateur
     *
     * @param int $userId ID de l'utilisateur
     * @return int Nombre de messages non lus
     */
    public function countUnread(int $userId): int
    {
        $sql = "
            SELECT COUNT(*)
            FROM messages
            WHERE receiver_id = :user_id
              AND is_read = 0
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Vérifier si deux utilisateurs ont déjà une conversation
     *
     * @param int $userId1 ID du premier utilisateur
     * @param int $userId2 ID du deuxième utilisateur
     * @return bool True si une conversation existe, false sinon
     */
    public function conversationExists(int $userId1, int $userId2): bool
    {
        $sql = "
            SELECT COUNT(*)
            FROM messages
            WHERE (sender_id = :user_id1 AND receiver_id = :user_id2)
               OR (sender_id = :user_id2_2 AND receiver_id = :user_id1_2)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'user_id1' => $userId1,
            'user_id2' => $userId2,
            'user_id2_2' => $userId2,
            'user_id1_2' => $userId1
        ]);

        return $stmt->fetchColumn() > 0;
    }

    /**
     * Supprimer un message (si l'utilisateur est l'expéditeur)
     *
     * @param int $messageId ID du message
     * @param int $userId ID de l'utilisateur qui supprime
     * @return bool True si succès, false sinon
     */
    public function delete(int $messageId, int $userId): bool
    {
        $sql = "
            DELETE FROM messages
            WHERE id = :message_id
              AND sender_id = :user_id
        ";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'message_id' => $messageId,
            'user_id' => $userId
        ]);
    }
}
