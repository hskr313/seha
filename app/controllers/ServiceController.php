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

        $serviceData = [
            'user_id' => $_SESSION['user_id'],
            'category_id' => $data['category_id'] ?? null,
            'service_type' => $data['service_type'] ?? '',
            'title' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
            'is_published' => isset($data['is_published']) ? 1 : 0,
        ];

        // Log processed service data
        error_log('Processed service data: ' . print_r($serviceData, true));

        $result = $serviceRepository->create($serviceData);

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
        $serviceData = [
            'service_type' => $data['name'] ?? '',
            'description' => $data['description'] ?? '',
            'is_published' => isset($data['is_published']) ? 1 : 0,
        ];

        // Log processed service data
        error_log('Processed service data: ' . print_r($serviceData, true));

        $result = $serviceRepository->update($serviceId, $serviceData);

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

        $result = $serviceRepository->update($serviceId, ['is_published' => $isPublished]);

        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
        exit();
    }
}
?>
