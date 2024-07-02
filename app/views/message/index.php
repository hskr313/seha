<div class="container">
    <h3>My Messages</h3> <!-- Titre de la section -->
    <ul class="list-group"> <!-- Début de la liste des messages -->
        <?php foreach ($conversations as $conversation): ?> <!-- Boucle sur chaque conversation -->
            <li class="list-group-item"> <!-- Élément de liste pour chaque conversation -->
                <a href="/seha/public/message/getConversation?user_id=<?php echo $conversation->sender_id == $_SESSION['user_id'] ? $conversation->receiver_id : $conversation->sender_id; ?>"> <!-- Lien vers la conversation -->
                    <?php
                    // Affiche le nom d'utilisateur de l'autre participant à la conversation
                    echo $conversation->sender_id == $_SESSION['user_id'] ? htmlspecialchars($conversation->receiver_username) : htmlspecialchars($conversation->sender_username);
                    ?>
                </a>
            </li>
        <?php endforeach; ?> <!-- Fin de la boucle -->
    </ul> <!-- Fin de la liste des messages -->
</div>