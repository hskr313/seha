<?php
class MessageController extends BaseController {
    public function searchUsers() {
        AuthMiddleware::requireAuth();
        $username = $_GET['username'] ?? '';
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
            $data['content'],
            date('Y-m-d H:i:s')
        );

        $result = $messageRepository->create($messageEntity);

        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
        exit();
    }

    public function getConversation() {
        AuthMiddleware::requireAuth();
        $messageRepository = new MessageRepository();
        $userId = $_SESSION['user_id'];
        $otherUserId = $_GET['user_id'];

        $messages = $messageRepository->findByConversation($userId, $otherUserId);

        $this->view('message/conversation', ['messages' => $messages, 'otherUserId' => $otherUserId]);
    }

    public function getAllConversations() {
        AuthMiddleware::requireAuth();
        $messageRepository = new MessageRepository();
        $userId = $_SESSION['user_id'];

        $conversations = $messageRepository->findAllConversations($userId);

        header('Content-Type: application/json');
        echo json_encode($conversations);
        exit();
    }
}
