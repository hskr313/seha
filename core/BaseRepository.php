<?php
class BaseRepository { // Déclaration de la classe BaseRepository
    protected $db; // Propriété pour la connexion à la base de données
    protected $table; // Propriété pour le nom de la table
    protected $entityClass; // Propriété pour le nom de la classe de l'entité

    // Constructeur de la classe, initialise les propriétés et la connexion à la base de données
    public function __construct(string $table, string $entityClass) {
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME); // Crée une nouvelle connexion MySQL
        if ($this->db->connect_error) { // Vérifie s'il y a une erreur de connexion
            die("Database connection failed: " . $this->db->connect_error); // Arrête l'exécution du script et affiche un message d'erreur en cas de problème de connexion
        }
        $this->table = $table; // Initialise la propriété $table
        $this->entityClass = $entityClass; // Initialise la propriété $entityClass
    }

    // Méthode pour récupérer toutes les lignes de la table
    public function findAll(): array {
        $result = $this->db->query("SELECT * FROM {$this->table}"); // Exécute une requête pour sélectionner toutes les lignes de la table
        if (!$result) { // Vérifie si la requête a échoué
            die("Query failed: " . $this->db->error); // Arrête l'exécution du script et affiche un message d'erreur
        }
        $rows = $result->fetch_all(MYSQLI_ASSOC); // Récupère toutes les lignes sous forme de tableau associatif
        return array_map([$this, 'mapToEntity'], $rows); // Transforme chaque ligne en objet entité
    }

    // Méthode pour trouver une ligne par ID
    public function findById(int $id): ?object {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?"); // Prépare une requête pour sélectionner une ligne par ID
        if (!$stmt) { // Vérifie si la préparation de la requête a échoué
            die("Prepare failed: " . $this->db->error); // Arrête l'exécution du script et affiche un message d'erreur
        }
        $stmt->bind_param('i', $id); // Lie le paramètre ID à la requête
        $stmt->execute(); // Exécute la requête
        $result = $stmt->get_result(); // Récupère le résultat de la requête

        if (!$result) { // Vérifie si la requête a échoué
            die ("Database query failed: " . $this->db->error); // Arrête l'exécution du script et affiche un message d'erreur
        }

        $data = $result->fetch_assoc(); // Récupère la ligne sous forme de tableau associatif

        return $data ? $this->mapToEntity($data) : null; // Transforme la ligne en objet entité ou retourne null si aucune ligne n'a été trouvée
    }

    // Méthode pour créer une nouvelle ligne dans la table
    public function create(object $entity): bool {
        $data = $this->extract($entity); // Extrait les données de l'entité
        $columns = implode(", ", array_keys($data)); // Concatène les noms de colonnes
        $placeholders = implode(", ", array_fill(0, count($data), '?')); // Crée des placeholders pour les valeurs
        $values = array_values($data); // Récupère les valeurs des données

        $stmt = $this->db->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)"); // Prépare une requête pour insérer une nouvelle ligne
        if (!$stmt) { // Vérifie si la préparation de la requête a échoué
            die("Prepare failed: " . $this->db->error); // Arrête l'exécution du script et affiche un message d'erreur
        }
        $stmt->bind_param(str_repeat('s', count($values)), ...$values); // Lie les paramètres à la requête
        return $stmt->execute(); // Exécute la requête et retourne le résultat
    }

    // Méthode pour mettre à jour une ligne par ID
    public function update(int $id, object $entity): bool {
        $data = $this->extract($entity); // Extrait les données de l'entité
        $sets = []; // Initialise un tableau pour les sets de la requête SQL
        $values = []; // Initialise un tableau pour les valeurs des sets
        foreach ($data as $column => $value) { // Parcourt les données de l'entité
            $sets[] = "$column = ?"; // Ajoute le set pour la requête SQL
            if (gettype($value) == 'boolean') { // Vérifie si la valeur est un booléen
                $value = (int)$value; // Convertit le booléen en entier (0 ou 1)
            }
            $values[] = $value; // Ajoute la valeur au tableau des valeurs
        }
        $values[] = $id; // Ajoute l'ID à la fin des valeurs
        $setString = implode(", ", $sets); // Concatène les sets pour la requête SQL

        $stmt = $this->db->prepare("UPDATE {$this->table} SET $setString WHERE id = ?"); // Prépare une requête pour mettre à jour une ligne par ID
        if (!$stmt) { // Vérifie si la préparation de la requête a échoué
            error_log('SQL prepare failed: ' . $this->db->error); // Enregistre une erreur dans le journal des erreurs
            return false; // Retourne false pour indiquer l'échec de la mise à jour
        }

        $bindTypes = ''; // Initialise une chaîne pour les types de paramètres

        foreach ($data as $_ => $value) { // Parcourt les données de l'entité pour déterminer les types de paramètres
            if (is_int($value)) {
                $bindTypes .= 'i'; // Ajoute 'i' pour les entiers
            } elseif (is_float($value)) {
                $bindTypes .= 'd'; // Ajoute 'd' pour les flottants
            } else {
                $bindTypes .= 's'; // Ajoute 's' pour les chaînes de caractères
            }
        }

        $bindTypes .= 'i'; // Ajoute 'i' pour l'ID

        $stmt->bind_param($bindTypes, ...$values); // Lie les paramètres à la requête
        $executeResult = $stmt->execute(); // Exécute la requête

        if (!$executeResult) { // Vérifie si l'exécution de la requête a échoué
            error_log('Execute failed: ' . $stmt->error); // Enregistre une erreur dans le journal des erreurs
        }

        return $executeResult; // Retourne le résultat de l'exécution de la requête
    }

    // Méthode pour supprimer une ligne par ID
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?"); // Prépare une requête pour supprimer une ligne par ID
        if (!$stmt) { // Vérifie si la préparation de la requête a échoué
            die("Prepare failed: " . $this->db->error); // Arrête l'exécution du script et affiche un message d'erreur
        }
        $stmt->bind_param('i', $id); // Lie le paramètre ID à la requête
        return $stmt->execute(); // Exécute la requête et retourne le résultat
    }

    // Méthode pour trouver des lignes par critères
    public function findByCriteria(array $criteria): array {
        $query = "SELECT * FROM {$this->table} WHERE "; // Initialise la requête SQL
        $conditions = []; // Initialise un tableau pour les conditions de la requête
        $values = []; // Initialise un tableau pour les valeurs des conditions
        foreach ($criteria as $column => $value) { // Parcourt les critères
            $conditions[] = "$column = ?"; // Ajoute la condition à la requête SQL
            $values[] = $value; // Ajoute la valeur au tableau des valeurs
        }
        $query .= implode(" AND ", $conditions); // Concatène les conditions pour la requête SQL

        $stmt = $this->db->prepare($query); // Prépare la requête SQL
        if (!$stmt) { // Vérifie si la préparation de la requête a échoué
            die("Prepare failed: " . $this->db->error); // Arrête l'exécution du script et affiche un message d'erreur
        }
        $stmt->bind_param(str_repeat('s', count($values)), ...$values); // Lie les paramètres à la requête
        $stmt->execute(); // Exécute la requête
        $result = $stmt->get_result(); // Récupère le résultat de la requête
        if (!$result) { // Vérifie si la requête a échoué
            die("Query failed: " . $this->db->error); // Arrête l'exécution du script et affiche un message d'erreur
        }
        $rows = $result->fetch_all(MYSQLI_ASSOC); // Récupère toutes les lignes sous forme de tableau associatif
        return array_map([$this, 'mapToEntity'], $rows); // Transforme chaque ligne en objet entité
    }

    /**
     * @throws ReflectionException
     */
    // Méthode protégée pour transformer un tableau de données en objet entité
    protected function mapToEntity(array $data): object {
        $reflect = new ReflectionClass($this->entityClass); // Crée une instance de ReflectionClass pour l'entité
        $entity = $reflect->newInstanceWithoutConstructor(); // Crée une nouvelle instance de l'entité sans appeler le constructeur
        foreach ($data as $key => $value) { // Parcourt les données
            if ($reflect->hasProperty($key)) { // Vérifie si l'entité a la propriété correspondante
                $property = $reflect->getProperty($key); // Récupère la propriété de l'entité
                $property->setValue($entity, $value); // Définit la valeur de la propriété
            }
        }
        return $entity; // Retourne l'objet entité
    }

    // Méthode protégée pour extraire les données d'un objet entité sous forme de tableau
    protected function extract(object $entity): array {
        $reflect = new ReflectionClass($entity); // Crée une instance de ReflectionClass pour l'entité
        $properties = $reflect->getProperties(); // Récupère toutes les propriétés de l'entité
        $data = []; // Initialise un tableau pour les données
        foreach ($properties as $property) { // Parcourt les propriétés
            $property->setAccessible(true); // Rendre la propriété accessible
            $data[$property->getName()] = $property->getValue($entity); // Ajoute la valeur de la propriété au tableau des données
        }
        return $data; // Retourne le tableau des données
    }

    /**
     * @throws ReflectionException
     */
    // Méthode pour récupérer les noms des propriétés de l'entité
    public function getEntityProperties(): array {
        $reflect = new ReflectionClass($this->entityClass); // Crée une instance de ReflectionClass pour l'entité
        $properties = $reflect->getProperties(); // Récupère toutes les propriétés de l'entité
        return array_map(function($property) { // Parcourt les propriétés et récupère leurs noms
            return $property->getName();
        }, $properties); // Retourne un tableau des noms des propriétés
    }
}
?>

