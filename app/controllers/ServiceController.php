<?php
class ServiceController extends BaseController {
    public function index() {
        AuthMiddleware::requireAuth();
        $serviceRepository = new ServiceRepository();
        $userId = $_SESSION['user_id'];
        $services = $serviceRepository->findByUserId($userId);
        $categoryRepository = new CategoryRepository();

        $servicesWithCategoryNames = [];
        foreach ($services as $service) {
            $category = $categoryRepository->findById($service->category_id);
            $serviceWithCategoryName = (object) [
                'id' => $service->id,
                'user_id' => $service->user_id,
                'category_id' => $service->category_id,
                'name' => $service->name,
                'description' => $service->description,
                'is_published' => $service->is_published,
                'category_name' => $category->category_name
            ];
            $servicesWithCategoryNames[] = $serviceWithCategoryName;
        }

        $categories = $categoryRepository->findAll();

        $this->view('service/index', [
            'title' => 'My Services',
            'services' => $servicesWithCategoryNames,
            'categories' => $categories
        ]);
    }

    public function createService() {
        AuthMiddleware::requireAuth();
        $serviceRepository = new ServiceRepository();

        $data = json_decode(file_get_contents('php://input'), true);

        // Log incoming data
        error_log('Received data: ' . print_r($data, true));

        // Create and validate the ServiceEntity object
        $serviceEntity = new ServiceEntity(
            null, // Allow null for the ID
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

        // Log the updated entity
        error_log('Updated entity: ' . print_r($serviceEntity, true));

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
