<div class="container">
    <h3>My Messages</h3>
    <div class="row">
        <div class="col-md-6">
            <h4>Sent Messages</h4>
            <ul class="list-group">
                <?php foreach ($sentMessages as $message): ?>
                    <li class="list-group-item">
                        <strong>To:</strong> <?php echo htmlspecialchars($message->receiver_id); ?><br>
                        <?php echo htmlspecialchars($message->content); ?><br>
                        <small><?php echo $message->sent_at; ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-md-6">
            <h4>Received Messages</h4>
            <ul class="list-group">
                <?php foreach ($receivedMessages as $message): ?>
                    <li class="list-group-item">
                        <strong>From:</strong> <?php echo htmlspecialchars($message->sender_id); ?><br>
                        <?php echo htmlspecialchars($message->content); ?><br>
                        <small><?php echo $message->sent_at; ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
