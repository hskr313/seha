<?php
class TransactionsController extends BaseController { // Déclaration de la classe TransactionsController qui hérite de BaseController

    // Méthode pour afficher la page des transactions de l'utilisateur connecté
    public function index() {
        AuthMiddleware::requireAuth(); // Exige l'authentification pour accéder à cette méthode
        $transactionRepository = new TransactionRepository(); // Crée une instance de TransactionRepository
        $userRepository = new UserRepository(); // Crée une instance de UserRepository
        $serviceRepository = new ServiceRepository(); // Crée une instance de ServiceRepository

        $userId = $_SESSION['user_id']; // Récupère l'ID de l'utilisateur connecté
        $transactions = $transactionRepository->findByUserId($userId); // Récupère les transactions de l'utilisateur

        foreach ($transactions as $transaction) { // Parcourt toutes les transactions
            $transaction->service_name = $serviceRepository->findById($transaction->service_id)->name; // Ajoute le nom du service à la transaction
            $transaction->provider_username = $userRepository->findById($transaction->provider_id)->username; // Ajoute le nom d'utilisateur du fournisseur à la transaction
            $transaction->receiver_username = $userRepository->findById($transaction->receiver_id)->username; // Ajoute le nom d'utilisateur du receveur à la transaction
        }

        $this->view('transactions/index', [ // Affiche la vue des transactions avec les données des transactions
            'title' => 'My Transactions',
            'transactions' => $transactions
        ]);
    }
}
?>