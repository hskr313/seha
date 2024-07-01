<?php
class UserController extends BaseController { // Déclaration de la classe UserController qui hérite de BaseController

    // Méthode pour afficher le profil de l'utilisateur
    public function profile() {
        AuthMiddleware::requireAuth(); // Exige l'authentification pour accéder à cette méthode
        $user = AuthMiddleware::getUser(); // Récupère l'utilisateur authentifié
        $this->view('user/profile', ['title' => 'My Profile', 'user' => $user]); // Affiche la vue du profil utilisateur avec les données de l'utilisateur
    }

    // Méthode pour mettre à jour le profil de l'utilisateur
    public function updateProfile() {
        AuthMiddleware::requireAuth(); // Exige l'authentification pour accéder à cette méthode
        $userRepository = new UserRepository(); // Crée une instance de UserRepository
        $user = $userRepository->findById($_SESSION['user_id']); // Récupère l'utilisateur par son ID de session

        // Met à jour les informations de l'utilisateur
        $user->username = $_POST['username']; // Met à jour le nom d'utilisateur
        $user->email = $_POST['email']; // Met à jour l'email

        if ($_POST['time_credit']) { // Vérifie si une tentative de mise à jour des crédits temps est présente
            throw new Error(); // Lance une erreur si c'est le cas
        }

        // Met à jour l'utilisateur dans la base de données
        $userRepository->update($user->id, [
            'username' => $user->username,
            'email' => $user->email,
        ]);

        header('Location: /seha/public/user/profile'); // Redirige vers la page de profil utilisateur
        exit(); // Arrête l'exécution du script
    }
}
?>