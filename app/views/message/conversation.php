<div class="container">
    <h3>Conversation</h3> <!-- Titre de la section de conversation -->
    <div id="message-list">
        <?php foreach ($messages as $message): ?> <!-- Boucle sur chaque message -->
            <div class="message <?php echo $message->sender_id == $_SESSION['user_id'] ? 'sent' : 'received'; ?>"> <!-- Détermine la classe du message en fonction de l'expéditeur -->
                <p><?php echo htmlspecialchars($message->content); ?></p> <!-- Contenu du message -->
                <small><?php echo $message->sent_at; ?></small> <!-- Date et heure d'envoi du message -->
            </div>
        <?php endforeach; ?> <!-- Fin de la boucle -->
    </div>

    <form id="sendMessageForm">
        <div class="form-group">
            <input type="hidden" id="receiver_id" name="receiver_id" value="<?php echo $otherUserId; ?>"> <!-- Champ caché pour l'ID du destinataire -->
            <label for="content">Message</label> <!-- Étiquette pour le champ de message -->
            <textarea class="form-control" id="content" name="content" required></textarea> <!-- Zone de texte pour saisir le message -->
        </div>
        <button type="submit" class="btn btn-primary">Send</button> <!-- Bouton pour envoyer le message -->
    </form>
</div>

<style>
    .message.sent {
        background-color: var(--secondary-color); /* Couleur de fond pour les messages envoyés */
        border: solid var(--primary) 2px; /* Bordure pour les messages envoyés */
        color: var(--primary); /* Couleur du texte pour les messages envoyés */
        text-align: right; /* Aligne le texte à droite pour les messages envoyés */
        padding: 10px; /* Espacement intérieur pour les messages envoyés */
        border-radius: 10px; /* Bords arrondis pour les messages envoyés */
        margin-bottom: 10px; /* Espacement en bas pour les messages envoyés */
    }

    .message.received {
        background-color: var(--primary); /* Couleur de fond pour les messages reçus */
        color: var(--white); /* Couleur du texte pour les messages reçus */
        text-align: left; /* Aligne le texte à gauche pour les messages reçus */
        padding: 10px; /* Espacement intérieur pour les messages reçus */
        border-radius: 10px; /* Bords arrondis pour les messages reçus */
        margin-bottom: 10px; /* Espacement en bas pour les messages reçus */
    }
</style>

<script>
    document.getElementById('sendMessageForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Empêche le rechargement de la page lors de la soumission du formulaire

        const formData = new FormData(this); // Récupère les données du formulaire
        const data = Object.fromEntries(formData.entries()); // Convertit les données du formulaire en objet JSON

        fetch('/seha/public/message/sendMessage', {
            method: 'POST', // Utilise la méthode POST pour envoyer les données
            headers: {
                'Content-Type': 'application/json' // Spécifie que les données sont en JSON
            },
            body: JSON.stringify(data) // Convertit l'objet JSON en chaîne de caractères
        }).then(response => response.json()).then(data => {
            if (data.status === 'success') {
                // Recharge les messages sans recharger la page
                const receiverId = document.getElementById('receiver_id').value; // Récupère l'ID du destinataire
                fetch(`/seha/public/message/getConversation?user_id=${receiverId}`)
                    .then(response => response.text())
                    .then(html => {
                        // Met à jour la liste des messages avec le nouveau contenu
                        document.querySelector('#message-list').innerHTML = new DOMParser().parseFromString(html, 'text/html').querySelector('#message-list').innerHTML;
                    });
            } else {
                alert('Failed to send message'); // Affiche une alerte en cas d'échec de l'envoi du message
            }
        }).catch(error => {
            alert('An error occurred: ' + error.message); // Affiche une alerte en cas d'erreur
        });
    });
</script>