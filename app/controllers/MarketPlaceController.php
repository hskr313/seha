<?php
class MarketPlaceController extends BaseController {
    public function index() {
        AuthMiddleware::requireAuth();
        $serviceRepository = new ServiceRepository();
        $servicesGroupedByCategory = $serviceRepository->findAllGroupedByCategory();
        $categories = $serviceRepository->findAllCategories();
        $this->view('market-place/index', ['title' => 'Welcome to Service Exchange', 'servicesGroupedByCategory' => $servicesGroupedByCategory, 'categories' => $categories]);
    }
}
