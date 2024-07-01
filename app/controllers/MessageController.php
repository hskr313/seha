<?php
class MessageController extends BaseController { // Déclaration de la classe MessageController qui hérite de BaseController
    public function index() {
        // Code pour afficher une vue d'index si nécessaire
    }

    // Méthode pour rechercher des utilisateurs par nom d'utilisateur
    public function searchUsers() {
        AuthMiddleware::requireAuth(); // Exige l'authentification pour accéder à cette méthode
        $username = filter_input(INPUT_GET, 'username', FILTER_SANITIZE_STRING); // Récupère et assainit le nom d'utilisateur depuis la requête GET
        $userRepository = new UserRepository(); // Crée une instance de UserRepository
        $users = $userRepository->findByUsername($username); // Recherche les utilisateurs par nom d'utilisateur

        header('Content-Type: application/json'); // Définit le type de contenu de la réponse en JSON
        echo json_encode($users); // Encode les utilisateurs en JSON et les affiche
        exit(); // Arrête l'exécution du script
    }

    // Méthode pour envoyer un message
    public function sendMessage() {
        AuthMiddleware::requireAuth(); // Exige l'authentification pour accéder à cette méthode
        $messageRepository = new MessageRepository(); // Crée une instance de MessageRepository

        $data = json_decode(file_get_contents('php://input'), true); // Récupère les données de la requête en JSON

        $messageEntity = new MessageEntity( // Crée une nouvelle instance de MessageEntity
            null, // ID (null car il sera auto-incrémenté)
            $_SESSION['user_id'], // ID de l'expéditeur
            $data['receiver_id'], // ID du destinataire
            htmlspecialchars($data['content']), // Contenu du message, assaini
            date('Y-m-d H:i:s') // Date et heure actuelles
        );

        $result = $messageRepository->create($messageEntity); // Enregistre le message dans la base de données

        header('Content-Type: application/json'); // Définit le type de contenu de la réponse en JSON
        echo json_encode(['status' => $result ? 'success' : 'error']); // Retourne le statut de l'opération en JSON
        exit(); // Arrête l'exécution du script
    }

    // Méthode pour récupérer une conversation entre l'utilisateur connecté et un autre utilisateur
    public function getConversation() {
        AuthMiddleware::requireAuth(); // Exige l'authentification pour accéder à cette méthode
        $messageRepository = new MessageRepository(); // Crée une instance de MessageRepository
        $userId = $_SESSION['user_id']; // Récupère l'ID de l'utilisateur connecté
        $otherUserId = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT); // Récupère et assainit l'ID de l'autre utilisateur depuis la requête GET

        $messages = $messageRepository->findByConversation($userId, $otherUserId); // Récupère les messages de la conversation

        $this->view('message/conversation', ['messages' => $messages, 'otherUserId' => $otherUserId]); // Affiche la vue de la conversation avec les messages
    }

    // Méthode pour récupérer toutes les conversations de l'utilisateur connecté
    public function getAllConversations() {
        AuthMiddleware::requireAuth(); // Exige l'authentification pour accéder à cette méthode
        $messageRepository = new MessageRepository(); // Crée une instance de MessageRepository
        $userRepository = new UserRepository(); // Crée une instance de UserRepository
        $userId = $_SESSION['user_id']; // Récupère l'ID de l'utilisateur connecté

        $conversations = $messageRepository->findAllConversations($userId); // Récupère toutes les conversations de l'utilisateur

        foreach ($conversations as $conversation) { // Parcourt toutes les conversations
            $conversation->sender_username = $userRepository->findById($conversation->sender_id)->username; // Ajoute le nom d'utilisateur de l'expéditeur à la conversation
            $conversation->receiver_username = $userRepository->findById($conversation->receiver_id)->username; // Ajoute le nom d'utilisateur du destinataire à la conversation
        }

        $this->view('message/index', ['conversations' => $conversations]); // Affiche la vue de l'index des messages avec les conversations
    }

    // Méthode pour récupérer le nombre de messages non lus de l'utilisateur connecté
    public function getUnreadMessageCount() {
        AuthMiddleware::requireAuth(); // Exige l'authentification pour accéder à cette méthode
        $messageRepository = new MessageRepository(); // Crée une instance de MessageRepository
        $userId = $_SESSION['user_id']; // Récupère l'ID de l'utilisateur connecté

        $unreadCount = $messageRepository->countUnreadMessages($userId); // Récupère le nombre de messages non lus

        header('Content-Type: application/json'); // Définit le type de contenu de la réponse en JSON
        echo json_encode(['unreadCount' => $unreadCount]); // Retourne le nombre de messages non lus en JSON
        exit(); // Arrête l'exécution du script
    }
}
?>