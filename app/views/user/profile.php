<div class="container"> <!-- Conteneur principal -->
  <div class="card mb-4"> <!-- Carte contenant le profil de l'utilisateur -->
    <div class="card-header"> <!-- En-tête de la carte -->
      <h5 class="m-0 font-weight-bold text-primary">Profile</h5> <!-- Titre de la carte -->
    </div>
    <div class="card-body"> <!-- Corps de la carte -->
      <form id="profileForm" method="POST" action="/seha/public/user/updateProfile">
        <!-- Formulaire pour mettre à jour le profil -->
        <div class="row"> <!-- Ligne contenant les informations du profil -->
          <div class="col-lg-6"> <!-- Colonne gauche -->
            <div class="form-group">
              <label for="username"><strong>Username:</strong></label> <!-- Étiquette pour le nom d'utilisateur -->
              <input type="text" id="username" name="username" class="form-control-plaintext"
                     value="<?php echo $user->username; ?>" readonly>
              <!-- Champ de texte pour le nom d'utilisateur, en mode lecture seule -->
            </div>
            <div class="form-group">
              <label for="email"><strong>Email:</strong></label> <!-- Étiquette pour l'email -->
              <input type="email" id="email" name="email" class="form-control-plaintext"
                     value="<?php echo $user->email; ?>" readonly>
              <!-- Champ de texte pour l'email, en mode lecture seule -->
            </div>
            <div class="form-group">
              <label for="time_credit"><strong>Time Credit:</strong></label> <!-- Étiquette pour le crédit temps -->
              <input type="text" id="time_credit" class="form-control-plaintext"
                     value="<?php echo $user->time_credit; ?>" readonly>
              <!-- Champ de texte pour le crédit temps, en mode lecture seule -->
            </div>
          </div>
          <div class="col-lg-6"> <!-- Colonne droite -->
            <!-- Ajouter des informations supplémentaires sur le profil ou des actions ici -->
          </div>
        </div>
        <div id="editButtons" class="mt-3 d-none"> <!-- Boutons de sauvegarde et d'annulation, cachés par défaut -->
          <button type="submit" class="btn btn-success">Save</button> <!-- Bouton de sauvegarde -->
          <button type="button" class="btn btn-secondary" onclick="cancelEdit()">Cancel</button>
          <!-- Bouton d'annulation -->
        </div>
        <a href="javascript:void(0)" class="btn btn-primary mt-3" id="editProfileBtn" onclick="editProfile()">Edit
          Profile</a> <!-- Bouton pour entrer en mode édition -->
      </form>
    </div>
  </div>
</div>

<script>
  // Fonction pour entrer en mode édition
  function editProfile() {
    document.getElementById('username').removeAttribute('readonly'); // Rend le champ du nom d'utilisateur éditable
    document.getElementById('username').classList.remove('form-control-plaintext'); // Retire la classe de texte en lecture seule
    document.getElementById('username').classList.add('form-control'); // Ajoute la classe de champ de formulaire

    document.getElementById('email').removeAttribute('readonly'); // Rend le champ de l'email éditable
    document.getElementById('email').classList.remove('form-control-plaintext'); // Retire la classe de texte en lecture seule
    document.getElementById('email').classList.add('form-control'); // Ajoute la classe de champ de formulaire

    document.getElementById('editProfileBtn').classList.add('d-none'); // Cache le bouton d'édition
    document.getElementById('editButtons').classList.remove('d-none'); // Affiche les boutons de sauvegarde et d'annulation
  }

  // Fonction pour annuler l'édition
  function cancelEdit() {
    document.getElementById('username').setAttribute('readonly', 'readonly'); // Rend le champ du nom d'utilisateur en lecture seule
    document.getElementById('username').classList.add('form-control-plaintext'); // Ajoute la classe de texte en lecture seule
    document.getElementById('username').classList.remove('form-control'); // Retire la classe de champ de formulaire

    document.getElementById('email').setAttribute('readonly', 'readonly'); // Rend le champ de l'email en lecture seule
    document.getElementById('email').classList.add('form-control-plaintext'); // Ajoute la classe de texte en lecture seule
    document.getElementById('email').classList.remove('form-control'); // Retire la classe de champ de formulaire

    document.getElementById('editProfileBtn').classList.remove('d-none'); // Affiche le bouton d'édition
    document.getElementById('editButtons').classList.add('d-none'); // Cache les boutons de sauvegarde et d'annulation
  }
</script>