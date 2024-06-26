<?php
class MarketPlaceController extends BaseController {
    public function index() {
        AuthMiddleware::requireAuth();
        $serviceRepository = new ServiceRepository();
        $userRepository = new UserRepository();
        $services = $serviceRepository->findAll();

        foreach ($services as $service) {
            $service->username = $userRepository->findById($service->user_id)->username;
        }

        $categories = $serviceRepository->findAllCategories();
        $this->view('market-place/index', [
            'title' => 'Welcome to Service Exchange',
            'services' => $services,
            'categories' => $categories
        ]);
    }

    public function search() {
        AuthMiddleware::requireAuth();
        $query = $_GET['query'] ?? '';
        $serviceRepository = new ServiceRepository();
        $userRepository = new UserRepository();
        $services = $serviceRepository->searchServices($query);

        foreach ($services as $service) {
            $service->username = $userRepository->findById($service->user_id)->username;
        }

        header('Content-Type: application/json');
        echo json_encode($services);
        exit();
    }

    public function requestService() {
        AuthMiddleware::requireAuth();
        $data = json_decode(file_get_contents('php://input'), true);

        $serviceId = $data['service_id'];
        $hours = $data['hours'];

        $serviceRepository = new ServiceRepository();
        $userRepository = new UserRepository();
        $transactionRepository = new TransactionRepository();

        $service = $serviceRepository->findById($serviceId);
        $providerId = $service->user_id;
        $receiverId = $_SESSION['user_id'];

        if ($providerId === $receiverId) {
            echo json_encode(['success' => false, 'message' => 'You cannot request your own service.']);
            exit();
        }

        $receiverCredit = $userRepository->getTimeCredit($receiverId);
        if ($receiverCredit < $hours) {
            echo json_encode(['success' => false, 'message' => 'Insufficient time credit.']);
            exit();
        }

        $transaction = new TransactionEntity(
            null,
            $serviceId,
            $providerId,
            $receiverId,
            $hours,
            date('Y-m-d H:i:s')
        );
        $transactionRepository->create($transaction);

        $providerCredit = $userRepository->getTimeCredit($providerId);
        $userRepository->updateTimeCredit($receiverId, $receiverCredit - $hours);
        $userRepository->updateTimeCredit($providerId, $providerCredit + $hours);

        echo json_encode(['success' => true]);
        exit();
    }
}
