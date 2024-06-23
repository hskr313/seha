<?php
class ServiceController extends BaseController {
    public function index() {
        AuthMiddleware::requireAuth();
        $serviceRepository = new ServiceRepository();
        $userId = $_SESSION['user_id'];
        $services = $serviceRepository->findByUserId($userId);
        $this->view('service/index', ['title' => 'My Services', 'services' => $services]);
    }

    public function createService() {
        AuthMiddleware::requireAuth();
        $serviceRepository = new ServiceRepository();

        $data = json_decode(file_get_contents('php://input'), true);

        // Log incoming data
        error_log('Received data: ' . print_r($data, true));

        // Create and validate the ServiceEntity object
        $serviceEntity = new ServiceEntity(
            null,
            $_SESSION['user_id'],
            $data['category_id'] ?? null,
            $data['name'] ?? '',
            $data['description'] ?? '',
            isset($data['is_published']) ? 1 : 0
        );

        // Log the hydrated entity
        error_log('Created entity: ' . print_r($serviceEntity, true));

        $result = $serviceRepository->create($serviceEntity);

        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }

        exit();
    }

    public function updateService() {
        AuthMiddleware::requireAuth();
        $serviceRepository = new ServiceRepository();

        $data = json_decode(file_get_contents('php://input'), true);

        // Log incoming data
        error_log('Received data: ' . print_r($data, true));

        $serviceId = $data['id'];
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';

        $serviceEntity = $serviceRepository->findById($serviceId);
        $serviceEntity->name = $name;
        $serviceEntity->description = $description;

        // Log the created entity
        error_log('Created entity: ' . print_r($serviceEntity, true));

        $result = $serviceRepository->update($serviceId, $serviceEntity);

        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }

        exit();
    }

    public function deleteService() {
        AuthMiddleware::requireAuth();
        $serviceRepository = new ServiceRepository();

        $data = json_decode(file_get_contents('php://input'), true);
        $serviceId = $data['id'];

        $result = $serviceRepository->delete($serviceId);

        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
        exit();
    }

    public function togglePublish() {
        AuthMiddleware::requireAuth();
        $serviceRepository = new ServiceRepository();

        $data = json_decode(file_get_contents('php://input'), true);

        // Log incoming data
        error_log('Received data: ' . print_r($data, true));

        $serviceId = $data['id'];
        $isPublished = isset($data['is_published']) ? 1 : 0;

        $serviceEntity = $serviceRepository->findById($serviceId);
        $serviceEntity->is_published = $isPublished;

        $result = $serviceRepository->update($serviceId, $serviceEntity);

        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
        exit();
    }
}
