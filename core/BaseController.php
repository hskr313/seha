<?php
class BaseController { // Déclaration de la classe BaseController

    // Méthode protégée pour afficher une vue avec des données et un layout
    protected function view($view, $data = [], $layout = 'main') {
        $viewPath = "../app/views/$view.php"; // Définit le chemin vers le fichier de la vue
        $layoutPath = "../app/views/layouts/$layout.php"; // Définit le chemin vers le fichier du layout

        if (file_exists($viewPath)) { // Vérifie si le fichier de la vue existe
            extract($data); // Extrait les variables du tableau $data pour les rendre disponibles dans la vue
            ob_start(); // Commence la mise en mémoire tampon de la sortie
            require_once $viewPath; // Inclut le fichier de la vue
            $content = ob_get_clean(); // Récupère le contenu mis en tampon et nettoie le tampon
            require_once $layoutPath; // Inclut le fichier du layout
        } else {
            die("View file not found: $viewPath"); // Arrête l'exécution du script et affiche un message d'erreur si la vue n'est pas trouvée
        }
    }
}
?>