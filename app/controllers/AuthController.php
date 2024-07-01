<?php
class AuthController extends BaseController { // Déclaration de la classe AuthController qui hérite de BaseController
    private $userRepository; // Propriété pour le dépôt des utilisateurs
    private $roleRepository; // Propriété pour le dépôt des rôles

    // Constructeur de la classe, initialise les propriétés de dépôt
    public function __construct() {
        $this->userRepository = new UserRepository(); // Initialise userRepository
        $this->roleRepository = new RoleRepository(); // Initialise roleRepository
    }

    // Méthode pour afficher la page de connexion
    public function login() {
        if (AuthMiddleware::isAuthenticated()) { // Vérifie si l'utilisateur est déjà authentifié
            header('Location: /seha/public'); // Redirige vers la page d'accueil si l'utilisateur est authentifié
            exit(); // Arrête l'exécution du script
        }

        $this->view('auth/login', ['title' => 'Login'], 'auth'); // Affiche la vue de connexion avec le layout 'auth'
    }

    // Méthode pour traiter les données du formulaire de connexion
    public function loginPost() {
        session_start(); // Démarre la session
        $email = $_POST['email']; // Récupère l'email du formulaire POST
        $password = $_POST['password']; // Récupère le mot de passe du formulaire POST

        $user = $this->userRepository->findByEmail($email); // Recherche l'utilisateur par email

        if ($user && password_verify($password, $user->password)) { // Vérifie si l'utilisateur existe et si le mot de passe est correct
            $_SESSION['user_id'] = $user->id; // Définit l'ID de l'utilisateur dans la session
            header('Location: /seha/public/'); // Redirige vers la page d'accueil
        } else {
            header('Location: /seha/public/auth/login?error=invalid_credentials'); // Redirige vers la page de connexion avec un message d'erreur
        }
        exit(); // Arrête l'exécution du script
    }

    // Méthode pour afficher la page d'inscription
    public function register() {
        $this->view('auth/register', ['title' => 'Register'], 'auth'); // Affiche la vue d'inscription avec le layout 'auth'
    }

    // Méthode pour traiter les données du formulaire d'inscription
    public function registerPost() {
        $name = $_POST['name']; // Récupère le nom du formulaire POST
        $email = $_POST['email']; // Récupère l'email du formulaire POST
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hache le mot de passe

        $user = new UserEntity(
            null, // ID (null car il sera auto-incrémenté)
            $name, // Nom
            $password, // Mot de passe haché
            $email // Email
        );

        $this->userRepository->create($user); // Crée un nouvel utilisateur dans le dépôt

        header('Location: /seha/public/auth/login'); // Redirige vers la page de connexion
        exit(); // Arrête l'exécution du script
    }

    // Méthode pour déconnecter l'utilisateur
    public function logout() {
        session_destroy(); // Détruit la session
        header('Location: /seha/public/auth/login'); // Redirige vers la page de connexion
        exit(); // Arrête l'exécution du script
    }
}
?>