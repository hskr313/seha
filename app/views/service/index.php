<div class="container">
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="m-0 font-weight-bold text-primary">My Services</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($services ?? null as $service): ?>
                    <tr data-id="<?php echo $service->id; ?>">
                        <th scope="row"><?php echo $service->id; ?></th>
                        <td contenteditable="true"><?php echo htmlspecialchars($service->service_type); ?></td>
                        <td contenteditable="true"><?php echo htmlspecialchars($service->description); ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm btn-edit">Edit</button>
                            <button class="btn btn-danger btn-sm btn-delete">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const id = row.getAttribute('data-id');
                const name = row.children[1].innerText;
                const description = row.children[2].innerText;

                fetch('/seha/public/service/updateService', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id, name, description })
                }).then(response => response.json()).then(data => {
                    if (data.status === 'success') {
                        alert('Service updated successfully');
                    } else {
                        alert('Failed to update service');
                    }
                });
            });
        });

        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const id = row.getAttribute('data-id');

                fetch('/seha/public/service/deleteService', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                }).then(response => response.json()).then(data => {
                    if (data.status === 'success') {
                        row.remove();
                        alert('Service deleted successfully');
                    } else {
                        alert('Failed to delete service');
                    }
                });
            });
        });
    });
</script>
