<?php
class MessageRepository extends BaseRepository { // Déclaration de la classe MessageRepository qui hérite de BaseRepository

    // Constructeur de la classe, initialise la table et la classe d'entité
    public function __construct() {
        parent::__construct('messages', MessageEntity::class); // Appelle le constructeur de BaseRepository avec le nom de la table et la classe d'entité
    }

    // Méthode pour trouver les messages par ID de l'expéditeur
    public function findBySenderId(int $sender_id): array {
        return $this->findByCriteria(['sender_id' => $sender_id]); // Utilise findByCriteria pour récupérer les messages par ID de l'expéditeur
    }

    // Méthode pour trouver les messages par ID du destinataire
    public function findByReceiverId(int $receiver_id): array {
        return $this->findByCriteria(['receiver_id' => $receiver_id]); // Utilise findByCriteria pour récupérer les messages par ID du destinataire
    }

    // Méthode pour trouver une conversation entre deux utilisateurs
    public function findByConversation(int $user1_id, int $user2_id): array {
        $query = "SELECT * FROM {$this->table} WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY sent_at"; // Requête SQL pour trouver les messages échangés entre deux utilisateurs
        $stmt = $this->db->prepare($query); // Prépare la requête SQL
        $stmt->bind_param('iiii', $user1_id, $user2_id, $user2_id, $user1_id); // Lie les paramètres de la requête
        $stmt->execute(); // Exécute la requête
        $result = $stmt->get_result(); // Récupère le résultat de la requête
        return array_map([$this, 'mapToEntity'], $result->fetch_all(MYSQLI_ASSOC)); // Transforme chaque ligne de résultat en objet entité et retourne un tableau d'entités
    }

    // Méthode pour trouver toutes les conversations de l'utilisateur
    public function findAllConversations(int $user_id): array {
        $query = "
        SELECT m1.*
        FROM {$this->table} m1
        INNER JOIN (
            SELECT 
                LEAST(sender_id, receiver_id) AS user1_id, 
                GREATEST(sender_id, receiver_id) AS user2_id, 
                MAX(sent_at) AS max_sent_at
            FROM {$this->table}
            WHERE sender_id = ? OR receiver_id = ?
            GROUP BY user1_id, user2_id
        ) m2 ON (m1.sender_id = m2.user1_id AND m1.receiver_id = m2.user2_id AND m1.sent_at = m2.max_sent_at)
            OR (m1.sender_id = m2.user2_id AND m1.receiver_id = m2.user1_id AND m1.sent_at = m2.max_sent_at)
        ORDER BY m1.sent_at DESC"; // Requête SQL pour trouver toutes les conversations de l'utilisateur

        $stmt = $this->db->prepare($query); // Prépare la requête SQL
        $stmt->bind_param('ii', $user_id, $user_id); // Lie les paramètres de la requête
        $stmt->execute(); // Exécute la requête
        $result = $stmt->get_result(); // Récupère le résultat de la requête
        return array_map([$this, 'mapToEntity'], $result->fetch_all(MYSQLI_ASSOC)); // Transforme chaque ligne de résultat en objet entité et retourne un tableau d'entités
    }

    // Méthode pour compter le nombre de messages non lus
    public function countUnreadMessages(int $userId): int {
        $query = "SELECT COUNT(*) AS unreadCount FROM {$this->table} WHERE receiver_id = ? AND read_at IS NULL"; // Requête SQL pour compter le nombre de messages non lus
        $stmt = $this->db->prepare($query); // Prépare la requête SQL
        $stmt->bind_param('i', $userId); // Lie le paramètre de la requête
        $stmt->execute(); // Exécute la requête
        $result = $stmt->get_result(); // Récupère le résultat de la requête
        $row = $result->fetch_assoc(); // Récupère la ligne de résultat
        return (int) $row['unreadCount']; // Retourne le nombre de messages non lus
    }
}
?>
