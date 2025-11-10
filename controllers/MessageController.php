<?php

/**
 * Contrôleur de gestion de la messagerie
 *
 * Gère toutes les fonctionnalités liées à la messagerie :
 * - Liste des conversations
 * - Affichage d'une conversation
 * - Envoi de messages
 * - Marquage comme lu
 *
 * @package    TomTroc
 * @subpackage Controllers
 * @author     TomTroc Team
 * @version    1.0.0
 * @since      Version 1.0
 */
class MessageController extends AbstractController
{
    /**
     * Instance du modèle Message
     *
     * @var Message
     */
    private Message $messageModel;

    /**
     * Instance du modèle User
     *
     * @var User
     */
    private User $userModel;

    /**
     * Constructeur du contrôleur de messagerie
     *
     * Initialise les modèles Message et User.
     *
     * @return void
     */
    public function __construct()
    {
        $this->messageModel = new Message();
        $this->userModel = new User();
    }

    /**
     * Afficher la liste des conversations
     *
     * Accessible uniquement aux utilisateurs connectés.
     * Affiche la liste de toutes les conversations avec compteur de non-lus.
     *
     * @return void
     */
    public function index(): void
    {
        $this->requireAuth();

        $userId = $this->getUserId();
        $conversations = $this->messageModel->getConversations($userId);
        $unreadTotal = $this->messageModel->countUnread($userId);

        $this->render('messages/index', [
            'conversations' => $conversations,
            'unreadTotal' => $unreadTotal
        ]);
    }

    /**
     * Afficher une conversation avec un utilisateur
     *
     * Accessible uniquement aux utilisateurs connectés.
     * Affiche tous les messages échangés avec un utilisateur spécifique.
     * Marque automatiquement les messages reçus comme lus.
     *
     * @param int $otherUserId ID de l'autre utilisateur
     * @return void
     */
    public function show(int $otherUserId): void
    {
        $this->requireAuth();

        $userId = $this->getUserId();

        // Vérifier que l'autre utilisateur existe
        $otherUser = $this->userModel->findById($otherUserId);
        if (!$otherUser) {
            $_SESSION['flash_error'] = "Utilisateur introuvable";
            $this->redirect(APP_URL . '/messages');
            return;
        }

        // Récupérer les messages de la conversation
        $messages = $this->messageModel->getConversationMessages($userId, $otherUserId);

        // Marquer les messages reçus comme lus
        $this->messageModel->markAsRead($userId, $otherUserId);

        $this->render('messages/conversation', [
            'messages' => $messages,
            'otherUser' => $otherUser,
            'userId' => $userId
        ]);
    }

    /**
     * Afficher le formulaire de nouveau message
     *
     * Accessible uniquement aux utilisateurs connectés.
     * Permet de démarrer une nouvelle conversation.
     *
     * @return void
     */
    public function newMessage(): void
    {
        $this->requireAuth();

        $recipientId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
        $bookId = isset($_GET['book_id']) ? (int)$_GET['book_id'] : null;

        $recipient = null;
        if ($recipientId) {
            $recipient = $this->userModel->findById($recipientId);
        }

        $this->render('messages/new', [
            'recipient' => $recipient,
            'bookId' => $bookId
        ]);
    }

    /**
     * Envoyer un message
     *
     * Traite l'envoi d'un nouveau message.
     * Redirige vers la conversation après envoi.
     *
     * @return void
     */
    public function send(): void
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/messages');
            return;
        }

        $senderId = $this->getUserId();
        $receiverId = (int)($_POST['receiver_id'] ?? 0);
        $content = trim($_POST['content'] ?? '');
        $bookId = !empty($_POST['book_id']) ? (int)$_POST['book_id'] : null;

        // Validation
        $errors = [];

        if (empty($receiverId)) {
            $errors[] = "Le destinataire est requis";
        }

        if (empty($content)) {
            $errors[] = "Le message ne peut pas être vide";
        } elseif (strlen($content) < 5) {
            $errors[] = "Le message doit contenir au moins 5 caractères";
        } elseif (strlen($content) > 1000) {
            $errors[] = "Le message ne peut pas dépasser 1000 caractères";
        }

        if ($senderId === $receiverId) {
            $errors[] = "Vous ne pouvez pas vous envoyer un message à vous-même";
        }

        // Vérifier que le destinataire existe
        $receiver = $this->userModel->findById($receiverId);
        if (!$receiver) {
            $errors[] = "Le destinataire n'existe pas";
        }

        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode('<br>', $errors);
            $_SESSION['old_content'] = $content;
            $this->redirect(APP_URL . '/messages/new?user_id=' . $receiverId);
            return;
        }

        // Envoyer le message
        $success = $this->messageModel->send($senderId, $receiverId, $content, $bookId);

        if ($success) {
            $_SESSION['flash_success'] = "Message envoyé avec succès";
            $this->redirect(APP_URL . '/messages/' . $receiverId);
        } else {
            $_SESSION['flash_error'] = "Erreur lors de l'envoi du message";
            $this->redirect(APP_URL . '/messages/new?user_id=' . $receiverId);
        }
    }
}
