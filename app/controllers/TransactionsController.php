<?php
class TransactionsController extends BaseController
{
    public function index()
    {
        AuthMiddleware::requireAuth();
        $transactionRepository = new TransactionRepository();
        $userRepository = new UserRepository();
        $serviceRepository = new ServiceRepository();

        $userId = $_SESSION['user_id'];
        $transactions = $transactionRepository->findByUserId($userId);

        foreach ($transactions as $transaction) {
            $transaction->service_name = $serviceRepository->findById($transaction->service_id)->name;
            $transaction->provider_username = $userRepository->findById($transaction->provider_id)->username;
            $transaction->receiver_username = $userRepository->findById($transaction->receiver_id)->username;
        }

        $this->view('transactions/index', [
            'title' => 'My Transactions',
            'transactions' => $transactions
        ]);
    }
}
