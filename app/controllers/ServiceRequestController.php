<?php

class ServiceRequestController extends BaseController { // Déclaration de la classe ServiceRequestController qui hérite de BaseController

    // Méthode pour créer une demande de service
    public function createServiceRequest() {
        AuthMiddleware::requireAuth(); // Exige l'authentification pour accéder à cette méthode
        $serviceRequestRepository = new ServiceRequestRepository(); // Crée une instance de ServiceRequestRepository
        $userRepository = new UserRepository(); // Crée une instance de UserRepository

        $data = json_decode(file_get_contents('php://input'), true); // Récupère les données de la requête en JSON

        // Log des données reçues
        error_log('Received data: ' . print_r($data, true));

        $requested_hours = $data['requested_hours']; // Récupère les heures demandées
        $requester_id = $_SESSION['user_id']; // Récupère l'ID du demandeur depuis la session

        // Vérifie si l'utilisateur a suffisamment de crédits temps
        $requester = $userRepository->findById($requester_id);
        if ($requester->time_credit < $requested_hours) { // Si le demandeur n'a pas assez de crédits temps
            echo json_encode(['status' => 'error', 'message' => 'Not enough time credits']); // Retourne un message d'erreur
            exit(); // Arrête l'exécution du script
        }

        // Crée une nouvelle instance de ServiceRequestEntity
        $serviceRequestEntity = new ServiceRequestEntity(
            null, // ID auto-incrémenté
            $data['service_id'], // ID du service
            $requester_id, // ID du demandeur
            $data['provider_id'], // ID du fournisseur
            1, // Statut de la demande (1 = en attente)
            $requested_hours, // Heures demandées
            $data['requested_date'] // Date demandée
        );

        $result = $serviceRequestRepository->create($serviceRequestEntity); // Enregistre la demande de service dans la base de données

        header('Content-Type: application/json'); // Définit le type de contenu de la réponse en JSON
        if ($result) {
            echo json_encode(['status' => 'success', 'data' => $serviceRequestEntity]); // Retourne un message de succès avec les données de la demande
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to create service request']); // Retourne un message d'erreur
        }

        exit(); // Arrête l'exécution du script
    }

    // Méthode pour mettre à jour une demande de service
    public function updateServiceRequest() {
        AuthMiddleware::requireAuth(); // Exige l'authentification pour accéder à cette méthode
        $serviceRequestRepository = new ServiceRequestRepository(); // Crée une instance de ServiceRequestRepository
        $transactionRepository = new TransactionRepository(); // Crée une instance de TransactionRepository
        $userRepository = new UserRepository(); // Crée une instance de UserRepository

        $data = json_decode(file_get_contents('php://input'), true); // Récupère les données de la requête en JSON

        // Log des données reçues
        error_log('Received data: ' . print_r($data, true));

        $serviceRequestId = $data['request_id']; // Récupère l'ID de la demande de service
        $status = $data['status']; // Récupère le statut de la demande (2 = accepté, 3 = rejeté)

        $serviceRequestEntity = $serviceRequestRepository->findById($serviceRequestId); // Récupère la demande de service par son ID
        if (!$serviceRequestEntity) { // Vérifie si la demande de service existe
            error_log('Service request not found with ID: ' . $serviceRequestId); // Log une erreur si la demande n'est pas trouvée
            echo json_encode(['status' => 'error', 'message' => 'Service request not found']); // Retourne un message d'erreur
            exit(); // Arrête l'exécution du script
        }

        // Met à jour le statut de la demande
        $serviceRequestEntity->request_status_id = $status;

        $result = $serviceRequestRepository->update($serviceRequestId, $serviceRequestEntity); // Met à jour la demande de service dans la base de données

        if ($status == 2 && $result) { // Si la demande est acceptée
            // Crée une transaction
            $transactionEntity = new TransactionEntity(
                null, // ID auto-incrémenté
                $serviceRequestEntity->service_id, // ID du service
                $serviceRequestEntity->provider_id, // ID du fournisseur
                $serviceRequestEntity->requester_id, // ID du demandeur
                $serviceRequestEntity->requested_hours, // Heures demandées
                date('Y-m-d H:i:s') // Date et heure actuelles
            );
            $transactionRepository->create($transactionEntity); // Enregistre la transaction dans la base de données

            // Met à jour les crédits temps des utilisateurs
            $provider = $userRepository->findById($serviceRequestEntity->provider_id);
            $requester = $userRepository->findById($serviceRequestEntity->requester_id);

            $provider->time_credit += $serviceRequestEntity->requested_hours; // Augmente le crédit temps du fournisseur
            $requester->time_credit -= $serviceRequestEntity->requested_hours; // Diminue le crédit temps du demandeur

            $userRepository->update($provider->id, $provider); // Met à jour le fournisseur dans la base de données
            $userRepository->update($requester->id, $requester); // Met à jour le demandeur dans la base de données
        }

        header('Content-Type: application/json'); // Définit le type de contenu de la réponse en JSON
        if ($result) {
            echo json_encode(['status' => 'success', 'data' => $serviceRequestEntity]); // Retourne un message de succès avec les données de la demande
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update service request']); // Retourne un message d'erreur
        }

        exit(); // Arrête l'exécution du script
    }
}
?>