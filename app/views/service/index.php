<div class="container">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-primary">My Services</h5>
            <button class="btn btn-primary" data-toggle="modal" data-target="#createServiceModal">Add Service</button>
        </div>
        <div class="card-body">
            <table class="table">
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
                <?php foreach ($services as $service): ?>
                    <tr data-id="<?php echo $service->id; ?>">
                        <th scope="row"><?php echo $service->id; ?></th>
                        <td><?php echo htmlspecialchars($service->category_name); ?></td>
                        <td><?php echo htmlspecialchars($service->name); ?></td>
                        <td><?php echo htmlspecialchars($service->description); ?></td>
                        <td>
                            <input type="checkbox" class="form-check-input btn-toggle-publish" disabled <?php echo $service->is_published ? 'checked' : ''; ?>>
                        </td>
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

<!-- Modal for creating a new service -->
<div class="modal fade" id="createServiceModal" tabindex="-1" role="dialog" aria-labelledby="createServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createServiceModalLabel">Add New Service</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createServiceForm">
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select class="form-control" id="category" name="category_id" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category->id; ?>"><?php echo htmlspecialchars($category->category_name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="serviceTitle">Name</label>
                        <input type="text" class="form-control" id="serviceTitle" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="serviceDescription">Description</label>
                        <textarea class="form-control" id="serviceDescription" name="description" required></textarea>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="servicePublished" name="is_published">
                        <label class="form-check-label" for="servicePublished">Published</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const id = row.getAttribute('data-id');
                const nameCell = row.children[2];
                const descriptionCell = row.children[3];
                const publishCheckbox = row.querySelector('.btn-toggle-publish');

                if (button.innerText === 'Edit') {
                    nameCell.contentEditable = true;
                    descriptionCell.contentEditable = true;
                    publishCheckbox.disabled = false;
                    button.innerText = 'Save';
                } else {
                    const name = nameCell.innerText;
                    const description = descriptionCell.innerText;
                    const isPublished = publishCheckbox.checked ? 1 : 0;

                    fetch('/seha/public/service/updateService', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id, name, description, is_published: isPublished })
                    }).then(response => response.json()).then(data => {
                        if (data.status === 'success') {
                            nameCell.contentEditable = false;
                            descriptionCell.contentEditable = false;
                            publishCheckbox.disabled = true;
                            button.innerText = 'Edit';
                        } else {
                            alert('Failed to update service');
                        }
                    });
                }
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
                    } else {
                        alert('Failed to delete service');
                    }
                });
            });
        });

        document.getElementById('createServiceForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            fetch('/seha/public/service/createService', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            }).then(response => response.json()).then(data => {
                if (data.status === 'success') {
                    location.reload();
                } else {
                    alert('Failed to create service');
                }
            });
        });
    });
</script>
