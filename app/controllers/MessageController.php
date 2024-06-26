<?php
class MessageController extends BaseController {
    public function index()
    {
        // Code pour afficher une vue d'index si nécessaire
    }

    public function searchUsers() {
        AuthMiddleware::requireAuth();
        $username = filter_input(INPUT_GET, 'username', FILTER_SANITIZE_STRING);
        $userRepository = new UserRepository();
        $users = $userRepository->findByUsername($username);

        header('Content-Type: application/json');
        echo json_encode($users);
        exit();
    }

    public function sendMessage() {
        AuthMiddleware::requireAuth();
        $messageRepository = new MessageRepository();

        $data = json_decode(file_get_contents('php://input'), true);

        $messageEntity = new MessageEntity(
            null,
            $_SESSION['user_id'],
            $data['receiver_id'],
            htmlspecialchars($data['content']),
            date('Y-m-d H:i:s')
        );

        $result = $messageRepository->create($messageEntity);

        header('Content-Type: application/json');
        echo json_encode(['status' => $result ? 'success' : 'error']);
        exit();
    }

    public function getConversation() {
        AuthMiddleware::requireAuth();
        $messageRepository = new MessageRepository();
        $userId = $_SESSION['user_id'];
        $otherUserId = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT);

        $messages = $messageRepository->findByConversation($userId, $otherUserId);

        $this->view('message/conversation', ['messages' => $messages, 'otherUserId' => $otherUserId]);
    }

    public function getAllConversations() {
        AuthMiddleware::requireAuth();
        $messageRepository = new MessageRepository();
        $userRepository = new UserRepository();
        $userId = $_SESSION['user_id'];

        $conversations = $messageRepository->findAllConversations($userId);

        foreach ($conversations as $conversation) {
            $conversation->sender_username = $userRepository->findById($conversation->sender_id)->username;
            $conversation->receiver_username = $userRepository->findById($conversation->receiver_id)->username;
        }

        $this->view('message/index', ['conversations' => $conversations]);
    }

    public function getUnreadMessageCount() {
        AuthMiddleware::requireAuth();
        $messageRepository = new MessageRepository();
        $userId = $_SESSION['user_id'];

        $unreadCount = $messageRepository->countUnreadMessages($userId);

        header('Content-Type: application/json');
        echo json_encode(['unreadCount' => $unreadCount]);
        exit();
    }
}
