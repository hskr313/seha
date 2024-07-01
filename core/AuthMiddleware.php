<?php
class AuthMiddleware { // Déclaration de la classe AuthMiddleware

    // Méthode statique pour vérifier si l'utilisateur est authentifié
    public static function isAuthenticated() {
        return isset($_SESSION['user_id']); // Vérifie si la variable de session 'user_id' est définie
    }

    // Méthode statique pour obtenir l'utilisateur authentifié
    public static function getUser(): ?UserEntity {
        if (!self::isAuthenticated()) { // Vérifie si l'utilisateur n'est pas authentifié
            return null; // Retourne null si l'utilisateur n'est pas authentifié
        }
        $userRepository = new UserRepository(); // Crée une nouvelle instance de UserRepository
        return $userRepository->findById($_SESSION['user_id']); // Retourne l'utilisateur correspondant à l'ID de session
    }

    // Méthode statique pour vérifier si l'utilisateur a un rôle spécifique
    public static function hasRole($roleName) {
        $user = self::getUser(); // Obtient l'utilisateur authentifié
        if (!$user) { // Vérifie si aucun utilisateur n'est authentifié
            return false; // Retourne false si l'utilisateur n'est pas authentifié
        }
        $roleRepository = new RoleRepository(); // Crée une nouvelle instance de RoleRepository
        $role = $roleRepository->findById($user->role_id); // Récupère le rôle de l'utilisateur à partir de son ID de rôle
        return $role && $role->name === $roleName; // Retourne true si le rôle de l'utilisateur correspond au rôle spécifié
    }

    // Méthode statique pour exiger l'authentification
    public static function requireAuth() {
        if (!self::isAuthenticated()) { // Vérifie si l'utilisateur n'est pas authentifié
            header('Location: /seha/public/auth/login'); // Redirige vers la page de connexion
            exit(); // Arrête l'exécution du script
        }
    }

    // Méthode statique pour exiger un rôle spécifique
    public static function requireRole($roleName) {
        if (!self::hasRole($roleName)) { // Vérifie si l'utilisateur n'a pas le rôle spécifié
            header('Location: /seha/public/market-place'); // Redirige vers la page du marché
            exit(); // Arrête l'exécution du script
        }
    }
}


