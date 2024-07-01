<?php
class ServiceController extends BaseController { // Déclaration de la classe ServiceController qui hérite de BaseController
    // Méthode pour afficher la page des services de l'utilisateur connecté
    public function index() {
        AuthMiddleware::requireAuth(); // Exige l'authentification pour accéder à cette méthode
        $serviceRepository = new ServiceRepository(); // Crée une instance de ServiceRepository
        $userId = $_SESSION['user_id']; // Récupère l'ID de l'utilisateur connecté
        $services = $serviceRepository->findByUserId($userId); // Récupère les services de l'utilisateur
        $categoryRepository = new CategoryRepository(); // Crée une instance de CategoryRepository

        $servicesWithCategoryNames = []; // Initialise un tableau pour les services avec les noms de catégories
        foreach ($services as $service) { // Parcourt tous les services
            $category = $categoryRepository->findById($service->category_id); // Récupère la catégorie du service
            $serviceWithCategoryName = (object) [ // Crée un objet avec les données du service et le nom de la catégorie
                'id' => $service->id,
                'user_id' => $service->user_id,
                'category_id' => $service->category_id,
                'name' => $service->name,
                'description' => $service->description,
                'is_published' => $service->is_published,
                'category_name' => $category->category_name
            ];
            $servicesWithCategoryNames[] = $serviceWithCategoryName; // Ajoute l'objet au tableau
        }

        $categories = $categoryRepository->findAll(); // Récupère toutes les catégories

        $this->view('service/index', [ // Affiche la vue des services avec les données des services et des catégories
            'title' => 'My Services',
            'services' => $servicesWithCategoryNames,
            'categories' => $categories
        ]);
    }

    // Méthode pour créer un nouveau service
    public function createService() {
        AuthMiddleware::requireAuth(); // Exige l'authentification pour accéder à cette méthode
        $serviceRepository = new ServiceRepository(); // Crée une instance de ServiceRepository

        $data = json_decode(file_get_contents('php://input'), true); // Récupère les données de la requête en JSON

        // Log des données reçues
        error_log('Received data: ' . print_r($data, true));

        // Crée et valide l'objet ServiceEntity
        $serviceEntity = new ServiceEntity(
            null, // Permet null pour l'ID
            $_SESSION['user_id'], // ID de l'utilisateur connecté
            $data['category_id'] ?? null, // ID de la catégorie
            $data['name'] ?? '', // Nom du service
            $data['description'] ?? '', // Description du service
            isset($data['is_published']) ? 1 : 0 // État de publication
        );

        // Log de l'entité hydratée
        error_log('Created entity: ' . print_r($serviceEntity, true));

        $result = $serviceRepository->create($serviceEntity); // Enregistre le service dans la base de données

        header('Content-Type: application/json'); // Définit le type de contenu de la réponse en JSON
        if ($result) {
            echo json_encode(['status' => 'success']); // Retourne un message de succès
        } else {
            echo json_encode(['status' => 'error']); // Retourne un message d'erreur
        }

        exit(); // Arrête l'exécution du script
    }

    // Méthode pour mettre à jour un service existant
    public function updateService() {
        AuthMiddleware::requireAuth(); // Exige l'authentification pour accéder à cette méthode
        $serviceRepository = new ServiceRepository(); // Crée une instance de ServiceRepository

        $data = json_decode(file_get_contents('php://input'), true); // Récupère les données de la requête en JSON

        if (!isset($data['id'])) { // Vérifie si l'ID du service est présent
            error_log('ID is missing from the payload'); // Log une erreur si l'ID est manquant
            echo json_encode(['status' => 'error', 'message' => 'ID is required']); // Retourne un message d'erreur
            exit(); // Arrête l'exécution du script
        }

        $serviceId = (int)$data['id']; // Convertit l'ID en entier
        $name = $data['name'] ?? ''; // Récupère le nom du service
        $description = $data['description'] ?? ''; // Récupère la description du service
        $isPublished = isset($data['is_published']) ? (int)$data['is_published'] : 0; // Récupère l'état de publication

        $serviceEntity = $serviceRepository->findById($serviceId); // Récupère le service par son ID

        if (!$serviceEntity) { // Vérifie si le service existe
            error_log('Service not found with ID: ' . $serviceId); // Log une erreur si le service n'est pas trouvé
            echo json_encode(['status' => 'error', 'message' => 'Service not found']); // Retourne un message d'erreur
            exit(); // Arrête l'exécution du script
        }

        // Met à jour les propriétés du service
        $serviceEntity->name = $name;
        $serviceEntity->description = $description;
        $serviceEntity->is_published = $isPublished;

        $result = $serviceRepository->update($serviceId, $serviceEntity); // Met à jour le service dans la base de données

        header('Content-Type: application/json'); // Définit le type de contenu de la réponse en JSON
        if ($result) {
            echo json_encode(['status' => 'success', 'data' => $serviceEntity]); // Retourne un message de succès avec les données du service
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update service']); // Retourne un message d'erreur
        }

        exit(); // Arrête l'exécution du script
    }

    // Méthode pour supprimer un service
    public function deleteService() {
        AuthMiddleware::requireAuth(); // Exige l'authentification pour accéder à cette méthode
        $serviceRepository = new ServiceRepository(); // Crée une instance de ServiceRepository

        $data = json_decode(file_get_contents('php://input'), true); // Récupère les données de la requête en JSON
        $serviceId = $data['id']; // Récupère l'ID du service

        $result = $serviceRepository->delete($serviceId); // Supprime le service de la base de données

        header('Content-Type: application/json'); // Définit le type de contenu de la réponse en JSON
        if ($result) {
            echo json_encode(['status' => 'success']); // Retourne un message de succès
        } else {
            echo json_encode(['status' => 'error']); // Retourne un message d'erreur
        }
        exit(); // Arrête l'exécution du script
    }
}
?>