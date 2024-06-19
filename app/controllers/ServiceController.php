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
            // Ajoutez d'autres champs ici
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
}
