<div class="container mt-4"> <!-- Conteneur principal avec une marge en haut -->
    <div class="mb-4"> <!-- Section pour le titre avec une marge en bas -->
        <h1>My Transactions</h1> <!-- Titre de la section -->
    </div>

    <table id="transactionsTable" class="display table table-striped table-bordered"> <!-- Table pour afficher les transactions -->
        <thead>
        <tr>
            <th>Service Name</th> <!-- En-tête pour le nom du service -->
            <th>Provider</th> <!-- En-tête pour le fournisseur -->
            <th>Receiver</th> <!-- En-tête pour le receveur -->
            <th>Hours Exchanged</th> <!-- En-tête pour les heures échangées -->
            <th>Transaction Date</th> <!-- En-tête pour la date de la transaction -->
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($transactions)): ?> <!-- Vérifie si le tableau des transactions n'est pas vide -->
            <?php foreach ($transactions as $transaction): ?> <!-- Boucle PHP pour chaque transaction -->
                <tr>
                    <td><?php echo htmlspecialchars($transaction->service_name); ?></td> <!-- Nom du service -->
                    <td><?php echo htmlspecialchars($transaction->provider_username); ?></td> <!-- Nom d'utilisateur du fournisseur -->
                    <td><?php echo htmlspecialchars($transaction->receiver_username); ?></td> <!-- Nom d'utilisateur du receveur -->
                    <td><?php echo htmlspecialchars($transaction->hours_exchanged); ?></td> <!-- Heures échangées -->
                    <td><?php echo htmlspecialchars($transaction->transaction_date); ?></td> <!-- Date de la transaction -->
                </tr>
            <?php endforeach; ?> <!-- Fin de la boucle PHP -->
        <?php else: ?> <!-- Si le tableau des transactions est vide -->
            <tr>
                <td colspan="5">No transactions available.</td> <!-- Message pour indiquer qu'il n'y a pas de transactions -->
            </tr>
        <?php endif; ?> <!-- Fin de la condition -->
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#transactionsTable').DataTable(); // Initialise DataTables sur la table des transactions
    });
</script>