<?php
class ServiceRepository extends BaseRepository { // Déclaration de la classe ServiceRepository qui hérite de BaseRepository

    // Constructeur de la classe, initialise la table et la classe d'entité
    public function __construct() {
        parent::__construct('services', ServiceEntity::class); // Appelle le constructeur de BaseRepository avec le nom de la table et la classe d'entité
    }

    // Méthode pour trouver tous les services publiés
    public function findAll(): array {
        $result = $this->db->query("SELECT * FROM {$this->table} WHERE is_published = 1"); // Exécute une requête pour sélectionner tous les services publiés
        if (!$result) { // Vérifie si la requête a échoué
            die("Query failed: " . $this->db->error); // Arrête l'exécution du script et affiche un message d'erreur
        }
        $rows = $result->fetch_all(MYSQLI_ASSOC); // Récupère toutes les lignes sous forme de tableau associatif
        return array_map([$this, 'mapToEntity'], $rows); // Transforme chaque ligne de résultat en objet entité et retourne un tableau d'entités
    }

    // Méthode pour trouver les services par ID de l'utilisateur
    public function findByUserId($userId) {
        return $this->findByCriteria(['user_id' => $userId]); // Utilise findByCriteria pour récupérer les services par ID de l'utilisateur
    }

    // Méthode pour trouver les services par ID de la catégorie
    public function findByCategoryId($categoryId) {
        return $this->findByCriteria(['category_id' => $categoryId]); // Utilise findByCriteria pour récupérer les services par ID de la catégorie
    }

    // Méthode pour trouver tous les services groupés par catégorie
    public function findAllGroupedByCategory() {
        $query = "SELECT s.*, c.category_name as category_name FROM {$this->table} s JOIN categories c ON s.category_id = c.id WHERE s.is_published = true ORDER BY category_id"; // Requête SQL pour sélectionner tous les services publiés et les grouper par catégorie
        $result = $this->db->query($query); // Exécute la requête SQL
        if (!$result) { // Vérifie si la requête a échoué
            die("Query failed: " . $this->db->error); // Arrête l'exécution du script et affiche un message d'erreur
        }

        $rows = $result->fetch_all(MYSQLI_ASSOC); // Récupère toutes les lignes sous forme de tableau associatif
        $grouped = []; // Initialise un tableau pour les services groupés par catégorie
        foreach ($rows as $row) { // Parcourt toutes les lignes de résultat
            $categoryId = $row['category_id']; // Récupère l'ID de la catégorie
            if (!isset($grouped[$categoryId])) { // Si la catégorie n'existe pas encore dans le tableau
                $grouped[$categoryId] = [
                    'category_name' => $row['category_name'], // Ajoute le nom de la catégorie
                    'services' => [] // Initialise un tableau pour les services de la catégorie
                ];
            }
            $grouped[$categoryId]['services'][] = $this->mapToEntity($row); // Ajoute le service à la catégorie correspondante
        }
        return $grouped; // Retourne le tableau des services groupés par catégorie
    }

    // Méthode pour trouver toutes les catégories
    public function findAllCategories() {
        $query = "SELECT * FROM categories"; // Requête SQL pour sélectionner toutes les catégories
        $result = $this->db->query($query); // Exécute la requête SQL
        if (!$result) { // Vérifie si la requête a échoué
            die("Query failed: " . $this->db->error); // Arrête l'exécution du script et affiche un message d'erreur
        }
        return $result->fetch_all(MYSQLI_ASSOC); // Retourne toutes les lignes sous forme de tableau associatif
    }

    // Méthode pour trouver un service par son ID
    public function findById(int $id): ?ServiceEntity {
        return parent::findById($id); // Utilise la méthode findById de BaseRepository pour récupérer le service par son ID
    }

    // Méthode pour rechercher des services par une requête
    public function searchServices($query) {
        $query = "%{$query}%"; // Prépare la requête pour la recherche en ajoutant des jokers
        $stmt = $this->db->prepare("
        SELECT s.*, u.username 
        FROM services s
        JOIN users u ON s.user_id = u.id
        WHERE s.is_published = 1 AND (s.name LIKE ? OR s.description LIKE ? OR u.username LIKE ?) "); // Prépare une requête SQL pour rechercher des services par nom, description ou nom d'utilisateur
        if (!$stmt) { // Vérifie si la préparation de la requête a échoué
            die("Prepare failed: " . $this->db->error); // Arrête l'exécution du script et affiche un message d'erreur
        }
        $stmt->bind_param('sss', $query, $query, $query); // Lie les paramètres de la requête
        $stmt->execute(); // Exécute la requête
        $result = $stmt->get_result(); // Récupère le résultat de la requête
        if (!$result) { // Vérifie si la requête a échoué
            die("Query failed: " . $this->db->error); // Arrête l'exécution du script et affiche un message d'erreur
        }
        $rows = $result->fetch_all(MYSQLI_ASSOC); // Récupère toutes les lignes sous forme de tableau associatif
        return array_map([$this, 'mapToEntity'], $rows); // Transforme chaque ligne de résultat en objet entité et retourne un tableau d'entités
    }

    // Méthode pour trouver tous les services groupés par catégorie avec les noms d'utilisateur
    public function findAllGroupedByCategoryWithUsernames() {
        $query = "
            SELECT s.*, c.category_name, u.username 
            FROM services s
            JOIN categories c ON s.category_id = c.id
            JOIN users u ON s.user_id = u.id
            WHERE s.is_published = true
            ORDER BY s.category_id"; // Requête SQL pour sélectionner tous les services publiés, les grouper par catégorie et inclure les noms d'utilisateur
        $result = $this->db->query($query); // Exécute la requête SQL
        if (!$result) { // Vérifie si la requête a échoué
            die("Query failed: " . $this->db->error); // Arrête l'exécution du script et affiche un message d'erreur
        }

        $rows = $result->fetch_all(MYSQLI_ASSOC); // Récupère toutes les lignes sous forme de tableau associatif
        $grouped = []; // Initialise un tableau pour les services groupés par catégorie
        foreach ($rows as $row) { // Parcourt toutes les lignes de résultat
            $categoryId = $row['category_id']; // Récupère l'ID de la catégorie
            if (!isset($grouped[$categoryId])) { // Si la catégorie n'existe pas encore dans le tableau
                $grouped[$categoryId] = [
                    'category_name' => $row['category_name'], // Ajoute le nom de la catégorie
                    'services' => [] // Initialise un tableau pour les services de la catégorie
                ];
            }
            $service = $this->mapToEntity($row); // Transforme la ligne de résultat en objet entité
            $service->username = $row['username']; // Ajoute le nom d'utilisateur à l'entité du service
            $grouped[$categoryId]['services'][] = $service; // Ajoute le service à la catégorie correspondante
        }
        return $grouped; // Retourne le tableau des services groupés par catégorie
    }
}
?>