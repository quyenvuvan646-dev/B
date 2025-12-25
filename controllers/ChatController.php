<?php
class ChatController extends Controller {
    public function show($order_id) {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }
        $msgModel = $this->model("MessageModel");
        $orderModel = $this->model("OrderModel");
        
        // Mark messages as read when user views the chat
        $msgModel->markOrderMessagesAsRead($order_id, $user['email']);
        
        $messages = $msgModel->getMessagesByOrder($order_id);
        $order = $orderModel->getOrderById($order_id);
        $orderItems = $orderModel->getOrderItemsWithProduct($order_id);
        $this->view("ChatView", [
            "messages" => $messages, 
            "order_id" => $order_id, 
            "user" => $user,
            "order" => $order,
            "orderItems" => $orderItems
        ]);
    }

    public function send() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }
        $order_id = $_POST['order_id'] ?? null;
        $receiver_email = $_POST['receiver_email'] ?? null;
        $message = $_POST['message'] ?? '';
        $image_url = $_POST['image_url'] ?? null;
        if ($order_id && $receiver_email && $message) {
            $msgModel = $this->model("MessageModel");
            $msgModel->sendMessage($order_id, $user['email'], $receiver_email, $message, $image_url);
        }
        header('Location: ' . APP_URL . '/ChatController/show/' . $order_id);
        exit();
    }
}
