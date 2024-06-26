<?php

class ServiceRequestController extends BaseController {
    public function createServiceRequest() {
        AuthMiddleware::requireAuth();
        $serviceRequestRepository = new ServiceRequestRepository();
        $userRepository = new UserRepository();

        $data = json_decode(file_get_contents('php://input'), true);

        // Log incoming data
        error_log('Received data: ' . print_r($data, true));

        $requested_hours = $data['requested_hours'];
        $requester_id = $_SESSION['user_id'];

        // Check if the user has enough time credits
        $requester = $userRepository->findById($requester_id);
        if ($requester->time_credit < $requested_hours) {
            echo json_encode(['status' => 'error', 'message' => 'Not enough time credits']);
            exit();
        }

        $serviceRequestEntity = new ServiceRequestEntity(
            null, // ID auto-incrémenté
            $data['service_id'],
            $requester_id, // ID du demandeur
            $data['provider_id'],
            1, // Statut de la demande (1 = en attente)
            $requested_hours,
            $data['requested_date']
        );

        $result = $serviceRequestRepository->create($serviceRequestEntity);

        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['status' => 'success', 'data' => $serviceRequestEntity]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to create service request']);
        }

        exit();
    }

    public function updateServiceRequest() {
        AuthMiddleware::requireAuth();
        $serviceRequestRepository = new ServiceRequestRepository();
        $transactionRepository = new TransactionRepository();
        $userRepository = new UserRepository();

        $data = json_decode(file_get_contents('php://input'), true);

        // Log incoming data
        error_log('Received data: ' . print_r($data, true));

        $serviceRequestId = $data['request_id'];
        $status = $data['status']; // 2 = accepté, 3 = rejeté

        $serviceRequestEntity = $serviceRequestRepository->findById($serviceRequestId);
        if (!$serviceRequestEntity) {
            error_log('Service request not found with ID: ' . $serviceRequestId);
            echo json_encode(['status' => 'error', 'message' => 'Service request not found']);
            exit();
        }

        // Update the request status
        $serviceRequestEntity->request_status_id = $status;

        $result = $serviceRequestRepository->update($serviceRequestId, $serviceRequestEntity);

        if ($status == 2 && $result) { // If accepted
            // Create transaction
            $transactionEntity = new TransactionEntity(
                null,
                $serviceRequestEntity->service_id,
                $serviceRequestEntity->provider_id,
                $serviceRequestEntity->requester_id,
                $serviceRequestEntity->requested_hours,
                date('Y-m-d H:i:s')
            );
            $transactionRepository->create($transactionEntity);

            // Update user time credits
            $provider = $userRepository->findById($serviceRequestEntity->provider_id);
            $requester = $userRepository->findById($serviceRequestEntity->requester_id);

            $provider->time_credit += $serviceRequestEntity->requested_hours;
            $requester->time_credit -= $serviceRequestEntity->requested_hours;

            $userRepository->update($provider->id, $provider);
            $userRepository->update($requester->id, $requester);
        }

        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['status' => 'success', 'data' => $serviceRequestEntity]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update service request']);
        }

        exit();
    }
}
