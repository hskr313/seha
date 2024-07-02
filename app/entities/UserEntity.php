<?php
class UserEntity {
    // Déclaration des propriétés de la classe UserEntity
    public ?int $id; // Identifiant de l'utilisateur, peut être nul
    public string $username; // Nom d'utilisateur
    public string $password; // Mot de passe
    public string $email; // Adresse e-mail
    public int $time_credit; // Crédit temps
    public int $role_id; // Identifiant du rôle de l'utilisateur

    // Constructeur de la classe UserEntity
    public function __construct(
        ?int $id = null, // Identifiant, par défaut nul
        string $username = '', // Nom d'utilisateur, par défaut une chaîne vide
        string $password = '', // Mot de passe, par défaut une chaîne vide
        string $email = '', // Adresse e-mail, par défaut une chaîne vide
        int $time_credit = 10, // Crédit temps, par défaut 10
        int $role_id = 2 // Identifiant du rôle, par défaut 2 (probablement un utilisateur standard)
    ) {
        // Initialisation des propriétés de la classe avec les valeurs fournies ou par défaut
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->time_credit = $time_credit;
        $this->role_id = $role_id;
    }
}
