<?php
class MarketPlaceController extends BaseController { // Déclaration de la classe MarketPlaceController qui hérite de BaseController

    // Méthode pour afficher la page principale du marché
    public function index() {
        AuthMiddleware::requireAuth(); // Exige l'authentification pour accéder à cette méthode
        $serviceRepository = new ServiceRepository(); // Crée une instance de ServiceRepository
        $userRepository = new UserRepository(); // Crée une instance de UserRepository
        $services = $serviceRepository->findAll(); // Récupère tous les services

        foreach ($services as $service) { // Parcourt tous les services
            $service->username = $userRepository->findById($service->user_id)->username; // Ajoute le nom d'utilisateur au service
        }

        $categories = $serviceRepository->findAllCategories(); // Récupère toutes les catégories de services
        $this->view('market-place/index', [ // Affiche la vue du marché avec les données des services et des catégories
            'title' => 'Welcome to Service Exchange',
            'services' => $services,
            'categories' => $categories
        ]);
    }

    // Méthode pour rechercher des services
    public function search() {
        AuthMiddleware::requireAuth(); // Exige l'authentification pour accéder à cette méthode
        $query = $_GET['query'] ?? ''; // Récupère le terme de recherche depuis la requête GET
        $serviceRepository = new ServiceRepository(); // Crée une instance de ServiceRepository
        $userRepository = new UserRepository(); // Crée une instance de UserRepository
        $services = $serviceRepository->searchServices($query); // Recherche les services correspondant au terme de recherche

        foreach ($services as $service) { // Parcourt tous les services trouvés
            $service->username = $userRepository->findById($service->user_id)->username; // Ajoute le nom d'utilisateur au service
        }

        header('Content-Type: application/json'); // Définit le type de contenu de la réponse en JSON
        echo json_encode($services); // Encode les services en JSON et les affiche
        exit(); // Arrête l'exécution du script
    }

    // Méthode pour demander un service
    public function requestService() {
        AuthMiddleware::requireAuth(); // Exige l'authentification pour accéder à cette méthode
        $data = json_decode(file_get_contents('php://input'), true); // Récupère les données de la requête en JSON

        $serviceId = $data['service_id']; // Récupère l'ID du service depuis les données
        $hours = $data['hours']; // Récupère le nombre d'heures demandées depuis les données

        $serviceRepository = new ServiceRepository(); // Crée une instance de ServiceRepository
        $userRepository = new UserRepository(); // Crée une instance de UserRepository
        $transactionRepository = new TransactionRepository(); // Crée une instance de TransactionRepository

        $service = $serviceRepository->findById($serviceId); // Récupère le service par son ID
        $providerId = $service->user_id; // Récupère l'ID du fournisseur du service
        $receiverId = $_SESSION['user_id']; // Récupère l'ID de l'utilisateur connecté

        if ($providerId === $receiverId) { // Vérifie si l'utilisateur essaie de demander son propre service
            echo json_encode(['success' => false, 'message' => 'You cannot request your own service.']); // Retourne un message d'erreur
            exit(); // Arrête l'exécution du script
        }

        $receiverCredit = $userRepository->getTimeCredit($receiverId); // Récupère le crédit temps de l'utilisateur
        if ($receiverCredit < $hours) { // Vérifie si l'utilisateur a suffisamment de crédit temps
            echo json_encode(['success' => false, 'message' => 'Insufficient time credit.']); // Retourne un message d'erreur
            exit(); // Arrête l'exécution du script
        }

        $transaction = new TransactionEntity( // Crée une nouvelle transaction
            null, // ID (null car il sera auto-incrémenté)
            $serviceId, // ID du service
            $providerId, // ID du fournisseur
            $receiverId, // ID du demandeur
            $hours, // Nombre d'heures demandées
            date('Y-m-d H:i:s') // Date et heure actuelles
        );
        $transactionRepository->create($transaction); // Enregistre la transaction dans la base de données

        $providerCredit = $userRepository->getTimeCredit($providerId); // Récupère le crédit temps du fournisseur
        $userRepository->updateTimeCredit($receiverId, $receiverCredit - $hours); // Met à jour le crédit temps du demandeur
        $userRepository->updateTimeCredit($providerId, $providerCredit + $hours); // Met à jour le crédit temps du fournisseur

        echo json_encode(['success' => true]); // Retourne un message de succès
        exit(); // Arrête l'exécution du script
    }
}
?>