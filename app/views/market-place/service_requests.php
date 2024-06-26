<!-- service_requests.php -->
<div class="container mt-4">
    <h2>Service Requests</h2>
    <div id="serviceRequests">
        <!-- Les requêtes de services seront chargées ici -->
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Charger les requêtes de services en attente
        fetch('/seha/public/service/getServiceRequests')
            .then(response => response.json())
            .then(data => {
                const serviceRequests = document.getElementById('serviceRequests');
                serviceRequests.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(request => {
                        const requestElement = document.createElement('div');
                        requestElement.className = 'request';
                        requestElement.innerHTML = `
                            <div>
                                <strong>${request.service_name}</strong> requested by ${request.requester_name} for ${request.requested_hours} hours
                                <button class="btn btn-success" onclick="updateRequestStatus(${request.id}, 2)">Accept</button>
                                <button class="btn btn-danger" onclick="updateRequestStatus(${request.id}, 3)">Decline</button>
                            </div>`;
                        serviceRequests.appendChild(requestElement);
                    });
                } else {
                    serviceRequests.innerHTML = '<div>No service requests found</div>';
                }
            });
    });

    function updateRequestStatus(requestId, statusId) {
        fetch('/seha/public/service/updateServiceRequestStatus', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ request_id: requestId, status_id: statusId })
        }).then(response => response.json()).then(data => {
            if (data.status === 'success') {
                location.reload();
            } else {
                alert('Failed to update request status');
            }
        });
    }
</script>


