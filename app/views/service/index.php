<div class="container"> <!-- Conteneur principal -->
    <div class="card mb-4"> <!-- Carte pour contenir la liste des services -->
        <div class="card-header d-flex justify-content-between align-items-center"> <!-- En-tête de la carte -->
            <h5 class="m-0 font-weight-bold text-primary">My Services</h5> <!-- Titre de la carte -->
            <button class="btn btn-primary" data-toggle="modal" data-target="#createServiceModal">Add Service</button> <!-- Bouton pour ajouter un service, ouvre un modal -->
        </div>
        <div class="card-body"> <!-- Corps de la carte -->
            <table class="table"> <!-- Table pour afficher les services -->
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Category</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Published</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($services as $service): ?> <!-- Boucle PHP pour chaque service -->
                    <tr data-id="<?php echo $service->id; ?>"> <!-- Ligne de table pour chaque service -->
                        <th scope="row"><?php echo $service->id; ?></th> <!-- ID du service -->
                        <td><?php echo htmlspecialchars($service->category_name); ?></td> <!-- Nom de la catégorie du service -->
                        <td><?php echo htmlspecialchars($service->name); ?></td> <!-- Nom du service -->
                        <td><?php echo htmlspecialchars($service->description); ?></td> <!-- Description du service -->
                        <td>
                            <input type="checkbox" class="form-check-input btn-toggle-publish" disabled <?php echo $service->is_published ? 'checked' : ''; ?>> <!-- Checkbox pour le statut de publication -->
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm btn-edit">Edit</button> <!-- Bouton pour éditer le service -->
                            <button class="btn btn-danger btn-sm btn-delete">Delete</button> <!-- Bouton pour supprimer le service -->
                        </td>
                    </tr>
                <?php endforeach; ?> <!-- Fin de la boucle PHP -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal pour créer un nouveau service -->
<div class="modal fade" id="createServiceModal" tabindex="-1" role="dialog" aria-labelledby="createServiceModalLabel" aria-hidden="true"> <!-- Début du modal -->
    <div class="modal-dialog" role="document"> <!-- Conteneur du modal -->
        <div class="modal-content"> <!-- Contenu du modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="createServiceModalLabel">Add New Service</h5> <!-- Titre du modal -->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <!-- Bouton pour fermer le modal -->
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createServiceForm"> <!-- Formulaire pour créer un service -->
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select class="form-control" id="category" name="category_id" required> <!-- Sélecteur pour les catégories -->
                            <?php foreach ($categories as $category): ?> <!-- Boucle PHP pour chaque catégorie -->
                                <option value="<?php echo $category->id; ?>"><?php echo htmlspecialchars($category->category_name); ?></option> <!-- Option pour chaque catégorie -->
                            <?php endforeach; ?> <!-- Fin de la boucle PHP -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="serviceTitle">Name</label>
                        <input type="text" class="form-control" id="serviceTitle" name="name" required> <!-- Champ pour le nom du service -->
                    </div>
                    <div class="form-group">
                        <label for="serviceDescription">Description</label>
                        <textarea class="form-control" id="serviceDescription" name="description" required></textarea> <!-- Champ pour la description du service -->
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="servicePublished" name="is_published"> <!-- Checkbox pour le statut de publication -->
                        <label class="form-check-label" for="servicePublished">Published</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button> <!-- Bouton pour soumettre le formulaire -->
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de l'édition des services
        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr'); // Trouve la ligne de la table
                const id = row.getAttribute('data-id'); // Récupère l'ID du service
                const nameCell = row.children[2]; // Récupère la cellule du nom
                const descriptionCell = row.children[3]; // Récupère la cellule de la description
                const publishCheckbox = row.querySelector('.btn-toggle-publish'); // Récupère la checkbox de publication

                if (button.innerText === 'Edit') {
                    nameCell.contentEditable = true; // Rend la cellule du nom éditable
                    descriptionCell.contentEditable = true; // Rend la cellule de la description éditable
                    publishCheckbox.disabled = false; // Active la checkbox de publication
                    button.innerText = 'Save'; // Change le texte du bouton en 'Save'
                } else {
                    const name = nameCell.innerText; // Récupère le nouveau nom
                    const description = descriptionCell.innerText; // Récupère la nouvelle description
                    const isPublished = publishCheckbox.checked ? 1 : 0; // Vérifie si le service est publié

                    fetch('/seha/public/service/updateService', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id, name, description, is_published: isPublished }) // Envoie les données mises à jour au serveur
                    }).then(response => response.json()).then(data => {
                        if (data.status === 'success') {
                            nameCell.contentEditable = false; // Désactive l'édition du nom
                            descriptionCell.contentEditable = false; // Désactive l'édition de la description
                            publishCheckbox.disabled = true; // Désactive la checkbox de publication
                            button.innerText = 'Edit'; // Change le texte du bouton en 'Edit'
                        } else {
                            alert('Failed to update service'); // Alerte en cas d'échec de la mise à jour
                        }
                    });
                }
            });
        });

        // Gestion de la suppression des services
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr'); // Trouve la ligne de la table
                const id = row.getAttribute('data-id'); // Récupère l'ID du service

                fetch('/seha/public/service/deleteService', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id }) // Envoie l'ID du service à supprimer au serveur
                }).then(response => response.json()).then(data => {
                    if (data.status === 'success') {
                        row.remove(); // Supprime la ligne de la table
                    } else {
                        alert('Failed to delete service'); // Alerte en cas d'échec de la suppression
                    }
                });
            });
        });

        // Gestion de la création d'un nouveau service
        document.getElementById('createServiceForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Empêche le rechargement de la page

            const formData = new FormData(this); // Récupère les données du formulaire
            const data = Object.fromEntries(formData.entries()); // Convertit les données en objet

            fetch('/seha/public/service/createService', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data) // Envoie les données au serveur
            }).then(response => response.json()).then(data => {
                if (data.status === 'success') {
                    location.reload(); // Recharge la page en cas de succès
                } else {
                    alert('Failed to create service'); // Alerte en cas d'échec de la création
                }
            });
        });
    });
</script>