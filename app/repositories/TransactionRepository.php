<?php

class TransactionRepository extends BaseRepository
{ // Déclaration de la classe TransactionRepository qui hérite de BaseRepository

  // Constructeur de la classe, initialise la table et la classe d'entité
  public function __construct()
  {
    parent::__construct('transactions', TransactionEntity::class); // Appelle le constructeur de BaseRepository avec le nom de la table et la classe d'entité
  }

  // Méthode pour trouver les transactions par ID de l'utilisateur
  public function findByUserId($userId)
  {
    $query = "SELECT * FROM {$this->table} WHERE provider_id = ? OR receiver_id = ?"; // Requête SQL pour sélectionner les transactions où l'utilisateur est fournisseur ou receveur
    $stmt = $this->db->prepare($query); // Prépare la requête SQL
    if (!$stmt) { // Vérifie si la préparation de la requête a échoué
      die("Prepare failed: " . $this->db->error); // Arrête l'exécution du script et affiche un message d'erreur
    }
    $stmt->bind_param('ii', $userId, $userId); // Lie les paramètres de la requête
    $stmt->execute(); // Exécute la requête
    $result = $stmt->get_result(); // Récupère le résultat de la requête
    if (!$result) { // Vérifie si la requête a échoué
      die("Query failed: " . $this->db->error); // Arrête l'exécution du script et affiche un message d'erreur
    }
    $rows = $result->fetch_all(MYSQLI_ASSOC); // Récupère toutes les lignes sous forme de tableau associatif
    return array_map([$this, 'mapToEntity'], $rows); // Transforme chaque ligne de résultat en objet entité et retourne un tableau d'entités
  }
}

?>