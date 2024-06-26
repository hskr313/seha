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
        function searchServices() {
            const query = document.getElementById('searchInput').value;
            fetch('/seha/public/marketplace/search?query=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('#servicesTable tbody');
                    tableBody.innerHTML = ''; // Clear the existing table body

                    if (data.length > 0) {
                        data.forEach(service => {
                            const row = document.createElement('tr');

                            const nameCell = document.createElement('td');
                            nameCell.textContent = service.name;
                            nameCell.style.padding = '8px'; // Add cell style
                            row.appendChild(nameCell);

                            const descriptionCell = document.createElement('td');
                            descriptionCell.textContent = service.description;
                            descriptionCell.style.padding = '8px'; // Add cell style
                            row.appendChild(descriptionCell);

                            const usernameCell = document.createElement('td');
                            usernameCell.textContent = service.username ?? '';
                            usernameCell.style.padding = '8px'; // Add cell style
                            row.appendChild(usernameCell);

                            const actionsCell = document.createElement('td');
                            actionsCell.style.padding = '8px'; // Add cell style
                            const requestButton = document.createElement('button');
                            requestButton.className = 'btn btn-primary';
                            requestButton.setAttribute('data-toggle', 'modal');
                            requestButton.setAttribute('data-target', '#serviceModal');
                            requestButton.setAttribute('data-service-id', service.id);
                            requestButton.setAttribute('data-name', service.name);
                            requestButton.setAttribute('data-description', service.description);
                            requestButton.setAttribute('data-username', service.username);
                            requestButton.textContent = 'Request Service';
                            actionsCell.appendChild(requestButton);
                            row.appendChild(actionsCell);

                            tableBody.appendChild(row);
                        });
                    } else {
                        const row = document.createElement('tr');
                        const cell = document.createElement('td');
                        cell.setAttribute('colspan', '4');
                        cell.textContent = 'No services available.';
                        cell.style.textAlign = 'center'; // Add cell style
                        row.appendChild(cell);
                        tableBody.appendChild(row);
                    }
                });
        }

        document.getElementById('searchButton').addEventListener('click', searchServices);

        $('#serviceModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var serviceId = button.data('service-id');
            var name = button.data('name');
            var description = button.data('description');
            var username = button.data('username');

            var modal = $(this);
            modal.find('#modalServiceName').text(name);
            modal.find('#modalServiceDescription').text(description);
            modal.find('#modalServiceUsername').text('By ' + username);
            modal.find('#requestServiceButton').data('service-id', serviceId);
        });

        document.getElementById('requestServiceButton').addEventListener('click', function() {
            var serviceId = $(this).data('service-id');
            var hours = document.getElementById('hoursInput').value;

            if (hours <= 0) {
                alert('Please enter a valid number of hours.');
                return;
            }

            fetch('/seha/public/marketplace/requestService', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    service_id: serviceId,
                    hours: hours
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Service requested successfully.');
                        $('#serviceModal').modal('hide');
                    } else {
                        alert('Failed to request service: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        });

        $('#servicesTable').DataTable(); // Initialize DataTables
    });
</script>
