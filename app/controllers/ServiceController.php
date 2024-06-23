<?php
class ServiceController extends BaseController {
    public function index() {
        AuthMiddleware::requireAuth();
        $serviceRepository = new ServiceRepository();
        $userId = $_SESSION['user_id'];
        $services = $serviceRepository->findByUserId($userId);
        $this->view('service/index', ['title' => 'My Services', 'services' => $services]);
    }

    public function updateService() {
        AuthMiddleware::requireAuth();
        $serviceRepository = new ServiceRepository();

        $serviceId = $_POST['id'];
        $serviceData = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'is_published' => $_POST['is_published'] ? 1 : 0,
        ];

        $serviceRepository->update($serviceId, $serviceData);

        echo json_encode(['status' => 'success']);
        exit();
    }

    public function deleteService() {
        AuthMiddleware::requireAuth();
        $serviceRepository = new ServiceRepository();

        $serviceId = $_POST['id'];
        $serviceRepository->delete($serviceId);

        echo json_encode(['status' => 'success']);
        exit();
    }

    public function togglePublish() {
        AuthMiddleware::requireAuth();
        $serviceRepository = new ServiceRepository();

        $data = json_decode(file_get_contents('php://input'), true);
        $serviceId = $data['id'];
        $isPublished = $data['is_published'] ? 1 : 0;

        $result = $serviceRepository->update($serviceId, ['is_published' => $isPublished]);

        if ($result) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
        exit();
    }
}
