<div class="container">
    <h3>Conversation</h3>
    <div id="message-list">
        <?php foreach ($messages as $message): ?>
            <div class="message <?php echo $message->sender_id == $_SESSION['user_id'] ? 'sent' : 'received'; ?>">
                <p><?php echo htmlspecialchars($message->content); ?></p>
                <small><?php echo $message->sent_at; ?></small>
            </div>
        <?php endforeach; ?>
    </div>

    <form id="sendMessageForm">
        <div class="form-group">
            <input type="hidden" id="receiver_id" name="receiver_id" value="<?php echo $otherUserId; ?>">
            <label for="content">Message</label>
            <textarea class="form-control" id="content" name="content" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send</button>
    </form>
</div>

<script>
    document.getElementById('sendMessageForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch('/seha/public/message/sendMessage', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        }).then(response => response.json()).then(data => {
            if (data.status === 'success') {
                // Reload the messages without refreshing the page
                const receiverId = document.getElementById('receiver_id').value;
                fetch(`/seha/public/message/getConversation?user_id=${receiverId}`)
                    .then(response => response.text())
                    .then(html => {
                        document.querySelector('#message-list').innerHTML = new DOMParser().parseFromString(html, 'text/html').querySelector('#message-list').innerHTML;
                    });
            } else {
                alert('Failed to send message');
            }
        });
    });
</script>
