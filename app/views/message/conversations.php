<!--message/conversations-->
<div class="container">
    <h3>My Messages</h3>
    <ul class="list-group">
        <?php foreach ($conversations as $conversation): ?>
            <li class="list-group-item">
                <a href="/seha/public/message/getConversation?user_id=<?php echo $conversation->sender_id == $_SESSION['user_id'] ? $conversation->receiver_id : $conversation->sender_id; ?>">
                    Conversation with <?php echo $conversation->sender_id == $_SESSION['user_id'] ? htmlspecialchars($conversation->receiver_username) : htmlspecialchars($conversation->sender_username); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
