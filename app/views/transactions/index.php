<div class="container mt-4">
    <div class="mb-4">
        <h1>My Transactions</h1>
    </div>

    <table id="transactionsTable" class="display table table-striped table-bordered">
        <thead>
        <tr>
            <th>Service Name</th>
            <th>Provider</th>
            <th>Receiver</th>
            <th>Hours Exchanged</th>
            <th>Transaction Date</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($transactions)): ?>
            <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?php echo htmlspecialchars($transaction->service_name); ?></td>
                    <td><?php echo htmlspecialchars($transaction->provider_username); ?></td>
                    <td><?php echo htmlspecialchars($transaction->receiver_username); ?></td>
                    <td><?php echo htmlspecialchars($transaction->hours_exchanged); ?></td>
                    <td><?php echo htmlspecialchars($transaction->transaction_date); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No transactions available.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#transactionsTable').DataTable();
    });
</script>
