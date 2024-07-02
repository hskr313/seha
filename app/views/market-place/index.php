<div class="container mt-4">
    <div class="mb-4">
        <label for="searchInput">Search:</label>
        <input type="text" id="searchInput" class="form-control" placeholder="Search by service name or username">
        <button id="searchButton" class="btn btn-primary mt-2">Search</button>
    </div>

    <table id="servicesTable" class="display table table-striped table-bordered">
        <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Username</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($services)): ?>
            <?php foreach ($services as $service): ?>
                <tr>
                    <td><?php echo htmlspecialchars($service->name); ?></td>
                    <td><?php echo htmlspecialchars($service->description); ?></td>
                    <td><?php echo htmlspecialchars($service->username ?? ''); ?></td>
                    <td>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#serviceModal" data-service-id="<?php echo $service->id; ?>" data-name="<?php echo htmlspecialchars($service->name); ?>" data-description="<?php echo htmlspecialchars($service->description); ?>" data-username="<?php echo htmlspecialchars($service->username); ?>">Request Service</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No services available.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="serviceModal" tabindex="-1" role="dialog" aria-labelledby="serviceModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="serviceModalLabel">Service Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 id="modalServiceName"></h5>
                    <p id="modalServiceDescription"></p>
                    <p><small id="modalServiceUsername" class="text-muted"></small></p>
                    <div class="form-group">
                        <label for="hoursInput">Number of Hours:</label>
                        <input type="number" id="hoursInput" class="form-control" min="1" max="100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="requestServiceButton">Request Service</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fonction pour rechercher des services
        function searchServices() {
            const query = document.getElementById('searchInput').value; // Récupère la valeur du champ de recherche
            fetch('/seha/public/marketplace/search?query=' + encodeURIComponent(query)) // Envoie une requête pour rechercher des services
                .then(response => response.json()) // Convertit la réponse en JSON
                .then(data => {
                    const tableBody = document.querySelector('#servicesTable tbody'); // Sélectionne le corps du tableau
                    tableBody.innerHTML = ''; // Vide le corps du tableau existant

                    if (data.length > 0) { // Si des services sont trouvés
                        data.forEach(service => { // Pour chaque service trouvé
                            const row = document.createElement('tr'); // Crée une nouvelle ligne de tableau

                            const nameCell = document.createElement('td'); // Crée une cellule pour le nom
                            nameCell.textContent = service.name; // Définit le texte de la cellule
                            nameCell.style.padding = '8px'; // Ajoute du style à la cellule
                            row.appendChild(nameCell); // Ajoute la cellule à la ligne

                            const descriptionCell = document.createElement('td'); // Crée une cellule pour la description
                            descriptionCell.textContent = service.description; // Définit le texte de la cellule
                            descriptionCell.style.padding = '8px'; // Ajoute du style à la cellule
                            row.appendChild(descriptionCell); // Ajoute la cellule à la ligne

                            const usernameCell = document.createElement('td'); // Crée une cellule pour le nom d'utilisateur
                            usernameCell.textContent = service.username ?? ''; // Définit le texte de la cellule
                            usernameCell.style.padding = '8px'; // Ajoute du style à la cellule
                            row.appendChild(usernameCell); // Ajoute la cellule à la ligne

                            const actionsCell = document.createElement('td'); // Crée une cellule pour les actions
                            actionsCell.style.padding = '8px'; // Ajoute du style à la cellule
                            const requestButton = document.createElement('button'); // Crée un bouton pour demander un service
                            requestButton.className = 'btn btn-primary'; // Ajoute des classes CSS au bouton
                            requestButton.setAttribute('data-toggle', 'modal'); // Ajoute l'attribut pour ouvrir le modal
                            requestButton.setAttribute('data-target', '#serviceModal'); // Spécifie le modal à ouvrir
                            requestButton.setAttribute('data-service-id', service.id); // Définit l'ID du service
                            requestButton.setAttribute('data-name', service.name); // Définit le nom du service
                            requestButton.setAttribute('data-description', service.description); // Définit la description du service
                            requestButton.setAttribute('data-username', service.username); // Définit le nom d'utilisateur du service
                            requestButton.textContent = 'Request Service'; // Définit le texte du bouton
                            actionsCell.appendChild(requestButton); // Ajoute le bouton à la cellule
                            row.appendChild(actionsCell); // Ajoute la cellule à la ligne

                            tableBody.appendChild(row); // Ajoute la ligne au corps du tableau
                        });
                    } else {
                        const row = document.createElement('tr'); // Crée une nouvelle ligne de tableau
                        const cell = document.createElement('td'); // Crée une cellule
                        cell.setAttribute('colspan', '4'); // Définit l'attribut colspan de la cellule
                        cell.textContent = 'No services available.'; // Définit le texte de la cellule
                        cell.style.textAlign = 'center'; // Centre le texte dans la cellule
                        row.appendChild(cell); // Ajoute la cellule à la ligne
                        tableBody.appendChild(row); // Ajoute la ligne au corps du tableau
                    }
                });
        }

        document.getElementById('searchButton').addEventListener('click', searchServices); // Ajoute un écouteur d'événement au bouton de recherche

        // Gestion de l'affichage du modal
        $('#serviceModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Bouton qui a déclenché le modal
            var serviceId = button.data('service-id'); // Récupère l'ID du service
            var name = button.data('name'); // Récupère le nom du service
            var description = button.data('description'); // Récupère la description du service
            var username = button.data('username'); // Récupère le nom d'utilisateur du service

            var modal = $(this); // Sélectionne le modal actuel
            modal.find('#modalServiceName').text(name); // Définit le nom du service dans le modal
            modal.find('#modalServiceDescription').text(description); // Définit la description du service dans le modal
            modal.find('#modalServiceUsername').text('By ' + username); // Définit le nom d'utilisateur du service dans le modal
            modal.find('#requestServiceButton').data('service-id', serviceId); // Définit l'ID du service pour le bouton de demande
        });

        // Gestion de la demande de service
        document.getElementById('requestServiceButton').addEventListener('click', function() {
            var serviceId = $(this).data('service-id'); // Récupère l'ID du service
            var hours = document.getElementById('hoursInput').value; // Récupère le nombre d'heures

            if (hours <= 0) { // Vérifie si le nombre d'heures est valide
                alert('Please enter a valid number of hours.');
                return;
            }

            fetch('/seha/public/marketplace/requestService', {
                method: 'POST', // Utilise la méthode POST
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    service_id: serviceId,
                    hours: hours
                })
            })
                .then(response => response.json()) // Convertit la réponse en JSON
                .then(data => {
                    if (data.success) { // Si la demande a réussi
                        alert('Service requested successfully.');
                        $('#serviceModal').modal('hide'); // Cache le modal
                    } else { // Si la demande a échoué
                        alert('Failed to request service: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error)); // Affiche une erreur dans la console
        });

        $('#servicesTable').DataTable(); // Initialise DataTables
    });
</script>