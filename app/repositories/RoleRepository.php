<?php

class RoleRepository extends BaseRepository
{ // Déclaration de la classe RoleRepository qui hérite de BaseRepository

  // Constructeur de la classe, initialise la table et la classe d'entité
  public function __construct()
  {
    parent::__construct('roles', RoleEntity::class); // Appelle le constructeur de BaseRepository avec le nom de la table et la classe d'entité
  }

  // Méthode pour obtenir l'ID du rôle d'administrateur
  public function getAdminID(): int
  {
    $result = $this->findByCriteria(['role_name' => 'admin']); // Utilise findByCriteria pour récupérer le rôle 'admin'
    return $result ? $result[0]->id : 1; // Retourne l'ID du rôle 'admin' si trouvé, sinon retourne 1 par défaut
  }

  // Méthode pour obtenir l'ID du rôle d'utilisateur
  public function getUserID(): int
  {
    $result = $this->findByCriteria(['role_name' => 'user']); // Utilise findByCriteria pour récupérer le rôle 'user'
    return $result ? $result[0]->id : 2; // Retourne l'ID du rôle 'user' si trouvé, sinon retourne 2 par défaut
  }
}

?>